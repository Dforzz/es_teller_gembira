<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Es Teler Gembira</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans pb-10 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="font-bold text-xl text-dark-green tracking-wide flex items-center gap-2">
                <i class="fa-solid fa-bowl-food text-[#F5C400]"></i> Es Teler <span class="text-[#F5C400]">Gembira</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-red-500 text-sm font-bold hover:underline">Keluar</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 max-w-4xl mx-auto w-full px-4 mt-6">
        @yield('content')
    </main>
</body>
</html>
