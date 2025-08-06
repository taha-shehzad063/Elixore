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

        // Validate inputs
        $request->validate([
            'category' => 'nullable|numeric',
            'tags' => 'nullable|array',
            'tags.*' => 'numeric',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'sort_by' => 'nullable|string|in:price_asc,price_desc,best_selling,a_z,z_a,old_to_new,new_to_old',
            'per_page' => 'nullable|integer|in:12,24,36'
        ]);

        // Get selected category from request (nullable)
        $selectedCategoryId = $request->input('category');

        // Filter products by category if selected
        if ($selectedCategoryId) {
            $productsQuery->where('category_id', $selectedCategoryId);
        }

        // Filter products by tags if provided
        if ($request->has('tags') && is_array($request->input('tags'))) {
            $tagIds = array_filter($request->input('tags'), 'is_numeric');
            if (!empty($tagIds)) {
                $productsQuery->whereHas('tags', function ($query) use ($tagIds) {
                    $query->whereIn('tags.id', $tagIds);
                });
            }
        }

        // Price Range filters
        if ($request->has('min_price') && is_numeric($request->input('min_price'))) {
            $productsQuery->where('price', '>=', $request->input('min_price'));
        }
        if ($request->has('max_price') && is_numeric($request->input('max_price'))) {
            $productsQuery->where('price', '<=', $request->input('max_price'));
        }

        // Sorting logic
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
                        ->select('products.*', DB::raw('COALESCE(oi.sales_count, 0) as sales_count'))
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

        // Per Page handling
        $perPage = $request->input('per_page', 12);
        if (!in_array($perPage, [12, 24, 36])) {
            $perPage = 12;
        }

        // Load tags based on category selection
        $tagsQuery = $selectedCategoryId
            ? Tag::where('category_id', $selectedCategoryId)
            : Tag::query();
        $tags = $tagsQuery->paginate(8);

        // Load categories and price range
        $categories = Category::select('id', 'name')->get();
        $minPrice = Product::min('price') ?? 0;
        $maxPrice = Product::max('price') ?? 1000;
        $products = $productsQuery->with('galleries')->paginate($perPage);

        // For filter AJAX requests
        if ($request->ajax() && $request->has('filter')) {
            return response()->json([
                'html' => view('front.default.products.products', compact('products'))->render(),
                'tags' => view('front.default.products.partials.tags_list', compact('tags'))->render(),
                'pagination' => $tags->links()->render()
            ]);
        }

        // For AJAX page load requests
        if ($request->ajax()) {
            return response()->json([
                'html' => view('front.default.products.products', compact('products'))->render(),
                'tags' => view('front.default.products.partials.tags_list', compact('tags'))->render(),
                'pagination' => $tags->links()->render()
            ]);
        }

        // For normal requests
        return view('front.default.products.index', compact(
            'products',
            'categories',
            'tags',
            'minPrice',
            'maxPrice',
            'selectedCategoryId'
        ));
    }

    public function categoryProducts(Request $request, $name)
    {
        $request->validate([
            'tags' => 'nullable|array',
            'tags.*' => 'numeric',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'sort_by' => 'nullable|string|in:price_asc,price_desc,best_selling,a_z,z_a,old_to_new,new_to_old',
            'per_page' => 'nullable|integer|in:12,24,36'
        ]);

        // Convert "men-shoes" => "Men Shoes"
        $categoryName = ucwords(str_replace('-', ' ', $name));

        // Get category by name
        $category = Category::where('name', $categoryName)->firstOrFail();

        // Get filtered products for this category
        $productsQuery = Product::where('category_id', $category->id);

        // Filter by Tags
        if ($request->has('tags') && is_array($request->input('tags'))) {
            $tagIds = array_filter($request->input('tags'), 'is_numeric');
            if (!empty($tagIds)) {
                $productsQuery->whereHas('tags', function ($query) use ($tagIds) {
                    $query->whereIn('tags.id', $tagIds);
                });
            }
        }

        // Price Range filters
        if ($request->has('min_price') && is_numeric($request->input('min_price'))) {
            $productsQuery->where('price', '>=', $request->input('min_price'));
        }
        if ($request->has('max_price') && is_numeric($request->input('max_price'))) {
            $productsQuery->where('price', '<=', $request->input('max_price'));
        }

        // Sorting logic
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
                        ->select('products.*', DB::raw('COALESCE(oi.sales_count, 0) as sales_count'))
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

        // Per Page handling
        $perPage = $request->input('per_page', 12);
        if (!in_array($perPage, [12, 24, 36])) {
            $perPage = 12;
        }

        // Load tags for this category
        $tags = Tag::where('category_id', $category->id)->paginate(8);

        $products = $productsQuery->with('galleries')->paginate($perPage);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('front.default.products.products', compact('products'))->render(),
                'tags' => view('front.default.products.partials.tags_list', compact('tags'))->render(),
                'pagination' => $tags->links()->render()
            ]);
        }

        $minPrice = Product::min('price') ?? 0;
        $maxPrice = Product::max('price') ?? 1000;
        $selectedCategoryId = $category->id;

        return view('front.default.products.index', compact(
            'category',
            'products',
            'tags',
            'minPrice',
            'maxPrice',
            'selectedCategoryId'
        ));
    }

    public function tagsByCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|numeric'
        ]);

        $categoryId = $request->input('category_id');
        $tagsQuery = $categoryId
            ? Tag::where('category_id', $categoryId)
            : Tag::query();
        $tags = $tagsQuery->paginate(8);

        return response()->json([
            'tags' => view('front.default.products.partials.tags_list', compact('tags'))->render(),
            'pagination' => $tags->links()->render()
        ]);
    }
}
