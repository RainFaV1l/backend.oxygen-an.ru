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

Route::controller(\App\Http\Controllers\Api\V1\IndexController::class)->group(function () {
    Route::get('/products', 'products')->name('products');
    Route::get('/products/{product}', 'product')->name('product');
    Route::get('/categories', 'categories')->name('categories');
    Route::get('/categories/{category}', 'category')->name('category');
});
