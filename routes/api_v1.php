<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::get('/products', [\App\Http\Controllers\Api\V1\IndexController::class, 'products'])->name('products');
//Route::get('/products/{product}', [\App\Http\Controllers\Api\V1\IndexController::class, 'product'])->name('product');

Route::controller(\App\Http\Controllers\Api\V1\IndexController::class)->middleware('api')->group(function () {
    Route::get('/products', 'products')->name('products');
    Route::get('/products/{product}/see/also', 'popularProducts')->name('popularProducts');
    Route::get('/products/{product}', 'product')->name('product');
    Route::get('/categories', 'categories')->name('categories');
    Route::get('/categories/{category}', 'category')->name('category');
    Route::post('/subscribe', 'subscribe')->name('subscribe');
});

Route::controller(\App\Http\Controllers\Api\V1\AuthController::class)->middleware('api')->prefix('auth')->group(function () {
    Route::post('/login', 'login');
    Route::post('/user', 'user');
    Route::post('/logout', 'logout');
    Route::post('/refresh', 'refresh');
});

Route::controller(\App\Http\Controllers\Api\V1\OrderController::class)->middleware('api')->group(function () {
    Route::post('guest/checkout', 'guestCheckout');
    Route::post('auth/checkout', 'authCheckout');
    Route::get('orders', 'orders');
});

Route::controller(\App\Http\Controllers\Api\V1\CartController::class)->middleware('api')->prefix('cart')->group(function () {
    Route::get('/products', 'getCartProducts');
    Route::post('{id}/status/cancelling', 'cancelling');
    Route::patch('/update', 'updateCart');
    Route::post('/products', 'addToCart');
    Route::delete('/products/{id}', 'deleteToCart');
    Route::delete('/clear', 'clearCart');
});
