@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('menus.index') }}" class="text-gray-500 hover:text-gray-900 text-sm font-medium flex items-center gap-2 mb-4">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Manajemen Menu
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Tambah Menu Baru</h1>
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
    <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Menu <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-[#F5C400] focus:ring-1 focus:ring-[#F5C400] transition">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="price" value="{{ old('price') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-[#F5C400] focus:ring-1 focus:ring-[#F5C400] transition" placeholder="Contoh: 15000">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
            <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-[#F5C400] focus:ring-1 focus:ring-[#F5C400] transition">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Gambar Menu (Opsional)</label>
            <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:bg-gray-50 transition cursor-pointer" onclick="document.getElementById('image').click()">
                <div class="text-gray-400 text-3xl mb-2"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                <p class="text-sm font-medium text-gray-600">Klik untuk mengunggah gambar</p>
                <p class="text-xs text-gray-400 mt-1">Maksimal 2MB (JPG, PNG)</p>
            </div>
            <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="document.getElementById('file-name').innerText = this.files[0].name">
            <p id="file-name" class="text-xs text-[#F5C400] font-bold mt-2"></p>
        </div>

        <div class="flex items-center gap-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
            <input type="checkbox" id="is_available" name="is_available" value="1" checked class="w-5 h-5 accent-[#F5C400] rounded cursor-pointer">
            <label for="is_available" class="font-bold text-gray-700 cursor-pointer">Menu Tersedia untuk Dipesan</label>
        </div>

        <div class="pt-4 border-t border-gray-100">
            <button type="submit" class="w-full bg-[#F5C400] text-gray-900 font-bold px-6 py-3 rounded-xl hover:bg-[#e5b700] transition shadow-sm">
                Simpan Menu Baru
            </button>
        </div>
    </form>
</div>
@endsection
