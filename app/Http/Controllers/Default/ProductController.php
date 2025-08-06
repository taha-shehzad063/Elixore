<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
public function index($slug)
{
    $product = Product::with([
        'galleries',
        'specifications',
        'reviews.replies' // Add this line to load reviews and their replies
    ])->where('slug', $slug)->firstOrFail();

    return view('front.default.products.product_details', compact('product'));
}

public function getOptions($id)
{
    $product = Product::with('options')->findOrFail($id);
    
    return response()->json([
        'status' => true,
        'options' => $product->options
    ]);
}

public function getPrice($id)
{
    $product = Product::findOrFail($id);
    
    return response()->json([
        'status' => true,
        'price' => $product->price
    ]);
}

}
