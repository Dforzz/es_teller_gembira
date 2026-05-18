<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesan Menu - Es Teler Gembira</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .font-heading { font-family: 'Baloo 2', 'Poppins', sans-serif; }
        .cart-scroll::-webkit-scrollbar { width: 4px; }
        .cart-scroll::-webkit-scrollbar-track { background: #f8fafc; border-radius: 4px; }
        .cart-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .card-hover { transition: box-shadow 0.25s ease, transform 0.25s ease; }
        .card-hover:hover { box-shadow: 0 12px 40px rgba(245,196,0,0.15); transform: translateY(-3px); }
        .pill-active { background: #222222; color: #fff; }
        .pill-inactive { background: #fff; color: #6b7280; border: 1px solid #e5e7eb; }
        .pill-inactive:hover { border-color: #F5C400; color: #F5C400; }
        .qty-btn { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #f1f5f9; font-size: 11px; transition: background 0.15s; }
        .qty-btn:hover { background: #e2e8f0; }
        .add-btn { width: 36px; height: 36px; border-radius: 50%; background: #F5C400; color: #222; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; transition: background 0.2s, transform 0.15s; }
        .add-btn:hover { background: #159A74; color: #fff; transform: scale(1.1); }
        .add-btn:active { transform: scale(0.95); }
        .wa-btn { background: #25D366; }
        .wa-btn:hover { background: #1db954; }
        .hide-arrows::-webkit-outer-spin-button, .hide-arrows::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .hide-arrows { -moz-appearance: textfield; }
    </style>
</head>
<body class="bg-gray-50 font-sans text-dark antialiased min-h-screen pb-28 lg:pb-0" x-data="orderApp()">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Back Button -->
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-gray-500 hover:text-dark transition-colors text-sm font-medium w-1/4">
                    <i class="fa-solid fa-arrow-left"></i> <span class="hidden sm:inline">Kembali</span>
                </a>
                
                <!-- Logo Centered -->
                <a href="{{ url('/') }}" class="flex items-center justify-center gap-2 w-2/4">
                    <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        <i class="fa-solid fa-bowl-food"></i>
                    </div>
                    <span class="font-heading font-bold text-lg md:text-xl text-dark-green whitespace-nowrap">Es Teler <span class="text-primary">Gembira</span></span>
                </a>
                
                <!-- Mobile cart badge button / Desktop Spacer -->
                <div class="flex justify-end w-1/4">
                    <button @click="mobileCartOpen = true" class="lg:hidden relative flex items-center gap-1.5 bg-dark text-white text-sm font-semibold px-3 py-1.5 rounded-full">
                        <i class="fa-solid fa-basket-shopping text-primary text-xs"></i>
                        <span x-text="totalItems + ' item'"></span>
                        <span x-show="totalItems > 0" class="absolute -top-1.5 -right-1.5 bg-pink-jelly text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center" x-cloak x-text="totalItems"></span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row gap-6">

            <!-- ── LEFT: Menu ── -->
            <div class="flex-1 min-w-0">

                <!-- Page title (compact) -->
                <div class="mb-6">
                    <h1 class="font-heading font-bold text-3xl text-dark">Pilih Menu</h1>
                    <p class="text-sm text-gray-500 mt-1">Tambahkan ke keranjang, lalu checkout via WhatsApp.</p>
                </div>

                <!-- Menu Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-3 gap-4">
                    @foreach($menus as $menu)
                        <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 card-hover cursor-pointer relative flex flex-col">
                            <!-- Image -->
                            <div class="aspect-[4/3] overflow-hidden bg-gray-100 relative">
                                <img src="{{ $menu->image }}" alt="{{ $menu->name }}" class="w-full h-full object-cover"
                                     loading="lazy"
                                     onerror="this.src='https://placehold.co/400x300/FFF8E7/F5C400?text=🍧'">
                            </div>
                            <!-- Body -->
                            <div class="p-3 flex flex-col flex-grow">
                                <h3 class="font-bold text-sm text-dark leading-snug mb-1">{{ $menu->name }}</h3>
                                <p class="text-xs text-gray-400 line-clamp-2 flex-grow mb-3">{{ $menu->description }}</p>
                                <div class="flex items-center justify-between mt-auto">
                                    <span class="font-bold text-sm text-dark-green">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                    <button @click.stop="addToCart({{ $menu->toJson() }})" class="add-btn">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ── RIGHT: Cart (desktop only) ── -->
            <div class="hidden lg:block w-72 xl:w-80 shrink-0">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm sticky top-20 overflow-hidden">
                    <!-- Cart header -->
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-basket-shopping text-primary text-sm"></i>
                            <span class="font-bold text-base text-dark">Keranjang</span>
                        </div>
                        <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded-full" x-text="totalItems + ' item'"></span>
                    </div>

                    <!-- Empty state -->
                    <div x-show="cart.length === 0" class="text-center py-10 px-6" x-cloak>
                        <div class="text-5xl mb-3 text-gray-300">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </div>
                        <p class="text-sm font-semibold text-dark mb-1">Keranjang kosong</p>
                        <p class="text-xs text-gray-400">Pilih menu dan tambahkan ke sini!</p>
                    </div>

                    <!-- Cart items -->
                    <div x-show="cart.length > 0" class="max-h-72 overflow-y-auto cart-scroll divide-y divide-gray-50" x-cloak>
                        <template x-for="cartItem in cart" :key="cartItem.id">
                            <div class="flex items-center gap-3 px-4 py-3">
                                <img :src="cartItem.image" class="w-12 h-12 rounded-xl object-cover shrink-0 bg-gray-100"
                                     onerror="this.src='https://placehold.co/48x48/FFF8E7/F5C400?text=🍧'">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-dark truncate" x-text="cartItem.name"></p>
                                    <p class="text-xs text-primary font-bold mt-0.5" x-text="formatRupiah(cartItem.price * cartItem.qty)"></p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <button @click="removeCartItem(cartItem.id)" class="qty-btn !bg-red-50 !text-red-500 hover:!bg-red-100">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                    <input type="number" min="1" max="100" class="w-14 h-8 text-center border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-primary font-bold text-dark hide-arrows" x-model.number="cartItem.qty" @blur="if(!cartItem.qty || cartItem.qty < 1) removeCartItem(cartItem.id); else if(cartItem.qty > 100) { cartItem.qty = 100; alert('Maksimal pesanan per menu adalah 100 pcs. Silakan buat pesanan baru jika ingin memesan lebih banyak.'); }" @input="if($event.target.value > 100) { cartItem.qty = 100; alert('Maksimal pesanan per menu adalah 100 pcs. Silakan buat pesanan baru jika ingin memesan lebih banyak.'); }">
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Order summary & checkout -->
                    <div x-show="cart.length > 0" class="px-5 py-4 border-t border-gray-100 bg-gray-50" x-cloak>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm text-gray-500">Total</span>
                            <span class="font-bold text-lg text-dark font-heading" x-text="formatRupiah(totalPrice)"></span>
                        </div>
                        <form action="{{ route('checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cart" :value="JSON.stringify(cart)">
                            <input type="hidden" name="total_price" :value="totalPrice">
                            
                            <div class="mb-3 space-y-2">
                                <input type="text" name="customer_name" required placeholder="Nama Anda" class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-primary" value="{{ auth()->user()->name ?? '' }}">
                                <input type="text" name="customer_phone" placeholder="Nomor WhatsApp" class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-primary" value="{{ auth()->user()->phone ?? '' }}">
                            </div>

                            <button type="submit" class="w-full bg-[#F5C400] text-gray-900 font-bold text-sm py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-[#e5b700] transition-colors">
                                Bayar Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Mobile: Floating sticky bar -->
    <div x-show="cart.length > 0 && !mobileCartOpen"
         x-transition:enter="transition duration-300 ease-out"
         x-transition:enter-start="translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition duration-200 ease-in"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-full opacity-0"
         class="lg:hidden fixed bottom-0 left-0 right-0 z-40 p-4 bg-white border-t border-gray-100 shadow-[0_-4px_20px_rgba(0,0,0,0.08)]"
         x-cloak>
        <button @click="mobileCartOpen = true"
            class="w-full bg-dark text-white font-bold py-3.5 rounded-xl flex items-center justify-between px-5 active:scale-95 transition-all">
            <div class="flex items-center gap-2">
                <span class="bg-primary text-dark text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center" x-text="totalItems"></span>
                <span class="text-sm">Lihat Pesanan</span>
            </div>
            <span class="font-heading text-primary font-bold text-lg" x-text="formatRupiah(totalPrice)"></span>
        </button>
    </div>

    <!-- Mobile: Bottom Sheet Overlay -->
    <div x-show="mobileCartOpen" class="lg:hidden fixed inset-0 z-50" x-cloak>
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40" @click="mobileCartOpen = false"
             x-transition:enter="transition duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>
        
        <!-- Sheet -->
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl flex flex-col max-h-[85vh]"
             x-transition:enter="transition duration-300 ease-out"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition duration-200 ease-in"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full">
            
            <!-- Handle -->
            <div class="flex justify-center pt-3 pb-2 shrink-0">
                <div class="w-10 h-1 bg-gray-200 rounded-full"></div>
            </div>
            
            <!-- Sheet header -->
            <div class="flex items-center justify-between px-5 pb-3 border-b border-gray-100 shrink-0">
                <span class="font-bold text-base text-dark flex items-center gap-2">
                    <i class="fa-solid fa-basket-shopping text-primary text-sm"></i> Pesananmu
                </span>
                <button @click="mobileCartOpen = false" class="w-7 h-7 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Empty state -->
            <div x-show="cart.length === 0" class="flex-1 flex flex-col items-center justify-center py-12 px-8 text-center">
                <div class="text-5xl mb-4 text-gray-300">
                    <i class="fa-solid fa-basket-shopping"></i>
                </div>
                <p class="font-semibold text-dark mb-1">Keranjang masih kosong</p>
                <p class="text-xs text-gray-400 mb-6">Yuk pilih menu dulu!</p>
                <button @click="mobileCartOpen = false" class="bg-primary text-dark font-bold text-sm px-6 py-2.5 rounded-full">Lihat Menu</button>
            </div>

            <!-- Cart items -->
            <div x-show="cart.length > 0" class="flex-1 overflow-y-auto cart-scroll divide-y divide-gray-50 px-5">
                <template x-for="cartItem in cart" :key="cartItem.id">
                    <div class="flex items-center gap-3 py-3">
                        <img :src="cartItem.image" class="w-14 h-14 rounded-xl object-cover shrink-0 bg-gray-100"
                             onerror="this.src='https://placehold.co/56x56/FFF8E7/F5C400?text=🍧'">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-dark truncate" x-text="cartItem.name"></p>
                            <p class="text-sm text-primary font-bold mt-0.5" x-text="formatRupiah(cartItem.price * cartItem.qty)"></p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button @click="removeCartItem(cartItem.id)" class="qty-btn !bg-red-50 !text-red-500 hover:!bg-red-100">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                            <input type="number" min="1" max="100" class="w-16 h-8 text-center border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary font-bold text-dark hide-arrows" x-model.number="cartItem.qty" @blur="if(!cartItem.qty || cartItem.qty < 1) removeCartItem(cartItem.id); else if(cartItem.qty > 100) { cartItem.qty = 100; alert('Maksimal pesanan per menu adalah 100 pcs. Silakan buat pesanan baru jika ingin memesan lebih banyak.'); }" @input="if($event.target.value > 100) { cartItem.qty = 100; alert('Maksimal pesanan per menu adalah 100 pcs. Silakan buat pesanan baru jika ingin memesan lebih banyak.'); }">
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- Checkout footer -->
            <div x-show="cart.length > 0" class="shrink-0 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-t-2xl">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-500">Total Belanja</span>
                    <span class="font-heading font-bold text-xl text-dark" x-text="formatRupiah(totalPrice)"></span>
                </div>
                
                <form action="{{ route('checkout') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="cart" :value="JSON.stringify(cart)">
                    <input type="hidden" name="total_price" :value="totalPrice">
                    
                    <div class="flex gap-2">
                        <input type="text" name="customer_name" required placeholder="Nama Anda" class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg" value="{{ auth()->user()->name ?? '' }}">
                        <input type="text" name="customer_phone" placeholder="No WhatsApp" class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg" value="{{ auth()->user()->phone ?? '' }}">
                    </div>

                    <button type="submit" class="w-full bg-[#F5C400] text-gray-900 font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 active:scale-95 transition-all text-sm">
                        Lanjut Bayar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderApp', () => ({
                mobileCartOpen: false,
                cart: [],
                get totalItems() {
                    return this.cart.reduce((t, i) => t + (Number(i.qty) || 0), 0);
                },
                get totalPrice() {
                    return this.cart.reduce((t, i) => t + i.price * (Number(i.qty) || 0), 0);
                },
                addToCart(item) {
                    const ex = this.cart.find(c => c.id === item.id);
                    if (ex) { 
                        if (ex.qty < 100) {
                            ex.qty++; 
                        } else {
                            alert('Maksimal pesanan per menu adalah 100 pcs. Silakan buat pesanan baru jika ingin memesan lebih banyak.');
                        }
                    } else { 
                        this.cart.push({ ...item, qty: 1 }); 
                    }
                    if (navigator.vibrate) navigator.vibrate(40);
                },
                increaseQty(id) {
                    const item = this.cart.find(c => c.id === id);
                    if (item) item.qty++;
                },
                decreaseQty(id) {
                    const idx = this.cart.findIndex(c => c.id === id);
                    if (idx === -1) return;
                    if (this.cart[idx].qty > 1) {
                        this.cart[idx].qty--;
                    } else {
                        this.cart.splice(idx, 1);
                        if (!this.cart.length) this.mobileCartOpen = false;
                    }
                },
                removeCartItem(id) {
                    const idx = this.cart.findIndex(c => c.id === id);
                    if (idx !== -1) {
                        this.cart.splice(idx, 1);
                        if (!this.cart.length) this.mobileCartOpen = false;
                    }
                },
                formatRupiah(n) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);
                },
                checkoutWhatsApp() {
                    if (!this.cart.length) return;
                    let msg = '*PESANAN - ES TELER GEMBIRA*%0A%0A';
                    this.cart.forEach((item, i) => {
                        msg += `${i + 1}. ${item.name} x${item.qty} = ${this.formatRupiah(item.price * item.qty)}%0A`;
                    });
                    msg += `%0A*TOTAL: ${this.formatRupiah(this.totalPrice)}*%0A%0ATerima kasih!`;
                    window.open(`https://wa.me/085840222334?text=${msg}`, '_blank');
                }
            }));
        });
    </script>
</body>
</html>
