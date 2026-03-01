<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('shop', [ShopController::class, 'list'])->name('shop');
Route::get('shop/{slug}', [ShopController::class, 'show'])->name('shop.product');

Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
Route::delete('cart/lines/{cartLineId}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('cart/lines/{cartLineId}', [CartController::class, 'update'])->name('cart.update');

Route::get('checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('checkout/address', [CheckoutController::class, 'saveAddress'])->name('checkout.address');
Route::post('checkout/session', [CheckoutController::class, 'createSession'])->name('checkout.session');
Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
Route::post('stripe/webhook', [CheckoutController::class, 'webhook'])->name('stripe.webhook');

Route::get('commissions', function () {
    return Inertia::render('Commissions');
})->name('commissions');

Route::get('contact', [ContactController::class, 'show'])->name('contact');
Route::post('contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
