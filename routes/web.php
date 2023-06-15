<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\CheckoutController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/stocks', StocksController::class);
Route::get('/cart', [StocksController::class, 'cartView'])->name('cart.view');
Route::get('/cart/add/{id}', [StocksController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/remove/{id}', [StocksController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart/clear', [StocksController::class, 'clearCart'])->name('cart.clear');


// Route::get('/stocks/{id}/checkout', [StocksController::class, 'checkoutForm'])-name('checkout.form');
// Route::post('/checkout', [StocksController::class, 'storeOrder'])->name('checkout.store');
Route::get('/checkout', [StocksController::class, 'checkout'])->name('checkout');
Route::post('/deploy-items', [StocksController::class, 'deployItems'])->name('deployItems');
Route::get('/deployed-items', [StocksController::class, 'showDeployedItems'])->name('deployedItems.index');
Route::get('/deployed-items/{id}/download-pdf', [StocksController::class, 'downloadPdf'])->name('deployedItems.downloadPdf');