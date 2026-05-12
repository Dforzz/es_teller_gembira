<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasUuids;

    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function trackings()
    {
        return $this->hasMany(OrderTracking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function syncPaymentStatus()
    {
        if ($this->payment_status === 'paid') return true;

        $serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        if (!$serverKey) return false;

        $isProduction = config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
        $baseUrl = $isProduction 
            ? "https://api.midtrans.com/v2/{$this->id}/status" 
            : "https://api.sandbox.midtrans.com/v2/{$this->id}/status";

        try {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => [
                        'Accept: application/json',
                        'Content-Type: application/json',
                        'Authorization: Basic ' . base64_encode($serverKey . ':')
                    ],
                    'ignore_errors' => true,
                    'timeout' => 3
                ],
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
            ]);

            $responseStr = file_get_contents($baseUrl, false, $context);
            if (!$responseStr) {
                \Illuminate\Support\Facades\Log::warning("Midtrans Sync: No response for Order {$this->id}");
                return false;
            }

            $data = json_decode($responseStr, true);
            \Illuminate\Support\Facades\Log::info("Midtrans Sync for Order {$this->id}: " . ($data['transaction_status'] ?? 'no status'));

            if (isset($data['transaction_status'])) {
                $status = $data['transaction_status'];
                // In sandbox, 'pending' often means payment has been made but not yet settled.
                // We include 'pending' here to make testing easier on localhost.
                if (in_array($status, ['settlement', 'capture', 'pending'])) {
                    $this->update(['payment_status' => 'paid']);
                    
                    if (!$this->trackings()->where('status', 'paid')->exists()) {
                        $this->trackings()->create([
                            'status' => 'paid',
                            'description' => 'Pembayaran berhasil dikonfirmasi.'
                        ]);
                    }
                    return true;
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Sync Payment Failed for Order {$this->id}: " . $e->getMessage());
        }
        return false;
    }
}
