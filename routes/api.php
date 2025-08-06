<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Social\AuthController;
use App\Http\Controllers\Default\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/watching-count', function(Request $request){
    return response()->json([
        'count' => rand(15, 35)
    ]);
});
// API Routes
Route::get('/product-price/{id}', [ProductController::class, 'getPrice'])->name('api.product.price');

Route::get('/product-options/{id}', [ProductController::class, 'getOptions'])->name('api.product.options');
