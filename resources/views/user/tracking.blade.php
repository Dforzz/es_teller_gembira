<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Pesanan - Es Teler Gembira</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Midtrans Snap JS -->
    <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com' }}/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key', env('MIDTRANS_CLIENT_KEY')) }}"></script>
</head>
<body class="bg-gray-50 font-sans min-h-screen pb-10">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ auth()->check() ? '/dashboard' : '/' }}" class="flex items-center gap-2 text-gray-500 hover:text-gray-900 text-sm font-medium">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <div class="font-bold text-lg text-gray-900">Detail Pesanan</div>
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-gray-500 hover:text-[#F5C400] text-sm font-medium transition-colors">
                <i class="fa-solid fa-house"></i> <span class="hidden sm:inline">Beranda</span>
            </a>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 mt-6 space-y-6">
        @if(session('success'))
            <div class="bg-green-50 text-green-600 p-4 rounded-xl text-sm font-medium border border-green-100">
                <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Header -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Nomor Pesanan</p>
                    <p class="font-mono text-sm font-bold text-gray-900">{{ strtoupper(substr($order->id, 0, 8)) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                    <p class="font-bold text-[#F5C400] text-lg">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>
            
            @php
                $statusColor = 'bg-yellow-100 text-yellow-700';
                $statusText = 'Menunggu Konfirmasi';
                if($order->order_status === 'processing') { $statusColor = 'bg-blue-100 text-blue-700'; $statusText = 'Sedang Dibuat'; }
                if($order->order_status === 'delivering') { $statusColor = 'bg-orange-100 text-orange-700'; $statusText = 'Dalam Perjalanan'; }
                if($order->order_status === 'completed') { $statusColor = 'bg-green-100 text-green-700'; $statusText = 'Selesai'; }
                if($order->order_status === 'cancelled') { $statusColor = 'bg-red-100 text-red-700'; $statusText = 'Dibatalkan'; }
            @endphp
            <div class="mt-4 flex items-center justify-between pt-4 border-t border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-700">Status:</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }}">{{ $statusText }}</span>
                </div>
                
                @if($order->payment_status === 'unpaid')
                    @if($order->snap_token)
                    <button id="pay-button" class="bg-[#F5C400] text-gray-900 text-xs font-bold px-4 py-2 rounded-lg hover:bg-[#e5b700] transition flex items-center gap-2">
                        <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                    </button>
                    @else
                    <span class="text-xs text-red-500 font-bold bg-red-50 px-3 py-1 rounded-full">Gagal memuat tagihan. Buat pesanan baru.</span>
                    @endif
                @else
                    <button disabled class="bg-green-100 text-green-700 text-xs font-bold px-4 py-2 rounded-lg cursor-default flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i> Sudah Dibayar
                    </button>
                @endif
                
                @if($order->order_status === 'delivering' && auth()->id() === $order->user_id)
                <form action="{{ route('order.complete', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-green-600 transition">
                        Pesanan Diterima
                    </button>
                </form>
                @endif
            </div>
        </div>

        @if($order->payment_status === 'unpaid' && $order->snap_token)
        <script>
            const paymentUrl = "{{ route('order.check_payment', $order->id) }}";
            const csrfToken = '{{ csrf_token() }}';
            const payBtn = document.getElementById('pay-button');

            // POST to mark as paid — auto-triggered after Snap.js payment
            const markAsPaid = (retries = 3) => {
                // Show loading state on button
                payBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memverifikasi...';
                payBtn.disabled = true;
                payBtn.classList.add('opacity-70', 'cursor-wait');

                fetch(paymentUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ marked_paid: true })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'paid') {
                        payBtn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Pembayaran Berhasil!';
                        payBtn.classList.remove('bg-\\[\\#F5C400\\]');
                        payBtn.classList.add('bg-green-500', 'text-white');
                        setTimeout(() => window.location.reload(), 800);
                    } else if (retries > 1) {
                        setTimeout(() => markAsPaid(retries - 1), 1500);
                    }
                })
                .catch(err => {
                    console.error('Payment check error:', err);
                    if (retries > 1) {
                        setTimeout(() => markAsPaid(retries - 1), 2000);
                    }
                });
            };

            payBtn.onclick = function () {
                snap.pay('{{ $order->snap_token }}', {
                    onSuccess: function (result) {
                        console.log('Snap Success:', result);
                        markAsPaid();
                    },
                    onPending: function (result) {
                        console.log('Snap Pending:', result);
                        markAsPaid();
                    },
                    onError: function (result) {
                        console.error('Snap Error:', result);
                    },
                    onClose: function () {
                        console.log('Snap Closed');
                    }
                });
            };
        </script>
        @endif

        <!-- Tracking Timeline -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="font-bold text-gray-900 mb-6">Lacak Pesanan</h2>
            <div class="relative pl-6 space-y-6">
                <!-- Line background -->
                <div class="absolute top-2 bottom-2 left-[11px] w-0.5 bg-gray-100"></div>

                @foreach($order->trackings as $index => $track)
                    <div class="relative z-10 flex gap-4">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 mt-0.5 {{ $index === 0 ? 'bg-[#F5C400] text-white shadow-md' : 'bg-gray-200 text-gray-400' }}">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $track->status)) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $track->description }}</p>
                            <p class="text-[10px] text-gray-400 mt-1">{{ $track->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Items -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="font-bold text-gray-900 mb-4">Rincian Menu</h2>
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-3">
                        <img src="{{ $item->menu->image ?? 'https://placehold.co/48x48/FFF8E7/F5C400?text=M' }}" class="w-12 h-12 rounded-xl object-cover bg-gray-50">
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-900">{{ $item->menu->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="font-bold text-sm text-gray-900">
                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
