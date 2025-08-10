<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
public function index($slug)
{
    $product = Product::with([
        'galleries',
        'specifications',
        'reviews.replies'
    ])->where('slug', $slug)->firstOrFail();

    // Get paginated reviews (10 per page)
    $reviews = $product->reviews()->with('replies')->paginate(10);

    // Get first gallery image if available
    $firstImage = $product->galleries->first()->image ?? null;

    // Get current session list
    $recentlyViewed = session()->get('recently_viewed', []);
$totalSold = DB::table('order_items')
    ->join('orders', 'order_items.order_id', '=', 'orders.id')
    ->where('order_items.product_id', $product->id)
    ->where('orders.status', 'completed')
    ->sum('order_items.quantity');

// If totalSold is null or less than 3, set it to 3
if ($totalSold < 3) {
    $totalSold = 3;
}

    // Remove this product if already exists
    $recentlyViewed = array_filter($recentlyViewed, function ($item) use ($product) {
        return $item['id'] !== $product->id;
    });

    // Add this product to the beginning
    array_unshift($recentlyViewed, [
        'id'    => $product->id,
        'slug'  => $product->slug,
        'name'  => $product->name,
        'image' => $firstImage, // first gallery image
    ]);

    // Limit to last 5
    $recentlyViewed = array_slice($recentlyViewed, 0, 5);

    // Save back to session
    session(['recently_viewed' => $recentlyViewed]);

    return view('front.default.products.product_details', compact('product', 'reviews', 'totalSold'));
}
// ProductController.php
public function search(Request $request)
{
    $query = $request->get('query');
    $categoryId = $request->get('category');

 $productsQuery = Product::with(['galleries' => fn($q) => $q->limit(1)])
    ->when($query, function($q) use ($query) {
        $q->where(function($inner) use ($query) {
            $inner->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('info', 'LIKE', "%{$query}%");
        });
    })
    ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
    ->select('id', 'name', 'slug', 'price', 'discount_price', 'category_id');

$products = $productsQuery->paginate(9);

$products->getCollection()->transform(function($product) {
    $product->image = $product->galleries->first()->image ?? null;
    $product->final_price = $product->discount_price ?? $product->price;
    unset($product->galleries); // optional to remove galleries
    return $product;
});

return response()->json($products);


}





public function reviews(Product $product)
{
    $reviews = $product->reviews()->with('replies')->paginate(10);
    return response()->json(['status' => true, 'reviews' => $reviews]);
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
