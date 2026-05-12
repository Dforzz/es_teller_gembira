<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Es Teler Gembira</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col hidden md:flex shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-gray-100">
            <span class="font-bold text-lg text-dark-green">Es Teler <span class="text-[#F5C400]">Gembira</span></span>
        </div>
        <div class="p-4 flex-1 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-[#F5C400] text-gray-900 font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }} transition">
                <i class="fa-solid fa-house w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('menus.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('menus.*') ? 'bg-[#F5C400] text-gray-900 font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }} transition">
                <i class="fa-solid fa-list w-5 text-center"></i> Manajemen Menu
            </a>

            <div class="pt-4 mt-4 border-t border-gray-100">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 font-bold transition">
                        <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0">
        <!-- Header -->
        <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-6 shrink-0">
            <div class="md:hidden font-bold text-lg text-dark-green">Admin Panel</div>
            <div class="hidden md:block"></div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#F5C400] text-gray-900 flex items-center justify-center font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="flex-1 overflow-auto p-6">
            @yield('content')
        </div>
    </main>
</body>
</html>
