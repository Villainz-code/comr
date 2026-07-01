<?php

use Illuminate\Support\Facades\Route;

// =====================
// GUEST ROUTES
// =====================
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Region API routes (used for dynamic dropdowns)
Route::get('/api/regencies/{province_id}', [App\Http\Controllers\RegionController::class, 'getRegencies']);
Route::get('/api/districts/{regency_id}', [App\Http\Controllers\RegionController::class, 'getDistricts']);


// =====================
// ADMIN ROUTES
// =====================
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('/products', App\Http\Controllers\Admin\ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);
    Route::resource('/categories', App\Http\Controllers\Admin\CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders');
    Route::put('/orders/{id}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update');
});

// =====================
// CUSTOMER ROUTES
// =====================
Route::prefix('user')->middleware(['auth', 'customer'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/shop', [App\Http\Controllers\User\ShopController::class, 'index'])->name('user.shop');
    Route::get('/shop/{product}', [App\Http\Controllers\User\ShopController::class, 'show'])->name('user.shop.show');
    Route::get('/order/create/{product}', [App\Http\Controllers\User\OrderController::class, 'create'])->name('user.order.create');
    Route::post('/order/store', [App\Http\Controllers\User\OrderController::class, 'store'])->name('user.order.store');
    Route::get('/orders', [App\Http\Controllers\User\OrderController::class, 'index'])->name('user.orders');
    Route::get('/orders/{order}/payment', [App\Http\Controllers\User\OrderController::class, 'payment'])->name('user.orders.payment');
    Route::get('/orders/{order}/edit', [App\Http\Controllers\User\OrderController::class, 'edit'])->name('user.order.edit');
    Route::put('/orders/{order}', [App\Http\Controllers\User\OrderController::class, 'update'])->name('user.order.update');
    Route::put('/orders/{order}/cancel', [App\Http\Controllers\User\OrderController::class, 'cancel'])->name('user.order.cancel');
    Route::get('/profile', [App\Http\Controllers\User\ProfileController::class, 'edit'])->name('user.profile');
    Route::put('/profile', [App\Http\Controllers\User\ProfileController::class, 'update'])->name('user.profile.update');
});
