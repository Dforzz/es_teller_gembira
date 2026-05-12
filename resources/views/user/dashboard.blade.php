@extends('layouts.user')

@section('content')
{{-- Welcome Card --}}
<div class="bg-gradient-to-r from-[#F5C400] to-[#e5b700] p-6 md:p-8 rounded-3xl shadow-sm mb-8 relative overflow-hidden">
    <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full"></div>
    <div class="absolute -bottom-6 -right-16 w-40 h-40 bg-white/5 rounded-full"></div>
    <div class="relative z-10 flex items-center gap-4">
        <div class="w-16 h-16 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center text-2xl font-bold text-gray-900 shrink-0 border-2 border-white/50">
            {{ substr(auth()->user()->name, 0, 1) }}
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Halo, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-800/70 text-sm mt-1"><i class="fa-solid fa-envelope mr-1"></i> {{ auth()->user()->email }}</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-6 text-sm font-medium border border-green-100 flex items-center gap-2">
        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

{{-- Quick Actions --}}
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-900">Riwayat Pesanan</h2>
    <a href="/pesan" class="bg-[#F5C400] text-gray-900 font-bold text-sm px-5 py-2.5 rounded-full hover:bg-[#e5b700] transition shadow-sm flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Pesan Lagi
    </a>
</div>

@if($orders->isEmpty())
<div class="bg-white p-12 rounded-3xl shadow-sm border border-gray-100 text-center">
    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-3xl">
        <i class="fa-solid fa-receipt"></i>
    </div>
    <h3 class="font-bold text-gray-900 text-lg mb-2">Belum ada pesanan</h3>
    <p class="text-gray-500 mb-6 text-sm">Yuk, mulai pesan Es Teler Gembira favoritmu sekarang!</p>
    <a href="/pesan" class="inline-block bg-[#F5C400] text-gray-900 font-bold px-8 py-3 rounded-full hover:bg-[#e5b700] transition shadow-lg shadow-[#F5C400]/20">Pesan Sekarang</a>
</div>
@else
<div class="space-y-4">
    @foreach($orders as $order)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-[#F5C400] hover:shadow-md transition group relative overflow-hidden">
        {{-- Link transparan untuk navigasi --}}
        <a href="{{ route('order.tracking', $order->id) }}" class="absolute inset-0 z-10"></a>
        
        {{-- Tombol hapus --}}
        <form action="{{ route('order.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat pesanan ini?')" class="absolute top-4 right-4 z-20">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-8 h-8 rounded-full bg-gray-50 hover:bg-red-50 text-gray-300 hover:text-red-500 transition flex items-center justify-center text-sm">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </form>

        <div class="p-5 flex items-center gap-4">
            {{-- Status icon --}}
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0
                @if($order->order_status === 'completed') bg-green-50 text-green-500
                @elseif($order->order_status === 'cancelled') bg-red-50 text-red-500
                @elseif($order->order_status === 'delivering') bg-orange-50 text-orange-500
                @elseif($order->order_status === 'processing') bg-blue-50 text-blue-500
                @else bg-gray-50 text-gray-400
                @endif">
                @if($order->order_status === 'completed')
                    <i class="fa-solid fa-circle-check text-xl"></i>
                @elseif($order->order_status === 'cancelled')
                    <i class="fa-solid fa-circle-xmark text-xl"></i>
                @elseif($order->order_status === 'delivering')
                    <i class="fa-solid fa-truck text-xl"></i>
                @elseif($order->order_status === 'processing')
                    <i class="fa-solid fa-blender text-xl"></i>
                @else
                    <i class="fa-solid fa-clock text-xl"></i>
                @endif
            </div>

            {{-- Order info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs text-gray-400 font-mono">#{{ strtoupper(substr($order->id, 0, 8)) }}</span>
                    <span class="text-xs text-gray-300">&bull;</span>
                    <span class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                <p class="text-lg font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                <div class="flex items-center gap-2 mt-1">
                    @php
                        $statusLabel = str_replace('_', ' ', strtoupper($order->order_status));
                        $statusClass = 'bg-gray-100 text-gray-600';
                        if($order->order_status === 'processing') $statusClass = 'bg-blue-100 text-blue-700';
                        if($order->order_status === 'delivering') $statusClass = 'bg-orange-100 text-orange-700';
                        if($order->order_status === 'completed') $statusClass = 'bg-green-100 text-green-700';
                        if($order->order_status === 'cancelled') $statusClass = 'bg-red-100 text-red-700';
                    @endphp
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $statusClass }}">{{ $statusLabel }}</span>
                    @if($order->payment_status === 'paid')
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700">PAID</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-500">UNPAID</span>
                    @endif
                </div>
            </div>

            {{-- Arrow --}}
            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-[#F5C400] group-hover:text-gray-900 transition-colors shrink-0">
                <i class="fa-solid fa-chevron-right"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
