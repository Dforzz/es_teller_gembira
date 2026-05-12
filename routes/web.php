<?php

use App\Http\Controllers\AuthController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuController;
use App\Models\Menu;
use App\Models\Order;

use App\Http\Controllers\OrderController;

Route::get('/', function () {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect('/admin/dashboard');
    }
    $menus = Menu::where('is_available', true)->get();
    return view('welcome', compact('menus'));
});

Route::get('/pesan', function () {
    $menus = Menu::where('is_available', true)->get();
    return view('order', compact('menus'));
});

Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Dashboard
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [OrderController::class, 'index'])->name('user.dashboard');
    
    Route::post('/order/{id}/complete', [OrderController::class, 'completeOrder'])->name('order.complete');
    Route::delete('/order/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
});

// Tracking route can be accessed by guest if they have the ID
Route::get('/tracking/{id}', [OrderController::class, 'tracking'])->name('order.tracking');
Route::match(['get', 'post'], '/order/{id}/check-payment', [OrderController::class, 'checkPayment'])->name('order.check_payment');

Route::post('/midtrans-webhook', [OrderController::class, 'webhook']);

// Admin Dashboard
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/order/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.order.status');
    Route::delete('/order/{id}', [AdminController::class, 'destroy'])->name('admin.order.destroy');
    Route::get('/order-statuses', [AdminController::class, 'orderStatuses'])->name('admin.order.statuses');
    
    Route::resource('menus', MenuController::class)->except(['show']);
});
