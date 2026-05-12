@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('menus.index') }}" class="text-gray-500 hover:text-gray-900 text-sm font-medium flex items-center gap-2 mb-4">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Manajemen Menu
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Ubah Menu: {{ $menu->name }}</h1>
</div>

@if($errors->any())
    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-medium border border-red-100">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Menu <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $menu->name) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-[#F5C400] focus:ring-1 focus:ring-[#F5C400] transition">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="price" value="{{ old('price', $menu->price) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-[#F5C400] focus:ring-1 focus:ring-[#F5C400] transition">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
            <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-[#F5C400] focus:ring-1 focus:ring-[#F5C400] transition">{{ old('description', $menu->description) }}</textarea>
        </div>

        <div x-data="{ showUpload: {{ $menu->image ? 'false' : 'true' }}, fileName: '' }">
            <label class="block text-sm font-bold text-gray-700 mb-2">Gambar Menu</label>
            
            {{-- Current image preview --}}
            @if($menu->image)
            <div x-show="!showUpload" class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <img src="{{ str_starts_with($menu->image, 'http') ? $menu->image : asset($menu->image) }}" class="h-24 w-24 rounded-xl object-cover border border-gray-200 shadow-sm">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-700">Gambar saat ini</p>
                    <button type="button" @click="showUpload = true" class="mt-2 text-sm font-bold text-[#F5C400] hover:text-[#e5b700] transition flex items-center gap-1">
                        <i class="fa-solid fa-pen"></i> Ubah Gambar
                    </button>
                </div>
            </div>
            @endif

            {{-- Upload area --}}
            <div x-show="showUpload" x-cloak>
                @if($menu->image)
                <button type="button" @click="showUpload = false; fileName = ''" class="text-xs text-gray-500 hover:text-gray-700 mb-2 flex items-center gap-1">
                    <i class="fa-solid fa-xmark"></i> Batal ubah gambar
                </button>
                @endif
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:bg-gray-50 transition cursor-pointer" onclick="document.getElementById('image').click()">
                    <div class="text-gray-400 text-3xl mb-2"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                    <p class="text-sm font-medium text-gray-600">Klik untuk mengunggah gambar baru</p>
                    <p class="text-xs text-gray-400 mt-1">Maksimal 2MB (JPG, PNG)</p>
                </div>
                <input type="file" id="image" name="image" accept="image/*" class="hidden" @change="fileName = $event.target.files[0]?.name || ''">
                <p class="text-xs text-[#F5C400] font-bold mt-2" x-text="fileName"></p>
            </div>
        </div>

        <div class="flex items-center gap-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
            <input type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', $menu->is_available) ? 'checked' : '' }} class="w-5 h-5 accent-[#F5C400] rounded cursor-pointer">
            <label for="is_available" class="font-bold text-gray-700 cursor-pointer">Menu Tersedia untuk Dipesan</label>
        </div>

        <div class="pt-4 border-t border-gray-100">
            <button type="submit" class="w-full bg-[#F5C400] text-gray-900 font-bold px-6 py-3 rounded-xl hover:bg-[#e5b700] transition shadow-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
