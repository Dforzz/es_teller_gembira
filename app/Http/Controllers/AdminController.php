<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderTracking;

class AdminController extends Controller
{
    public function dashboard()
    {
        $orders = Order::with('items.menu')->latest()->get();
        
        return view('admin.dashboard', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $status = $request->input('status');
        
        $order->update(['order_status' => $status]);
        
        $description = 'Status pesanan diperbarui oleh Admin.';
        if ($status === 'processing') $description = 'Pesanan Anda sedang dibuat, mohon tunggu.';
        if ($status === 'delivering') $description = 'Pesanan dalam perjalanan.';
        if ($status === 'cancelled') $description = 'Pesanan Anda dibatalkan. Maaf stok sudah habis atau pesanan tidak dapat diproses.';
        
        OrderTracking::create([
            'order_id' => $order->id,
            'status' => $status,
            'description' => $description
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * API endpoint for AJAX polling — returns current payment statuses for all orders.
     */
    public function orderStatuses()
    {
        $orders = Order::select('id', 'payment_status', 'order_status')->get();

        $statuses = [];
        foreach ($orders as $order) {
            $statuses[$order->id] = [
                'payment_status' => $order->payment_status,
                'order_status' => $order->order_status,
            ];
        }

        return response()->json($statuses);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        
        return back()->with('success', 'Pesanan berhasil dihapus!');
    }
}
