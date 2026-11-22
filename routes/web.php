<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Cafe Self-Order Application
|--------------------------------------------------------------------------
*/

// Public Customer Self-Order Flow (Guest Checkout, No Login Required)
Route::get('/', function () {
    return redirect()->route('customer.catalog');
});

Route::get('/order', [CustomerOrderController::class, 'index'])->name('customer.catalog');
Route::post('/cart/add', [CustomerOrderController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CustomerOrderController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/clear', [CustomerOrderController::class, 'clearCart'])->name('cart.clear');
Route::get('/order/checkout', [CustomerOrderController::class, 'checkout'])->name('customer.checkout');
Route::post('/order/submit', [CustomerOrderController::class, 'storeOrder'])->name('customer.storeOrder');
Route::get('/order/track/{orderCode}', [CustomerOrderController::class, 'track'])->name('customer.track');
Route::get('/api/order/status/{orderCode}', [CustomerOrderController::class, 'checkStatus'])->name('api.order.status');
Route::post('/order/simulate-pay/{orderCode}', [CustomerOrderController::class, 'simulateCustomerPay'])->name('customer.simulatePay');
Route::post('/order/cancel/{orderCode}', [CustomerOrderController::class, 'cancelOrder'])->name('customer.cancelOrder');

// Authentication Routes for Staff (Kasir & Manager)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Cashier Operations Console (Protected: Kasir & Manager)
Route::prefix('cashier')->name('cashier.')->middleware(['auth', 'role:cashier,manager'])->group(function () {
    Route::get('/', [CashierController::class, 'index'])->name('index');
    Route::get('/order/create', [CashierController::class, 'createOrder'])->name('order.create');
    Route::post('/order/store', [CashierController::class, 'storeDirectOrder'])->name('order.store');
    Route::post('/order/{id}/approve', [CashierController::class, 'approvePayment'])->name('approvePayment');
    Route::post('/order/{id}/status', [CashierController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/order/{id}/reject', [CashierController::class, 'rejectOrder'])->name('rejectOrder');
    Route::get('/poll', [CashierController::class, 'pollPending'])->name('pollPending');
    Route::get('/scan', [CashierController::class, 'scanPage'])->name('scan');
    Route::get('/scan/lookup', [CashierController::class, 'scanLookup'])->name('scan.lookup');
});

// Manager Superadmin Control (Protected: Strictly Manager Only)
Route::prefix('manager')->name('manager.')->middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/', [ManagerController::class, 'dashboard'])->name('dashboard');
    Route::post('/traffic/toggle', [ManagerController::class, 'toggleTraffic'])->name('toggleTraffic');

    // Menu Master Data
    Route::get('/menus', [ManagerController::class, 'menuIndex'])->name('menu.index');
    Route::get('/menus/create', [ManagerController::class, 'menuCreate'])->name('menu.create');
    Route::post('/menus', [ManagerController::class, 'menuStore'])->name('menu.store');
    Route::get('/menus/{id}/edit', [ManagerController::class, 'menuEdit'])->name('menu.edit');
    Route::put('/menus/{id}', [ManagerController::class, 'menuUpdate'])->name('menu.update');
    Route::post('/menus/{id}/toggle-stock', [ManagerController::class, 'toggleMenuStock'])->name('menu.toggleStock');
    Route::delete('/menus/{id}', [ManagerController::class, 'menuDestroy'])->name('menu.destroy');

    // Categories
    Route::get('/categories', [ManagerController::class, 'categoriesIndex'])->name('categories.index');
    Route::post('/categories', [ManagerController::class, 'categoryStore'])->name('categories.store');
    Route::delete('/categories/{id}', [ManagerController::class, 'categoryDestroy'])->name('categories.destroy');

    // Tables & QR Code Generator
    Route::get('/tables', [ManagerController::class, 'tablesIndex'])->name('tables.index');
    Route::post('/tables', [ManagerController::class, 'tableStore'])->name('tables.store');
    Route::post('/tables/mass-generate', [ManagerController::class, 'massGenerateTables'])->name('tables.massGenerate');
    Route::get('/tables/print-qrs', [ManagerController::class, 'printQRs'])->name('tables.printQRs');

    // Financial Reports
    Route::get('/reports', [ManagerController::class, 'reports'])->name('reports.index');
    // Manager monitor cashier with manager header
    Route::get('/cashier/monitor', [ManagerController::class, 'monitorCashier'])->name('cashier.monitor');

    // Cancel pending order before checkout (clears cart)
    Route::post('/order/cancel-pending', [CustomerOrderController::class, 'cancelPending'])->name('customer.cancelPending');

    // Receipt view after payment
    Route::get('/order/receipt/{orderCode}', [CustomerOrderController::class, 'receipt'])->name('customer.receipt');

    
});
