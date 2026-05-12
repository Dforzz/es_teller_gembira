@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Pesanan</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola pesanan masuk dan perbarui status.</p>
    </div>
    <div class="flex items-center gap-2 text-xs text-gray-400">
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
        </span>
        Live Update
    </div>
</div>

{{-- Date Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-wrap items-end gap-4" x-data="dateFilter()" x-cloak>
    <div>
        <label class="block text-xs font-bold text-gray-500 mb-1">Dari Tanggal</label>
        <input type="date" x-model="startDate" @change="applyFilter()" class="border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-[#F5C400] focus:ring-1 focus:ring-[#F5C400]">
    </div>
    <div>
        <label class="block text-xs font-bold text-gray-500 mb-1">Sampai Tanggal</label>
        <input type="date" x-model="endDate" @change="applyFilter()" class="border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-[#F5C400] focus:ring-1 focus:ring-[#F5C400]">
    </div>
    <button @click="resetFilter()" class="text-xs font-bold text-gray-500 hover:text-gray-900 px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
        <i class="fa-solid fa-rotate-left mr-1"></i> Reset
    </button>
    <span class="text-xs text-gray-400 ml-auto" x-show="startDate || endDate">
        Menampilkan <span class="font-bold text-gray-700" x-text="visibleCount"></span> pesanan
    </span>
</div>

@if(session('success'))
    <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-6 text-sm font-medium border border-green-100 flex items-center gap-2">
        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Pembelian</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Total & Pembayaran</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Status Pengiriman</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50/50 transition order-row" data-date="{{ $order->created_at->format('Y-m-d') }}">
                    <td class="py-4 px-6 text-sm font-mono text-gray-600">
                        {{ substr($order->id, 0, 8) }}
                        @if($order->is_hidden_by_user)
                            <div class="mt-2"><span class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded border border-red-200">Dihapus User</span></div>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-sm font-bold text-gray-800">{{ $order->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-bold text-gray-900 text-sm">{{ $order->customer_name }}</div>
                        <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                            <i class="fa-brands fa-whatsapp text-green-500"></i> {{ $order->customer_phone ?? '-' }}
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-xs text-gray-600 mb-2 space-y-1">
                            @foreach($order->items as $item)
                                <div class="flex items-start gap-1.5">
                                    <span class="font-bold text-gray-900">{{ $item->quantity }}x</span>
                                    <span>{{ $item->menu ? $item->menu->name : 'Menu Dihapus' }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-sm font-bold text-[#F5C400] border-t border-gray-100 pt-2 mt-2">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                        <span id="payment-badge-{{ $order->id }}"
                              class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold transition-all duration-300
                              {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ strtoupper($order->payment_status) }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        @php
                            $bg = 'bg-gray-100 text-gray-700';
                            if($order->order_status === 'processing') $bg = 'bg-blue-100 text-blue-700';
                            if($order->order_status === 'delivering') $bg = 'bg-orange-100 text-orange-700';
                            if($order->order_status === 'completed') $bg = 'bg-green-100 text-green-700';
                            if($order->order_status === 'cancelled') $bg = 'bg-red-100 text-red-700';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $bg }}">
                            {{ str_replace('_', ' ', strtoupper($order->order_status)) }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <form action="{{ route('admin.order.status', $order->id) }}" method="POST" class="flex gap-2 items-center">
                                @csrf
                                <select name="status" class="bg-white border border-gray-200 rounded-lg px-3 py-1.5 text-xs font-medium outline-none focus:border-[#F5C400] focus:ring-1 focus:ring-[#F5C400]">
                                    <option value="pending_admin" @selected($order->order_status == 'pending_admin')>Menunggu</option>
                                    <option value="processing" @selected($order->order_status == 'processing')>Dibuat</option>
                                    <option value="delivering" @selected($order->order_status == 'delivering')>Dikirim</option>
                                    <option value="completed" @selected($order->order_status == 'completed')>Selesai</option>
                                    <option value="cancelled" @selected($order->order_status == 'cancelled')>Ditolak</option>
                                </select>
                                <button type="submit" class="bg-gray-900 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-gray-800 transition">Update</button>
                            </form>
                            
                            <form action="{{ route('admin.order.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesanan ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition" title="Hapus Pesanan">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($orders->isEmpty())
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300 text-2xl">
                    <i class="fa-solid fa-inbox"></i>
                </div>
                <p class="text-gray-500 text-sm">Belum ada pesanan masuk.</p>
            </div>
        @endif
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dateFilter', () => ({
            startDate: '',
            endDate: '',
            visibleCount: {{ $orders->count() }},
            applyFilter() {
                const rows = document.querySelectorAll('.order-row');
                let count = 0;
                rows.forEach(row => {
                    const date = row.dataset.date;
                    let show = true;
                    if (this.startDate && date < this.startDate) show = false;
                    if (this.endDate && date > this.endDate) show = false;
                    row.style.display = show ? '' : 'none';
                    if (show) count++;
                });
                this.visibleCount = count;
            },
            resetFilter() {
                this.startDate = '';
                this.endDate = '';
                document.querySelectorAll('.order-row').forEach(r => r.style.display = '');
                this.visibleCount = document.querySelectorAll('.order-row').length;
            }
        }));
    });

    // Auto-refresh payment statuses
    function refreshPaymentStatuses() {
        fetch("{{ route('admin.order.statuses') }}", { headers: { 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(statuses => {
            for (const [orderId, data] of Object.entries(statuses)) {
                const badge = document.getElementById('payment-badge-' + orderId);
                if (!badge) continue;
                const currentText = badge.textContent.trim();
                const newText = data.payment_status.toUpperCase();
                if (currentText !== newText) {
                    badge.textContent = newText;
                    badge.className = data.payment_status === 'paid'
                        ? 'inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 transition-all duration-300'
                        : 'inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 transition-all duration-300';
                    badge.style.transform = 'scale(1.2)';
                    setTimeout(() => { badge.style.transform = 'scale(1)'; }, 300);
                }
            }
        })
        .catch(err => console.error('Auto-refresh error:', err));
    }
    setInterval(refreshPaymentStatuses, 5000);
</script>
@endsection
