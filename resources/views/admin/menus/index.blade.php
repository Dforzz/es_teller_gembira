@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Menu</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola daftar menu yang tersedia di website.</p>
    </div>
    <a href="{{ route('menus.create') }}" class="bg-[#F5C400] text-gray-900 font-bold px-6 py-2.5 rounded-full hover:bg-[#e5b700] transition shadow-sm flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Menu
    </a>
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
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Gambar & Info</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($menus as $menu)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-4 px-6 flex items-center gap-4">
                        @if($menu->image)
                            <img src="{{ str_starts_with($menu->image, 'http') ? $menu->image : asset($menu->image) }}" class="w-12 h-12 rounded-xl object-cover shadow-sm border border-gray-100">
                        @else
                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        @endif
                        <div>
                            <div class="font-bold text-gray-900">{{ $menu->name }}</div>
                            <div class="text-xs text-gray-500 max-w-[200px] truncate">{{ $menu->description ?? '-' }}</div>
                        </div>
                    </td>

                    <td class="py-4 px-6 font-bold text-[#F5C400]">
                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-6">
                        @if($menu->is_available)
                            <span class="px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-700">Tersedia</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs font-bold bg-red-100 text-red-700">Habis</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('menus.edit', $menu->id) }}" class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($menus->isEmpty())
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300 text-2xl">
                    <i class="fa-solid fa-bowl-food"></i>
                </div>
                <p class="text-gray-500 text-sm">Belum ada menu. Silakan tambah menu baru.</p>
            </div>
        @endif
    </div>
</div>
@endsection
