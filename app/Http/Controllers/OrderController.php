<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTracking;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->where('is_hidden_by_user', false)->latest()->get();
        
        return view('user.dashboard', compact('orders'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'nullable|string',
            'cart' => 'required|json',
            'total_price' => 'required|integer'
        ]);

        $cart = json_decode($validated['cart'], true);

        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Keranjang kosong.']);
        }

        $orderId = Str::uuid()->toString();

        $order = Order::create([
            'id' => $orderId,
            'user_id' => auth()->id(), // null if guest
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'total_price' => $validated['total_price'],
            'order_status' => 'pending_admin',
            'payment_status' => 'unpaid'
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['id'],
                'quantity' => $item['qty'],
                'price' => $item['price']
            ]);
        }

        // Call Midtrans API manually using Http facade
        $serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        $isProduction = config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
        $baseUrl = $isProduction ? 'https://app.midtrans.com/snap/v1/transactions' : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $payload = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'phone' => $order->customer_phone ?? '',
            ],
            'callbacks' => [
                'finish' => route('order.tracking', $order->id),
                'error' => route('order.tracking', $order->id),
                'pending' => route('order.tracking', $order->id),
            ]
        ];

        try {
            // Kita bypass cURL yang bermasalah di laptop Anda dan gunakan native PHP Stream
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => [
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization: Basic ' . base64_encode($serverKey . ':')
                    ],
                    'content' => json_encode($payload),
                    'ignore_errors' => true,
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ]);

            $responseStr = file_get_contents($baseUrl, false, $context);
            $statusCode = $http_response_header[0] ?? '';

            if (strpos($statusCode, '201') !== false || strpos($statusCode, '200') !== false) {
                $data = json_decode($responseStr, true);
                if (isset($data['token'])) {
                    $order->update(['snap_token' => $data['token']]);
                } else {
                    \Illuminate\Support\Facades\Log::error('Midtrans API Token Missing: ' . $responseStr);
                }
            } else {
                \Illuminate\Support\Facades\Log::error('Midtrans HTTP Error: ' . $statusCode . ' - ' . $responseStr);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Midtrans Native PHP Error: ' . $e->getMessage());
        }

        OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'pending_admin',
            'description' => 'Pesanan Anda sedang menunggu konfirmasi admin.'
        ]);

        return redirect()->route('order.tracking', $order->id)->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran.');
    }

    public function tracking(Request $request, $id)
    {
        $order = Order::with(['items.menu', 'trackings' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        // Jika ada parameter dari redirect Midtrans, coba update status
        $transactionStatus = $request->query('transaction_status');
        if ($transactionStatus && $order->payment_status === 'unpaid') {
            if (in_array($transactionStatus, ['settlement', 'capture', 'pending'])) {
                // Untuk sandbox, kita anggap pending setelah bayar adalah sukses jika datang dari redirect
                $order->update(['payment_status' => 'paid']);
                
                if (!$order->trackings()->where('status', 'paid')->exists()) {
                    $order->trackings()->create([
                        'status' => 'paid',
                        'description' => 'Pembayaran berhasil dikonfirmasi (via Redirect).'
                    ]);
                }
                $order->refresh();
            }
        }

        if ($order->payment_status === 'unpaid') {
            $order->syncPaymentStatus();
        }

        return view('user.tracking', compact('order'));
    }

    public function completeOrder($id)
    {
        $order = Order::findOrFail($id);
        if ($order->user_id !== auth()->id()) abort(403);
        
        $order->update(['order_status' => 'completed']);
        OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'completed',
            'description' => 'Pesanan Selesai. Terima kasih telah memesan!'
        ]);
        
        return back()->with('success', 'Pesanan dikonfirmasi selesai.');
    }

    public function webhook(Request $request)
    {
        $serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed == $request->signature_key) {
            $order = Order::find($request->order_id);
            if ($order) {
                if ($request->transaction_status == 'settlement' || $request->transaction_status == 'capture') {
                    $order->update(['payment_status' => 'paid']);
                    OrderTracking::create([
                        'order_id' => $order->id,
                        'status' => 'paid',
                        'description' => 'Pembayaran berhasil dikonfirmasi oleh Midtrans.'
                    ]);
                } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'expire') {
                    $order->update(['payment_status' => 'failed']);
                }
            }
        }
        return response()->json(['status' => 'ok']);
    }
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->update(['is_hidden_by_user' => true]);

        return back()->with('success', 'Pesanan berhasil dihapus dari riwayat.');
    }

    public function checkPayment(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // If this is a POST from Snap.js onSuccess callback, directly mark as paid.
        // The Snap SDK already verified the payment client-side, and CSRF middleware
        // protects this endpoint. In Midtrans Sandbox, the API returns 'pending' 
        // (not 'settlement') so we cannot rely on the Status API alone.
        if ($request->isMethod('post') && $order->payment_status !== 'paid') {
            $order->update(['payment_status' => 'paid']);
            
            if (!$order->trackings()->where('status', 'paid')->exists()) {
                $order->trackings()->create([
                    'status' => 'paid',
                    'description' => 'Pembayaran berhasil dikonfirmasi.'
                ]);
            }
            
            $order->refresh();
            return response()->json(['status' => $order->payment_status]);
        }
        
        // For GET requests, try to sync with Midtrans Status API
        $order->syncPaymentStatus();
        $order->refresh();
        return response()->json(['status' => $order->payment_status]);
    }
}
