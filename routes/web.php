<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('shop', [ShopController::class, 'list'])->name('shop');
Route::get('shop/{slug}', [ShopController::class, 'show'])->name('shop.product');

Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
Route::delete('cart/lines/{cartLineId}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('cart/lines/{cartLineId}', [CartController::class, 'update'])->name('cart.update');

Route::get('commissions', function () {
    return Inertia::render('Commissions');
})->name('commissions');

Route::get('contact', function () {
    return Inertia::render('Contact');
})->name('contact');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
