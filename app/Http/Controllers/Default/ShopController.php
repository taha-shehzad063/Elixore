<?php

namespace App\Http\Controllers\Default\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class ShopController extends Controller
{

    public function index(Request $request)
{
    $categories = Category::all();
    $minPrice = Product::min('price') ?? 0;
    $maxPrice = Product::max('price') ?? 1000;
    $tags = Tag::whereNotNull('sub_category_id')->get();
    $subcategories = collect(); // Empty for shop route unless category is selected
    Log::info('Index: Fetching tags for no-filter case', [
        'tags_count' => $tags->count(),
        'tags' => $tags->pluck('name')->toArray()
    ]);

    $products = $this->filterProducts($request);

    if ($request->ajax()) {
        return [
            'html' => view('front.default.products.products', compact('products'))->render(),
            'tags' => view('front.default.products.partials.tags_list', compact('tags'))->render(),
        ];
    }

    return view('front.default.products.index', compact('categories', 'products', 'minPrice', 'maxPrice', 'tags', 'subcategories'));
}

public function category($category, Request $request)
{
    $category = urldecode($category);
    $categoryName = ucwords(str_replace('-', ' ', $category));
    $categoryModel = Category::whereRaw('LOWER(name) = ?', [strtolower($categoryName)])->first();

    if (!$categoryModel) {
        Log::warning('Category not found', ['category_name' => $categoryName]);
        return redirect()->route('shop.index')->with('error', 'Category "' . $categoryName . '" not found.');
    }

    $selectedCategoryId = $categoryModel->id;
    $categories = Category::all();
    $minPrice = Product::min('price') ?? 0;
    $maxPrice = Product::max('price') ?? 1000;
    $subcategories = SubCategory::where('category_id', $selectedCategoryId)->get();
    $subcategoryIds = $subcategories->pluck('id');
    $tags = Tag::whereIn('sub_category_id', $subcategoryIds)->get();
    Log::info('Category: Fetching tags for category', [
        'category_id' => $selectedCategoryId,
        'subcategory_ids' => $subcategoryIds->toArray(),
        'tags_count' => $tags->count(),
        'tags' => $tags->pluck('name')->toArray()
    ]);

    $products = $this->filterProducts($request, $selectedCategoryId);

    if ($request->ajax()) {
        return [
            'html' => view('front.default.products.products', compact('products'))->render(),
            'tags' => view('front.default.products.partials.tags_list', compact('tags'))->render(),
        ];
    }

    return view('front.default.products.index', compact('categories', 'products', 'minPrice', 'maxPrice', 'tags', 'selectedCategoryId', 'subcategories'));
}

public function getSubCategoriesByCategory(Request $request)
{
    $categoryId = $request->query('category_id');
    $subcategories = SubCategory::where('category_id', $categoryId)->get();
    Log::info('getSubCategoriesByCategory: Fetching subcategories', [
        'category_id' => $categoryId,
        'subcategories_count' => $subcategories->count(),
        'subcategories' => $subcategories->pluck('name')->toArray()
    ]);

    return [
        'html' => view('front.default.products.partials.subcategory_list', compact('subcategories'))->render()
    ];
}

public function getTagsByCategoryOrSubcategory(Request $request)
{
    $subcategoryId = $request->query('subcategory_id');
    $categoryId = $request->query('category_id');
    $noFilter = $request->query('no_filter');

    Log::info('getTagsByCategoryOrSubcategory called', [
        'subcategory_id' => $subcategoryId,
        'category_id' => $categoryId,
        'no_filter' => $noFilter,
        'request_query' => $request->query()
    ]);

    $tags = collect();

    if ($subcategoryId) {
        // Validate subcategory exists and belongs to the provided category
        $subcategory = SubCategory::where('id', $subcategoryId)
            ->when($categoryId, fn($query) => $query->where('category_id', $categoryId))
            ->first();

        if ($subcategory) {
            $tags = Tag::where('sub_category_id', $subcategoryId)->get();
            Log::info('Tags fetched for subcategory', [
                'subcategory_id' => $subcategoryId,
                'category_id' => $categoryId,
                'tags' => $tags->pluck('name')->toArray(),
                'tag_count' => $tags->count()
            ]);
        } else {
            Log::warning('Subcategory not found or does not belong to category', [
                'subcategory_id' => $subcategoryId,
                'category_id' => $categoryId
            ]);
        }
    } elseif ($categoryId) {
        // Fetch tags for all subcategories under the category
        $subcategoryIds = SubCategory::where('category_id', $categoryId)->pluck('id');
        $tags = Tag::whereIn('sub_category_id', $subcategoryIds)->get();
        Log::info('Tags fetched for category', [
            'category_id' => $categoryId,
            'subcategory_ids' => $subcategoryIds->toArray(),
            'tags' => $tags->pluck('name')->toArray(),
            'tag_count' => $tags->count()
        ]);
    } elseif ($noFilter) {
        // Fetch all tags with non-null sub_category_id
        $tags = Tag::whereNotNull('sub_category_id')->get();
        Log::info('Tags fetched for no_filter', [
            'tags' => $tags->pluck('name')->toArray(),
            'tag_count' => $tags->count()
        ]);
    } else {
        Log::warning('No valid parameters provided for tag fetch');
    }

    return response()->json([
        'html' => view('front.default.products.partials.tags_list', compact('tags'))->render()
    ]);
}

protected function filterProducts(Request $request, $categoryId = null)
{
    $query = Product::query();

    if ($categoryId) {
        $query->where('category_id', $categoryId);
    } elseif ($request->category) {
        $query->where('category_id', $request->category);
    }

    if ($request->subcategory) {
        $query->where('sub_category_id', $request->subcategory);
    }

    if ($request->tags) {
        $query->whereHas('tags', function ($q) use ($request) {
            $q->whereIn('tags.id', $request->tags);
        });
    }

    if ($request->availability) {
        if ($request->availability == 'in stock') {
            $query->where('availability', 'in stock');
        } elseif ($request->availability == 'out of stock') {
            $query->where('availability', 'out of stock');
        }
    }

    if ($request->min_price) {
        $query->where('price', '>=', $request->min_price);
    }

    if ($request->max_price) {
        $query->where('price', '<=', $request->max_price);
    }

    if ($request->sort_by) {
        $this->applySorting($query, $request->sort_by);
    }

    $perPage = $request->per_page ?? 12;
    return $query->paginate($perPage);
}
    private function applySorting($query, $sortBy)
    {
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'best_selling':
                $query->withCount('orderItems as sales_count')
                      ->orderByDesc('sales_count');
                break;
            case 'a_z':
                $query->orderBy('name', 'asc');
                break;
            case 'z_a':
                $query->orderBy('name', 'desc');
                break;
            case 'old_to_new':
                $query->orderBy('created_at', 'asc');
                break;
            case 'new_to_old':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
    }

public function subcategoryProducts(Request $request, $category, $subcategory)
{
    // Validate request parameters
    $request->validate([
        'tags' => 'nullable|array',
        'tags.*' => 'numeric|exists:tags,id',
        'min_price' => 'nullable|numeric|min:0',
        'max_price' => 'nullable|numeric|min:0',
        'sort_by' => 'nullable|string|in:price_asc,price_desc,best_selling,a_z,z_a,old_to_new,new_to_old',
        'per_page' => 'nullable|integer|in:12,24,36',
        'availability' => 'nullable|string|in:in stock,out of stock',
        'grid_mode' => 'nullable|string|in:list,3,4,6',
    ]);

    // Convert URL parameters to proper case
    $categoryName = ucwords(str_replace('-', ' ', $category));
    $subcategoryName = ucwords(str_replace('-', ' ', $subcategory));

    // Validate category and subcategory
    $category = Category::where('name', $categoryName)->firstOrFail();
    $subcategory = SubCategory::where('name', $subcategoryName)
        ->where('category_id', $category->id)
        ->firstOrFail();

    // Get subcategory ID
    $selectedSubCategoryId = $subcategory->id;

    // Build product query
    $productsQuery = Product::where('sub_category_id', $selectedSubCategoryId)->with('galleries');

    // Log received tags
    \Log::info('Received tags in subcategoryProducts: ' . json_encode($request->input('tags', [])));

    // Tags filter
    if ($request->has('tags') && is_array($request->input('tags'))) {
        $tagIds = array_filter($request->input('tags'), 'is_numeric');
        if (!empty($tagIds)) {
            $productsQuery->whereHas('tags', function ($query) use ($tagIds) {
                $query->whereIn('tags.id', $tagIds);
            });
        }
    }

    // Availability filter
    if ($request->has('availability') && $request->input('availability')) {
        $productsQuery->where('availability', $request->input('availability'));
    }

    // Price range filter
    if ($request->has('min_price') && is_numeric($request->input('min_price'))) {
        $productsQuery->where('price', '>=', $request->input('min_price'));
    }
    if ($request->has('max_price') && is_numeric($request->input('max_price'))) {
        $productsQuery->where('price', '<=', $request->input('max_price'));
    }

    // Sorting logic
    $this->applySorting($productsQuery, $request->input('sort_by'));
    \Log::info('After sorting (' . $request->input('sort_by', 'default') . '): ' . $productsQuery->count());

    // Per page handling
    $perPage = $request->input('per_page', 12);
    if (!in_array($perPage, [12, 24, 36])) {
        $perPage = 12;
    }

    // Fetch tags for this subcategory
    $tags = Tag::where('sub_category_id', $selectedSubCategoryId)->get();
    \Log::info('Tags fetched for subcategory ' . $selectedSubCategoryId . ': ' . $tags->pluck('id')->toJson());

    // Paginate products
    $products = $productsQuery->paginate($perPage)->appends($request->query());
    \Log::info('Final paginated products count: ' . $products->total());

    // Cache min and max price for performance
    $minPrice = cache()->remember("min_price_subcategory_{$selectedSubCategoryId}", 3600, function () use ($selectedSubCategoryId) {
        return Product::where('sub_category_id', $selectedSubCategoryId)->min('price') ?? 0;
    });
    $maxPrice = cache()->remember("max_price_subcategory_{$selectedSubCategoryId}", 3600, function () use ($selectedSubCategoryId) {
        return Product::where('sub_category_id', $selectedSubCategoryId)->max('price') ?? 1000;
    });

    if ($request->ajax()) {
        return response()->json([
            'html' => view('front.default.products.products', compact('products'))->render(),
            'tags' => view('front.default.products.partials.tags_list', [
                'tags' => $tags,
                'selectedTags' => $request->input('tags', [])
            ])->render(),
            'pagination' => $products->links()->toHtml(),
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'selectedSubCategoryId' => $selectedSubCategoryId
        ]);
    }

    return view('front.default.products.subcategory', compact(
        'subcategory',
        'products',
        'tags',
        'minPrice',
        'maxPrice',
        'selectedSubCategoryId'
    ));
}

public function tagsByCategory(Request $request)
{
    $subCategoryId = $request->query('sub_category_id');
    $tags = Tag::where('sub_category_id', $subCategoryId)->get();
    \Log::info('Rendering tags for subcategory ' . $subCategoryId . ': ' . $tags->pluck('id')->toJson());

    return response()->json([
        'html' => view('front.default.products.partials.tags_list', [
            'tags' => $tags,
            'selectedTags' => $request->query('tags', [])
        ])->render()
    ]);
}
}
