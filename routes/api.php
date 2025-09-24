<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Social\AuthController;
use App\Http\Controllers\Default\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/watching-count', function (Request $request) {
    return response()->json([
        'count' => rand(15, 35)
    ]);
})->name('api.watching.count');
// API Routes
Route::get('/product-price/{id}', [ProductController::class, 'getPrice'])->name('api.product.price');

Route::get('/product-options/{id}', [ProductController::class, 'getOptions'])->name('api.product.options');

// Route::post('/mark-product-shown', function (Request $request) {
//     $productId = $request->input('product_id');

//     if ($productId) {
//         $shownProducts = session()->get('shown_products', []);
//         if (!in_array($productId, $shownProducts)) {
//             $shownProducts[] = $productId;
//             session()->put('shown_products', $shownProducts);
//         }
//     }

//     return response()->json(['status' => 'success']);
// });
Route::get('/reset-shown-products', function () {
    session()->forget('shown_products');
    return 'shown_products session cleared!';
});
