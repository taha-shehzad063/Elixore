<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerImage;
use App\Models\CollectionBanner;
use App\Models\Cart;
use App\Models\Review;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Blog;
use App\Models\Tag;
use App\Models\GeneralSetting;
use App\Models\Wishlist;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class FrontendController extends Controller
{
  public function home() {
    $bestSellingProductIds = DB::table('order_items')
        ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->limit(6)
        ->pluck('product_id');
            $bestSellers = Product::whereIn('id', $bestSellingProductIds)->get();
 $reviews = Review::where('rating', '>=', 4)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
    $data = [
        'banners' => BannerImage::all(),
        'products' => Product::latest()->take(6)->get(),
      'bestSellers' => $bestSellers,
      'reviews' => $reviews,
        'testimonials' => Product::latest()->take(6)->get(),
        'collections' => CollectionBanner::first(),
        'setting' => GeneralSetting::first(),
'latestThreeBlogs' => Blog::latest()->take(3)->get(),
'mostPopularBlog' => Blog::withCount('comments')->orderByDesc('comments_count')->take(3)->get(),
    ];
    return view('front.default.homepage')->with($data);
}


public function cart(){
    return view('front.default.cart.cart');
}



public function wishlist()
{
    $wishlistItems = Wishlist::where('user_id', Auth::id())->with('product')->get();
    return view('front.default.wishlist', compact('wishlistItems'));
}
public function removeWishlist($id)
{
    $item = Wishlist::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
    $item->delete();
    return response()->json(['status' => true]);
}

public function addWishlist(Request $request)
{
    // dd($request->all());
     if (!Auth::check()) {
        // If request is AJAX (e.g., from frontend), return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'You need to login first.',
                'redirect' => route('user.login')
            ], 401);
        }

        // Otherwise, redirect to login page
        return redirect()->route('user.login');
    }
    $productId = $request->input('product_id');
    $userId = Auth::id();

    $exists = Wishlist::where('user_id', $userId)
                      ->where('product_id', $productId)
                      ->exists();

    if (!$exists) {
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return response()->json(['status' => true, 'message' => 'Product added to wishlist.']);
    }

    return response()->json(['status' => false, 'message' => 'Product is already in your wishlist.']);
}

}
