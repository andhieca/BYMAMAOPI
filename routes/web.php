<?php

use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\CategoryController as AdminCategory;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\ProductController as AdminProduct;
use App\Http\Controllers\Admin\QuotaController as AdminQuota;
use App\Http\Controllers\Admin\SettingController as AdminSetting;
use App\Http\Controllers\OrderFlowController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Facing (Frontend) Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [OrderFlowController::class, 'index'])->name('home');
Route::get('/api/calendar-data', [OrderFlowController::class, 'getCalendarData'])->name('calendar.data');
Route::post('/order/create', [OrderFlowController::class, 'createOrder'])->name('order.create');
Route::post('/order/track', [OrderFlowController::class, 'trackOrder'])->name('order.track');
Route::get('/order/{code}/success', [OrderFlowController::class, 'success'])->name('order.success');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuth::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuth::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuth::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Protected Backoffice Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Orders Management
    Route::get('/orders', [AdminOrder::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrder::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [AdminOrder::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{id}/payment', [AdminOrder::class, 'updatePayment'])->name('orders.update-payment');
    Route::delete('/orders/{id}', [AdminOrder::class, 'destroy'])->name('orders.destroy');

    // Products Management
    Route::get('/products', [AdminProduct::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProduct::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProduct::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProduct::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminProduct::class, 'update'])->name('products.update');
    Route::post('/products/{id}/toggle', [AdminProduct::class, 'toggleAvailability'])->name('products.toggle');
    Route::delete('/products/{id}', [AdminProduct::class, 'destroy'])->name('products.destroy');

    // Categories Management
    Route::get('/categories', [AdminCategory::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [AdminCategory::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategory::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [AdminCategory::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [AdminCategory::class, 'update'])->name('categories.update');
    Route::post('/categories/{id}/toggle', [AdminCategory::class, 'toggleStatus'])->name('categories.toggle');
    Route::delete('/categories/{id}', [AdminCategory::class, 'destroy'])->name('categories.destroy');

    // Quota & PO Calendar Management
    Route::get('/quotas', [AdminQuota::class, 'index'])->name('quotas.index');
    Route::post('/quotas/date', [AdminQuota::class, 'updateDate'])->name('quotas.update-date');
    Route::post('/quotas/bulk', [AdminQuota::class, 'bulkClose'])->name('quotas.bulk');

    // Store Settings
    Route::get('/settings', [AdminSetting::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSetting::class, 'update'])->name('settings.update');
});
