<?php

namespace App\Http\Controllers\Default\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index(Request $request)
{
    $productsQuery = Product::query();

    // Filter by Category
    if ($request->has('category') && $request->input('category')) {
        $productsQuery->where('category_id', $request->input('category'));
    }

    // Filter by Tags
    if ($request->has('tags') && is_array($request->input('tags'))) {
        $tagIds = $request->input('tags');
        if (!empty($tagIds)) {
            $productsQuery->whereHas('tags', function ($query) use ($tagIds) {
                $query->whereIn('tags.id', $tagIds);
            });
        }
    }

    // Filter by Price Range
    if ($request->has('min_price') && is_numeric($request->input('min_price'))) {
        $productsQuery->where('price', '>=', $request->input('min_price'));
    }
    if ($request->has('max_price') && is_numeric($request->input('max_price'))) {
        $productsQuery->where('price', '<=', $request->input('max_price'));
    }

    // Sorting
    if ($request->has('sort_by')) {
        switch ($request->input('sort_by')) {
            case 'price_asc':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'best_selling':
                $productsQuery->leftJoin(DB::raw('(SELECT product_id, SUM(quantity) as sales_count FROM order_items GROUP BY product_id) as oi'), 'products.id', '=', 'oi.product_id')
                    ->select('products.*', DB::raw('COALESCE(oi.sales_count,0) as sales_count'))
                    ->orderByDesc('sales_count');
                break;
            case 'a_z':
                $productsQuery->orderBy('name', 'asc');
                break;
            case 'z_a':
                $productsQuery->orderBy('name', 'desc');
                break;
            case 'old_to_new':
                $productsQuery->orderBy('created_at', 'asc');
                break;
            case 'new_to_old':
                $productsQuery->orderBy('created_at', 'desc');
                break;
            default:
                $productsQuery->orderBy('created_at', 'desc');
                break;
        }
    } else {
        $productsQuery->orderBy('created_at', 'desc');
    }

    // Per Page
    $perPage = $request->input('per_page', 12);
    if (!in_array($perPage, [12, 14, 16])) {
        $perPage = 12;
    }

    // For AJAX requests, return only the products partial
    if ($request->ajax()) {
        $products = $productsQuery->with('galleries')->paginate($perPage);
        return view('front.default.products.products', compact('products'))->render();
    }

    // For non-AJAX requests, return the full view
    $categories = Category::all();
    $tags = Tag::all();
    $minPrice = Product::min('price') ?? 0;
    $maxPrice = Product::max('price') ?? 1000;
    $products = $productsQuery->with('galleries')->paginate($perPage);

    return view('front.default.products.index', compact('products', 'categories', 'tags', 'minPrice', 'maxPrice'));
}

public function categoryProducts(Request $request, $name)
{
    // Convert "men-shoes" => "Men Shoes"
    $categoryName = ucwords(str_replace('-', ' ', $name));

    // Get category by name
    $category = Category::where('name', $categoryName)->firstOrFail();

    // Get filtered products for this category
    $productsQuery = Product::where('category_id', $category->id);

    // Optional: Filter by tag, price range, sort, pagination
    if ($request->has('tags') && is_array($request->input('tags'))) {
        $tagIds = $request->input('tags');
        $productsQuery->whereHas('tags', function ($query) use ($tagIds) {
            $query->whereIn('tags.id', $tagIds);
        });
    }

    if ($request->has('min_price')) {
        $productsQuery->where('price', '>=', $request->min_price);
    }

    if ($request->has('max_price')) {
        $productsQuery->where('price', '<=', $request->max_price);
    }

    if ($request->has('sort_by')) {
        switch ($request->input('sort_by')) {
            case 'price_asc':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'best_selling':
                $productsQuery->leftJoin(DB::raw('(SELECT product_id, SUM(quantity) as sales_count FROM order_items GROUP BY product_id) as oi'), 'products.id', '=', 'oi.product_id')
                    ->select('products.*', DB::raw('COALESCE(oi.sales_count,0) as sales_count'))
                    ->orderByDesc('sales_count');
                break;
            case 'a_z':
                $productsQuery->orderBy('name', 'asc');
                break;
            case 'z_a':
                $productsQuery->orderBy('name', 'desc');
                break;
            case 'old_to_new':
                $productsQuery->orderBy('created_at', 'asc');
                break;
            case 'new_to_old':
                $productsQuery->orderBy('created_at', 'desc');
                break;
            default:
                $productsQuery->orderBy('created_at', 'desc');
        }
    } else {
        $productsQuery->orderBy('created_at', 'desc');
    }

    $perPage = $request->input('per_page', 12);
    if (!in_array($perPage, [12, 14, 16])) {
        $perPage = 12;
    }

    $products = $productsQuery->with('galleries')->paginate($perPage);

    if ($request->ajax()) {
        return view('front.default.products.products', compact('products'))->render();
    }

    $tags = Tag::all();
    $minPrice = Product::min('price') ?? 0;
    $maxPrice = Product::max('price') ?? 1000;

    return view('front.default.products.index', compact('category', 'products', 'tags', 'minPrice', 'maxPrice'));
}

}