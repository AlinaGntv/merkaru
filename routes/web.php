<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ContactController,
    CatalogController,
    DashboardController,
    SizeController,
    AddressController,
    OrderController,
    AdminController,
    FeedbackController,
};

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

Route::controller(CatalogController::class)->group(function () {
    Route::get('/catalog', 'index')->name('catalog');
    Route::get('/catalog/order/{product}', 'order')->name('product.order');
});

Route::controller(ContactController::class)->group(function () {
    Route::get('/contacts', 'index')->name('contacts');
    Route::post('/contacts', 'send')->name('contacts.send');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'user'])
        ->name('dashboard');
    
    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');
    
    Route::prefix('api')->group(function () {
        Route::post('/sizes', [SizeController::class, 'store'])->name('sizes.store');
        Route::post('/address', [AddressController::class, 'store'])->name('address.store');
        Route::post('/orders', [OrderController::class, 'create'])->name('orders.create');
    });
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/sizes', [AdminController::class, 'sizes'])->name('admin.sizes');
    Route::get('/messages', [AdminController::class, 'messages'])->name('admin.messages');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('admin.orders.show');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.updateStatus');
    Route::get('/feedback', [AdminController::class, 'feedback'])->name('admin.feedback');
    Route::get('/feedback/{message}', [AdminController::class, 'showFeedback'])->name('admin.feedback.show');
    Route::delete('/feedback/{message}', [AdminController::class, 'deleteFeedback'])->name('admin.feedback.delete');
});

Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
