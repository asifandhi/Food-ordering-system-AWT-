<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\BrowseController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Hotelier\DashboardController;
use App\Http\Controllers\Hotelier\MenuController;
use App\Http\Controllers\Hotelier\OrderManageController;
use App\Http\Controllers\Hotelier\ProfileController as HotelierProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\HotelierController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Customer\ReviewController;



use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminHotelierController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminRevenueController;

use App\Http\Controllers\Admin\AdminReviewController;



// ── Landing Page ───────────────────────────────────────────
Route::get('/', function () {
    return view('landing');
})->name('landing');

// ══════════════════════════════════════════════════════════
// CUSTOMER AUTH ROUTES
// ══════════════════════════════════════════════════════════
Route::get('/customer/login', [LoginController::class, 'showCustomerLogin'])->name('login.customer');
Route::post('/customer/login', [LoginController::class, 'customerLogin'])->name('login.customer.post');
Route::get('/customer/register', [RegisterController::class, 'showCustomerRegister'])->name('register.customer');
Route::post('/customer/register', [RegisterController::class, 'customerRegister'])->name('register.customer.post');

// ══════════════════════════════════════════════════════════
// HOTELIER AUTH ROUTES
// ══════════════════════════════════════════════════════════
Route::get('/hotelier/login', [LoginController::class, 'showHotelierLogin'])->name('login.hotelier');
Route::post('/hotelier/login', [LoginController::class, 'hotelierLogin'])->name('login.hotelier.post');
Route::get('/hotelier/register', [RegisterController::class, 'showHotelierRegister'])->name('register.hotelier');
Route::post('/hotelier/register', [RegisterController::class, 'hotelierRegister'])->name('register.hotelier.post');

// ── Logout (shared) ────────────────────────────────────────
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ══════════════════════════════════════════════════════════
// CUSTOMER PROTECTED ROUTES
// ══════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        // Browse
        Route::get('/browse', [BrowseController::class, 'index'])->name('browse');
        Route::get('/restaurant/{id}', [BrowseController::class, 'restaurant'])->name('restaurant');

        // Cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

        // Checkout
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/order/place', [OrderController::class, 'place'])->name('order.place');

        // Orders
        Route::get('/orders', [OrderController::class, 'index'])->name('orders');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

        // Reviews
        Route::get('/review/{order_id}/create', [ReviewController::class, 'create'])->name('review.create');
        Route::post('/review/{order_id}/store', [ReviewController::class, 'store'])->name('review.store');

        // Profile & Addresses
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/address/store', [ProfileController::class, 'storeAddress'])->name('address.store');
        Route::post('/address/delete/{id}', [ProfileController::class, 'deleteAddress'])->name('address.delete');
        Route::post('/address/default/{id}', [ProfileController::class, 'setDefault'])->name('address.default');
    });

// ══════════════════════════════════════════════════════════
// HOTELIER PROTECTED ROUTES
// ══════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:hotelier', 'hotelier.approved'])
    ->prefix('hotelier')
    ->name('hotelier.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [HotelierProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update', [HotelierProfileController::class, 'update'])->name('profile.update');
        Route::post('/toggle-open', [HotelierProfileController::class, 'toggleOpen'])->name('toggle.open');

        // Delivery Slabs
        Route::post('/slabs/store', [HotelierProfileController::class, 'storeSlabs'])->name('slabs.store');

        // Categories
        Route::get('/menu', [MenuController::class, 'index'])->name('menu');
        Route::post('/category/store', [MenuController::class, 'storeCategory'])->name('category.store');
        Route::post('/category/update/{id}', [MenuController::class, 'updateCategory'])->name('category.update');
        Route::post('/category/delete/{id}', [MenuController::class, 'deleteCategory'])->name('category.delete');

        // Food Items
        Route::post('/item/store', [MenuController::class, 'storeItem'])->name('item.store');
        Route::post('/item/update/{id}', [MenuController::class, 'updateItem'])->name('item.update');
        Route::post('/item/delete/{id}', [MenuController::class, 'deleteItem'])->name('item.delete');
        Route::post('/item/toggle/{id}', [MenuController::class, 'toggleItem'])->name('item.toggle');

        // Orders
        Route::get('/orders', [OrderManageController::class, 'index'])->name('orders');
        Route::get('/orders/{id}', [OrderManageController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/status', [OrderManageController::class, 'updateStatus'])->name('orders.status');
    });

// ══════════════════════════════════════════════════════════
// ADMIN PROTECTED ROUTES
// ══════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/hoteliers', [HotelierController::class, 'index'])->name('hoteliers');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
});

// ==================== ADMIN ROUTES ====================

// Admin Auth (no middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Hoteliers
    Route::get('/hoteliers', [AdminHotelierController::class, 'index'])->name('hoteliers.index');
    Route::post('/hoteliers/{id}/approve', [AdminHotelierController::class, 'approve'])->name('hoteliers.approve');
    Route::post('/hoteliers/{id}/reject', [AdminHotelierController::class, 'reject'])->name('hoteliers.reject');
    Route::delete('/hoteliers/{id}', [AdminHotelierController::class, 'destroy'])->name('hoteliers.destroy');

    // Users (Customers)
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');

    // Revenue
    Route::get('/revenue', [AdminRevenueController::class, 'index'])->name('revenue.index');

    // Reviews
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
});