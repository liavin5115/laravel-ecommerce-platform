<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StoreOnboardingController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\OrganizationController as SuperAdminOrganizationController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CouponController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

// ── Storefront ──────────────────────────────────────────
Route::get('/', function () {
    $products = Product::with(['store', 'images'])
        ->where('is_active', true)
        ->latest()
        ->simplePaginate(8);
    return view('welcome', compact('products'));
});

Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('public.products.show');
Route::get('/marketplace', [ProductController::class, 'index'])->name('public.products.index');

// ── Store Onboarding ──────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/open-store', [StoreOnboardingController::class, 'index'])->name('stores.onboarding');
    Route::post('/open-store', [StoreOnboardingController::class, 'store'])->name('stores.onboarding.store');
});

// ── Super Admin (Platform Owner) ────────────────────────
Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    
    // Organizations
    Route::get('/organizations', [SuperAdminOrganizationController::class, 'index'])->name('organizations.index');
    Route::patch('/organizations/{organization}/toggle', [SuperAdminOrganizationController::class, 'toggleStatus'])->name('organizations.toggle-status');
    Route::patch('/organizations/{organization}/plan', [SuperAdminOrganizationController::class, 'updatePlan'])->name('organizations.update-plan');
});

// ── Cart ────────────────────────────────────────────────
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{key}', [CartController::class, 'remove'])->name('cart.remove');

// ── Checkout ────────────────────────────────────────────
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// ── Dashboard ───────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/products', [DashboardController::class, 'products'])->name('dashboard.products');
    Route::get('/dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');
    Route::get('/dashboard/customers', [DashboardController::class, 'customers'])->name('dashboard.customers');
    Route::get('/dashboard/coupons', [CouponController::class, 'index'])->name('dashboard.coupons');
    Route::get('/dashboard/stores', [App\Http\Controllers\Admin\StoreController::class, 'index'])->name('dashboard.stores');
    Route::get('/dashboard/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('dashboard.categories');
    Route::get('/dashboard/tickets', [DashboardController::class, 'tickets'])->name('dashboard.tickets');
    Route::get('/dashboard/tickets/{ticket}', [DashboardController::class, 'ticketShow'])->name('dashboard.tickets.show');
    Route::post('/dashboard/tickets/{ticket}/reply', [DashboardController::class, 'ticketReply'])->name('dashboard.tickets.reply');
});

// ── Admin CRUD ──────────────────────────────────────────
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Products CRUD
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Orders
    Route::get('/orders/{order}', [DashboardController::class, 'orderShow'])->name('orders.show');
    Route::patch('/orders/{order}/status', [DashboardController::class, 'orderUpdateStatus'])->name('orders.updateStatus');

    // Stores CRUD
    Route::get('/stores/create', [App\Http\Controllers\Admin\StoreController::class, 'create'])->name('stores.create');
    Route::post('/stores', [App\Http\Controllers\Admin\StoreController::class, 'store'])->name('stores.store');
    Route::get('/stores/{store}/edit', [App\Http\Controllers\Admin\StoreController::class, 'edit'])->name('stores.edit');
    Route::put('/stores/{store}', [App\Http\Controllers\Admin\StoreController::class, 'update'])->name('stores.update');
    Route::delete('/stores/{store}', [App\Http\Controllers\Admin\StoreController::class, 'destroy'])->name('stores.destroy');

    // Categories CRUD
    Route::get('/categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

    // Coupons CRUD
    Route::get('/coupons/create', [CouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');
});

// ── Auth / Profile ──────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
