<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Policy;
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
class PolicyController extends Controller
{
public function show($slug)
{
    $policy = Policy::where('slug', $slug)->firstOrFail();

    // Extract all image URLs from HTML content
    preg_match_all('/<img[^>]+src="([^">]+)"/', $policy->content, $matches);
    $images = $matches[1]; // This will be an array of image URLs

    return view('front.default.policies.show', [
        'policy' => $policy,
        'images' => $images,
    ]);
}
public function wishlist(){
 $cartCount = 0;
    $wishlistCount = 0;
    
    if (Auth::check()) {
        $cart = Cart::where('user_id', Auth::id())->where('status', 'active')->first();
        $cartCount = $cart ? $cart->items()->count() : 0;
        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
    }
    
    return response()->json([
        'cartCount' => $cartCount,
        'wishlistCount' => $wishlistCount
    ]);
}
}
