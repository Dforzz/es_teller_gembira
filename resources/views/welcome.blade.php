<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Es Teler Gembira - Segarnya Bikin Gembira!</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="Es Teler Gembira hadir dengan rasa premium, creamy, dan topping melimpah. Cocok untuk semua usia dengan harga bersahabat.">

    <!-- Fonts are handled in app.css -->
    
    <!-- Vite CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        .font-heading { font-family: 'Baloo 2', 'Poppins', sans-serif; }
        
        /* Swiper Custom Pagination */
        .swiper-pagination-bullet-active {
            background: var(--color-primary) !important;
        }
        
        .bg-pattern {
            background-color: var(--color-cream);
            background-image: radial-gradient(var(--color-primary) 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="font-sans text-dark antialiased bg-cream selection:bg-primary selection:text-dark overflow-x-hidden" x-data="{ mobileMenuOpen: false }">

    <!-- 1. Navbar Sticky -->
    <nav x-data="{ scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)" 
         :class="{ 'bg-white/90 backdrop-blur-md shadow-sm py-3': scrolled, 'bg-transparent py-5': !scrolled }"
         class="fixed w-full z-50 transition-all duration-300">
        <div class="container mx-auto px-4 md:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-bowl-food"></i>
                    </div>
                    <span class="font-heading font-bold text-2xl text-dark-green tracking-wide">Es Teler <span class="text-primary">Gembira</span></span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="font-medium hover:text-primary transition-colors">Home</a>
                    <a href="#keunggulan" class="font-medium hover:text-primary transition-colors">Keunggulan</a>
                    <a href="#menu" class="font-medium hover:text-primary transition-colors">Menu</a>
                    @auth
                        <a href="{{ auth()->user()->role === 'admin' ? url('/admin/dashboard') : url('/dashboard') }}" class="font-medium hover:text-primary transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-medium hover:text-primary transition-colors">Login</a>
                    @endauth
                </div>

                <!-- CTA Button -->
                <div class="hidden md:block">
                    <a href="{{ url('/pesan') }}" class="bg-primary hover:bg-primary-hover text-dark font-semibold px-6 py-2.5 rounded-full shadow-lg shadow-primary/30 transition-all hover:-translate-y-1 inline-block">
                        Pesan Sekarang
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-2xl text-dark">
                    <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-collapse 
             class="md:hidden bg-white shadow-xl absolute w-full left-0 top-full"
             x-cloak>
            <div class="flex flex-col px-6 py-4 space-y-4">
                <a href="#home" @click="mobileMenuOpen = false" class="font-medium py-2 border-b border-gray-100">Home</a>
                <a href="#keunggulan" @click="mobileMenuOpen = false" class="font-medium py-2 border-b border-gray-100">Keunggulan</a>
                <a href="#menu" @click="mobileMenuOpen = false" class="font-medium py-2 border-b border-gray-100">Menu</a>
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? url('/admin/dashboard') : url('/dashboard') }}" @click="mobileMenuOpen = false" class="font-medium py-2 border-b border-gray-100">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="font-medium py-2 border-b border-gray-100">Login</a>
                @endauth
                <a href="{{ url('/pesan') }}" @click="mobileMenuOpen = false" class="bg-primary text-center text-dark font-semibold px-6 py-3 rounded-full mt-2">
                    Pesan Sekarang
                </a>
            </div>
        </div>
    </nav>

    <!-- 2. Hero Section -->
    <section id="home" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-gradient-to-br from-cream via-white to-primary/20">
        <!-- Decorative Background Mesh -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-primary/30 blur-[100px] animate-pulse"></div>
            <div class="absolute top-[20%] -right-[10%] w-[50%] h-[50%] rounded-full bg-secondary/20 blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute -bottom-[10%] left-[20%] w-[40%] h-[40%] rounded-full bg-pink-jelly/20 blur-[100px] animate-pulse" style="animation-delay: 4s;"></div>
        </div>
        
        <div class="container mx-auto px-4 md:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <!-- Text Content -->
                <div class="w-full lg:w-1/2 text-center lg:text-left" data-aos="fade-right">
                    <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-sm px-4 py-2 rounded-full text-dark-green font-semibold text-sm mb-6 shadow-sm border border-white/50">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                        </span>
                        <span>Ayo Pesan Sekarang!!!</span>
                    </div>
                    <h1 class="font-heading font-extrabold text-5xl md:text-6xl lg:text-7xl leading-[1.1] mb-6 text-dark">
                        Segarnya Bikin <br/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-pink-jelly">Bahagia!</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-700 mb-8 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        Nikmati perpaduan alpukat, nangka, kelapa, jelly, susu creamy dan topping melimpah dengan harga ramah kantong.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ url('/pesan') }}" class="px-8 py-4 bg-primary hover:bg-primary-hover text-dark font-semibold rounded-full shadow-lg shadow-primary/30 transition-all hover:-translate-y-1 flex items-center justify-center gap-2">
                            Pesan Sekarang
                        </a>
                        <a href="#menu" class="px-8 py-4 bg-white/80 backdrop-blur text-dark font-semibold rounded-full border border-gray-200 hover:bg-white hover:shadow-lg transition-all flex items-center justify-center gap-2">
                            Lihat Menu <i class="fa-solid fa-bowl-food text-primary"></i>
                        </a>
                    </div>
                </div>

                <!-- Image/Mockup -->
                <div class="w-full lg:w-1/2 relative" data-aos="fade-left" data-aos-delay="200">
                    <div class="relative w-full max-w-lg mx-auto aspect-square flex items-center justify-center">
                        <!-- Blob Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary/40 to-secondary/40 blob-shape backdrop-blur-3xl"></div>
                        
                        <!-- Main Product Image Placeholder -->
                        <div class="relative z-10 w-4/5 h-4/5 rounded-[40px] shadow-2xl p-2 transform rotate-3 hover:rotate-0 transition-transform duration-500 bg-white/40 backdrop-blur-sm border border-white/50">
                            <img src="{{ asset('images/untukutama.png') }}" alt="Es Teler Gembira" class="w-full h-full object-cover rounded-[32px] shadow-inner">
                            
                            <!-- Floating Card 1 -->
                            <div class="absolute -top-4 -right-4 glass px-4 py-3 rounded-2xl shadow-xl transform rotate-6 animate-float">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center text-primary">
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500 font-medium">Rating</div>
                                        <div class="font-bold text-dark text-sm">4.9/5.0</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Card 2 -->
                            <div class="absolute -bottom-6 -left-6 glass px-5 py-4 rounded-2xl shadow-xl transform -rotate-6 animate-float-delayed">
                                <div class="text-xs text-gray-500 font-medium mb-1">Mulai dari</div>
                                <div class="font-bold text-dark-green text-2xl font-heading">Rp 10.000</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Section Keunggulan -->
    <section id="keunggulan" class="py-24 bg-white relative overflow-hidden">
        <div class="container mx-auto px-4 md:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-secondary font-bold tracking-wider uppercase text-sm mb-2 block">Keunggulan Kami</span>
                <h2 class="font-heading font-bold text-3xl md:text-5xl text-dark mb-4">Kenapa Memilih <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Es Teler Gembira?</span></h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Kami berkomitmen memberikan kualitas terbaik dengan bahan-bahan pilihan yang bikin nagih.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1 -->
                <div class="group bg-white rounded-3xl p-8 text-center shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-secondary/20 to-secondary/5 rounded-2xl flex items-center justify-center text-3xl text-secondary mb-6 group-hover:scale-110 group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-leaf"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-dark">Bahan Segar</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Menggunakan buah segar setiap hari langsung dari petani lokal terbaik.</p>
                </div>

                <!-- Card 2 -->
                <div class="group bg-white rounded-3xl p-8 text-center shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-primary/20 to-primary/5 rounded-2xl flex items-center justify-center text-3xl text-primary mb-6 group-hover:scale-110 group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-bowl-food"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-dark">Creamy & Melimpah</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Resep rahasia kuah susu creamy dengan topping yang nggak pelit sama sekali.</p>
                </div>

                <!-- Card 3 -->
                <div class="group bg-white rounded-3xl p-8 text-center shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-pink-jelly/20 to-pink-jelly/5 rounded-2xl flex items-center justify-center text-3xl text-pink-jelly mb-6 group-hover:scale-110 group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-dark">Harga Bersahabat</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Kualitas premium dengan harga yang aman dan nyaman di kantong.</p>
                </div>

                <!-- Card 4 -->
                <div class="group bg-white rounded-3xl p-8 text-center shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-dark-green/20 to-dark-green/5 rounded-2xl flex items-center justify-center text-3xl text-dark-green mb-6 group-hover:scale-110 group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-face-smile-beam"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-dark">Cocok Semua Usia</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Aman dan disukai oleh anak-anak, remaja, hingga orang dewasa.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Menu Unggulan (Swiper Slider) -->
    <section id="menu" class="py-24 bg-dark relative overflow-hidden">
        <!-- Dark mode decorative elements -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-[80px]"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-secondary/10 rounded-full blur-[100px]"></div>

        <div class="container mx-auto px-4 md:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12" data-aos="fade-up">
                <div>
                    <span class="text-primary font-bold tracking-wider uppercase text-sm mb-2 block">Menu Spesial</span>
                    <h2 class="font-heading font-bold text-3xl md:text-5xl text-white">Pilihan <span class="text-secondary">Favorit</span></h2>
                </div>
                <div class="mt-6 md:mt-0 flex gap-3">
                    <button class="swiper-button-prev-custom w-12 h-12 rounded-full border border-white/20 bg-white/5 text-white backdrop-blur-md flex items-center justify-center hover:bg-primary hover:border-primary hover:text-dark transition-all">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <button class="swiper-button-next-custom w-12 h-12 rounded-full border border-white/20 bg-white/5 text-white backdrop-blur-md flex items-center justify-center hover:bg-primary hover:border-primary hover:text-dark transition-all">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Swiper Container -->
            <div class="swiper menuSwiper pb-16" data-aos="fade-up" data-aos-delay="200">
                <div class="swiper-wrapper">
                    @foreach($menus as $menu)
                    <div class="swiper-slide">
                        <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-[32px] p-6 shadow-2xl group hover:-translate-y-2 transition-all duration-300">
                            <div class="relative rounded-2xl overflow-hidden aspect-[4/3] mb-6">
                                @if($menu->image)
                                    <img src="{{ str_starts_with($menu->image, 'http') ? $menu->image : asset($menu->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $menu->name }}">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                        <i class="fa-solid fa-bowl-food text-5xl text-white/50"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-dark/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            <h3 class="font-bold text-2xl mb-2 font-heading text-white">{{ $menu->name }}</h3>
                            <p class="text-gray-400 text-sm mb-6 line-clamp-2">{{ $menu->description ?? 'Menu spesial dari Es Teler Gembira.' }}</p>
                            <div class="flex justify-between items-center pt-4 border-t border-white/10">
                                <div>
                                    <div class="text-xs text-gray-400 mb-1">Harga</div>
                                    <span class="font-bold text-2xl text-primary">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                </div>
                                <a href="{{ url('/pesan') }}" class="w-12 h-12 bg-white/10 text-white rounded-full flex items-center justify-center hover:bg-primary hover:text-dark transition-all transform hover:scale-110">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- Pagination -->
                <div class="swiper-pagination mt-8 relative"></div>
            </div>
            
            <div class="text-center mt-12" data-aos="fade-up">
                <a href="{{ url('/pesan') }}" class="inline-block relative group">
                    <div class="absolute inset-0 bg-primary rounded-full blur-md opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative bg-dark border border-primary text-primary font-bold px-8 py-4 rounded-full hover:bg-primary hover:text-dark transition-all flex items-center gap-2">
                        Lihat Semua Menu <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>
            </div>
        </div>
    </section>



    <!-- 10. Footer -->
    <footer class="bg-dark text-white pt-20 pb-10">
        <div class="container mx-auto px-4 md:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2 lg:col-span-1">
                    <a href="#" class="flex items-center gap-2 mb-6">
                        <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-dark font-bold text-xl">
                            <i class="fa-solid fa-bowl-food"></i>
                        </div>
                        <span class="font-heading font-bold text-2xl text-white tracking-wide">Es Teler <span class="text-primary">Gembira</span></span>
                    </a>
                    <p class="text-gray-400 text-sm mb-6">Minuman segar, dessert creamy, es teler modern pilihan keluarga Indonesia.</p>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="font-bold text-lg mb-6 border-b border-white/10 pb-2 inline-block">Navigasi</h4>
                    <ul class="space-y-3">
                        <li><a href="#home" class="text-gray-400 hover:text-primary transition-colors text-sm">Home</a></li>
                        <li><a href="#keunggulan" class="text-gray-400 hover:text-primary transition-colors text-sm">Keunggulan</a></li>
                        <li><a href="#menu" class="text-gray-400 hover:text-primary transition-colors text-sm">Menu</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-span-1 md:col-span-2">
                    <h4 class="font-bold text-lg mb-6 border-b border-white/10 pb-2 inline-block">Hubungi Kami</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-location-dot text-primary mt-1"></i>
                            <span class="text-gray-400 text-sm">Jl. Timur Indah 5, Timur Indah Residence 2, Sido Mulyo, Gading Cempaka, Kota Bengkulu</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-brands fa-whatsapp text-primary"></i>
                            <span class="text-gray-400 text-sm">+62 822-3884-6002</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/10 pt-8 text-center md:text-left flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} Es Teler Gembira. All rights reserved.</p>
                <div class="text-gray-500 text-sm">
                    Dibuat untuk UMKM Indonesia
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/6282238846002?text=Halo%20Es%20Teler%20Gembira,%20saya%20mau%20pesan" 
       target="_blank"
       class="fixed bottom-6 right-6 w-14 h-14 bg-[#25D366] text-white rounded-full shadow-2xl flex items-center justify-center text-3xl z-50 hover:scale-110 transition-transform hover:bg-[#20bd5a] group animate-bounce">
        <i class="fa-brands fa-whatsapp"></i>
        <span class="absolute right-16 bg-white text-dark text-sm font-bold px-3 py-1.5 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
            Pesan via WA
        </span>
    </a>

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                offset: 50,
                duration: 800,
                easing: 'ease-out-cubic',
            });
            
            // Initialize Swiper
            const swiper = new Swiper('.menuSwiper', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next-custom',
                    prevEl: '.swiper-button-prev-custom',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                }
            });
        });
    </script>
</body>
</html>
