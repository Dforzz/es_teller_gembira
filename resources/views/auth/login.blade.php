<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Es Teler Gembira</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Selamat Datang Kembali</h1>
            <p class="text-gray-500 text-sm mt-2">Masuk ke akun Es Teler Gembira Anda</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 text-red-500 p-4 rounded-xl mb-6 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#F5C400] focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#F5C400] focus:border-transparent">
            </div>
            <button type="submit" class="w-full bg-[#F5C400] text-gray-900 font-bold py-3 rounded-xl hover:bg-[#e5b700] transition-colors mt-2">
                Masuk
            </button>
        </form>
        
        <p class="text-center text-sm text-gray-500 mt-6">
            Belum punya akun? <a href="{{ route('register') }}" class="text-[#F5C400] font-bold hover:underline">Daftar sekarang</a>
        </p>
    </div>
</body>
</html>
