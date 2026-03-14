<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


// -----------------------------------------
// 🌐 Public (Guest) Routes
// -----------------------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');

// -----------------------------------------
// 🛒 Cart AJAX Routes (Authenticated Users)
// -----------------------------------------
Route::middleware(['auth'])->group(function () {
    Route::post('/cart/add', [CartController::class, 'addAjax'])->name('cart.add.ajax');
    Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');
    Route::patch('/cart/update', [CartController::class, 'updateAjax'])->name('cart.update.ajax');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/orders/count', [App\Http\Controllers\UserOrderController::class, 'getCount'])->name('orders.count');
});

// -----------------------------------------
// 🧠 Admin Dashboard Route (After Login)
// -----------------------------------------
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');

Route::get('/admin/recent-orders', [AdminController::class, 'recentOrders'])
    ->middleware(['auth', 'admin'])
    ->name('admin.recent.orders');

Route::get('/admin/recent-medicines', [AdminController::class, 'recentMedicines'])
    ->middleware(['auth', 'admin'])
    ->name('admin.recent.medicines');

// -----------------------------------------
// 🔀 General Dashboard Route (Role-based redirect)
// -----------------------------------------
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('home'); // Keep users on home page
    }
})->middleware(['auth'])->name('dashboard');

// -----------------------------------------
// 💊 Medicine CRUD Routes (Admin Protected)
// -----------------------------------------
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/medicines', [MedicineController::class, 'index'])->name('medicines.index');
    Route::get('/medicines/create', [MedicineController::class, 'create'])->name('medicines.create');
    Route::post('/medicines', [MedicineController::class, 'store'])->name('medicines.store');
    Route::get('/medicines/{medicine}/edit', [MedicineController::class, 'edit'])->name('medicines.edit');
    Route::put('/medicines/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');
    Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy');
    
    // 👥 User Management Routes (Admin Only)
    Route::get('/admin/users', [UserController::class, 'adminUsersList'])->name('admin.users');
    Route::get('/admin/users/{id}', [UserController::class, 'adminUserShow'])->name('admin.user.show');
    
    // 📦 Order Management Routes (Admin Only)
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/admin/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update.status');
    Route::patch('/admin/orders/{id}/payment', [AdminOrderController::class, 'updatePaymentStatus'])->name('admin.orders.update.payment');
    Route::patch('/admin/orders/{id}/refund', [AdminOrderController::class, 'updateRefundStatus'])->name('admin.orders.update.refund');
    Route::get('/admin/orders/status/{status}', [AdminOrderController::class, 'getOrdersByStatus'])->name('admin.orders.by.status');
    Route::get('/admin/orders/search', [AdminOrderController::class, 'search'])->name('admin.orders.search');
    Route::delete('/admin/orders/{id}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');
    
    // 🔔 Notification Routes (Admin Only)
    Route::get('/admin/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/admin/notifications/count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('admin.notifications.count');
    Route::get('/admin/notifications/latest', [App\Http\Controllers\NotificationController::class, 'getLatest'])->name('admin.notifications.latest');
    Route::patch('/admin/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::patch('/admin/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('admin.notifications.read-all');
    Route::delete('/admin/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'delete'])->name('admin.notifications.delete');
});

// -----------------------------------------
// 👤 User Profile Routes (Enhanced)
// -----------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/details', [ProfileController::class, 'updateProfile'])->name('profile.update.details');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Address Management
    Route::post('/profile/address', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
    Route::put('/profile/address/{address}', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
    Route::delete('/profile/address/{address}', [ProfileController::class, 'deleteAddress'])->name('profile.address.delete');
});

// ================= USER SIDE ROUTES =================

Route::middleware(['auth'])->group(function () {

    // 🛒 Cart Routes (User Only)
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('user.cart');
    Route::post('/cart/add/{medicine}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cart}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cart}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('user.checkout');
    
    // 📦 Order Routes (User Only)
    Route::post('/place-order', [App\Http\Controllers\CartController::class, 'placeOrder'])->name('user.place.order');
    Route::get('/order-success/{order}', [App\Http\Controllers\CartController::class, 'orderSuccess'])->name('user.order.success');
    
    // 📋 User Order Management Routes
    Route::get('/my-orders', [App\Http\Controllers\UserOrderController::class, 'index'])->name('user.orders.index');
    Route::get('/my-orders/{id}', [App\Http\Controllers\UserOrderController::class, 'show'])->name('user.orders.show');
    Route::get('/my-orders/{id}/track', [App\Http\Controllers\UserOrderController::class, 'track'])->name('user.orders.track');
    Route::patch('/my-orders/{id}/cancel', [App\Http\Controllers\UserOrderController::class, 'cancel'])->name('user.orders.cancel');
    Route::post('/my-orders/{id}/refund', [App\Http\Controllers\UserOrderController::class, 'requestRefund'])->name('user.orders.refund');
    Route::post('/my-orders/{id}/return', [App\Http\Controllers\UserOrderController::class, 'requestReturn'])->name('user.orders.return');
});

// Removed old shop route - now using home page for medicine shopping
Route::get('/shop/{id}', [UserController::class, 'show'])->name('user.show'); // single medicine page


Route::post('/stripe/payment', [CartController::class, 'stripePayment'])->name('stripe.payment');
Route::get('/stripe/success', [CartController::class, 'stripeSuccess'])->name('stripe.success');
Route::get('/stripe/cancel', [CartController::class, 'stripeCancel'])->name('stripe.cancel');
Route::post('/stripe/webhook', [App\Http\Controllers\StripeWebhookController::class, 'handleWebhook'])->name('stripe.webhook');
Route::get('/stripe/test', function() { return view('stripe-test'); })->name('stripe.test');



// -----------------------------------------
// 🧩 Auth (Login, Register, etc.)
// -----------------------------------------
require __DIR__.'/auth.php';
