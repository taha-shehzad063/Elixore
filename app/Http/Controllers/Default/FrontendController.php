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
use Illuminate\Support\Facades\Session;

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

    // Best Selling Products (based on orders)
    $bestSellingProductIds = DB::table('order_items')
        ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->pluck('product_id');

    $bestSellers = Product::whereIn('id', $bestSellingProductIds)->get();

    // Most Popular Products (based on reviews)
    $mostPopularProductIds = Review::select('product_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(*) as total_reviews'))
        ->groupBy('product_id')
        ->having('avg_rating', '>=', 4) // only products with good ratings
        ->orderByDesc('total_reviews')
        ->orderByDesc('avg_rating')
        ->limit(6)
        ->pluck('product_id');

    $mostPopular = Product::whereIn('id', $mostPopularProductIds)->get();

    // Latest good reviews
    $reviews = Review::where('rating', '>=', 4)
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();

    $data = [
        'banners' => BannerImage::all(),
        'products' => Product::latest()->take(6)->get(),
        'bestSellers' => $bestSellers, // best selling list
        'mostpopular' => $mostPopular, // most popular list
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
        // Fetch wishlist items based on session_id or user_id if authenticated
        $wishlistItems = Wishlist::where(function ($q) {
            $q->where('session_id', Session::getId());
            if (Auth::check()) {
                $q->orWhere('user_id', Auth::id());
            }
        })->with('product')->get();

        return view('front.default.wishlist', compact('wishlistItems'));
    }

    public function removeWishlist($id)
    {
        // Find wishlist item by ID and session_id or user_id
        $item = Wishlist::where('id', $id)
            ->where(function ($q) {
                $q->where('session_id', Session::getId());
                if (Auth::check()) {
                    $q->orWhere('user_id', Auth::id());
                }
            })->firstOrFail();

        $item->delete();

        return response()->json(['status' => true]);
    }

    public function addWishlist(Request $request)
    {
        // Validate request
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $productId = $request->input('product_id');

        // Check if product already exists in wishlist
        $exists = Wishlist::where('product_id', $productId)
            ->where(function ($q) {
                $q->where('session_id', Session::getId());
                if (Auth::check()) {
                    $q->orWhere('user_id', Auth::id());
                }
            })->exists();

        if ($exists) {
            return response()->json(['status' => false, 'message' => 'Product is already in your wishlist.']);
        }

        // Create wishlist item with session_id, include user_id if authenticated
        Wishlist::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'session_id' => Session::getId(),
            'product_id' => $productId,
        ]);

        return response()->json(['status' => true, 'message' => 'Product added to wishlist.']);
    }

}
