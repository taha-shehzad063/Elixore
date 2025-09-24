<?php

namespace App\Http\Controllers\Admin\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\UploadsImages;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Tag;
use App\Models\ProductGallery;
use App\Models\ProductOption;
use App\Models\ProductSpecification;

class ProductController extends Controller
{
    use UploadsImages;

   public function index(Request $request)
{
    $products = Product::with(['category', 'subCategory', 'galleries'])->get();

    return view('admin.frontend.home.products.index', compact('products'));
}


    public function showReviews(Product $product)
    {
        $reviews = $product->reviews()->with('replies')->latest()->get();
        return view('admin.frontend.home.products.partials.reviews_content', [
            'product' => $product,
            'reviews' => $reviews
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        $subcategories = SubCategory::all(); // Load all subcategories
        $tags = Tag::all();
        return view('admin.frontend.home.products.create', compact('categories', 'subcategories', 'tags'));
    }

 public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'info' => 'required|string',
        'link' => 'required|string',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'discount_price' => 'nullable|numeric',
        'category_id' => 'required|exists:categories,id',
        'sub_category_id' => 'required|exists:sub_categories,id',
        'availability' => 'required|in:in stock,out of stock',
        'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'image_links.*' => 'nullable|url',
        'tags' => 'nullable|array',
        'tags.*' => 'exists:tags,id',
        'color' => 'nullable|array',   // ✅ validate as array
        'color.*' => 'string|max:50',  // each color string
    ]);

    $slug = $this->generateUniqueSlug($request->name);

    $product = Product::create([
        'name' => $request->name,
        'slug' => $slug,
        'price' => $request->price,
        'discount_price' => $request->discount_price,
        'category_id' => $request->category_id,
        'sub_category_id' => $request->sub_category_id,
        'info' => $request->info,
        'link' => $request->link,
        'description' => $request->description,
        'availability' => $request->availability,
        'color' => !empty($request->color) ? implode(',', $request->color) : null, // ✅ save multiple
    ]);

    // Handle Gallery Images
    if ($request->hasFile('gallery_images')) {
        foreach ($request->file('gallery_images') as $galleryImage) {
            $path = $this->uploadImage($galleryImage, 'gallery');
            ProductGallery::create([
                'product_id' => $product->id,
                'image' => $path,
            ]);
        }
    }

    // Handle Image Links
    if ($request->has('image_links') && is_array($request->image_links)) {
        foreach ($request->image_links as $imageLink) {
            if (!empty($imageLink) && filter_var($imageLink, FILTER_VALIDATE_URL)) {
                ProductGallery::create([
                    'product_id' => $product->id,
                    'image' => $imageLink,
                ]);
            }
        }
    }

    // Handle Tags
    $tags = $request->input('tags', []);
    if (!empty($tags)) {
        $product->tags()->sync($tags);
    }

    return redirect()->route('admin.products.index')
        ->with('success', 'Product created successfully with gallery images and colors!');
}


    public function show($id)
    {
        $products = Product::findOrFail($id);
        return view('products.show', compact('products'));
    }

    public function edit($id)
    {
        $products = Product::with(['specifications', 'options', 'tags', 'galleries'])->findOrFail($id);
        $categories = Category::all();
        $subcategories = SubCategory::all(); // Load all subcategories
        $tags = Tag::all();
        return view('admin.frontend.home.products.edit', compact('products', 'categories', 'subcategories', 'tags'));
    }

public function deleteGalleryImage($id)
{
    $image = ProductGallery::findOrFail($id);

    $filePath = public_path($image->image);

    if ($image->image && file_exists($filePath)) {
        unlink($filePath);
    }

    $image->delete();

    return back()->with('success', 'Gallery image deleted successfully.');
}


public function update(Request $request, $id)
{
    $products = Product::findOrFail($id);

    $request->validate([
        'name' => 'required|string',
        'info' => 'required|string',
        'link' => 'required|string',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'discount_price' => 'nullable|numeric',
        'category_id' => 'required|exists:categories,id',
        'sub_category_id' => 'required|exists:sub_categories,id',
        'availability' => 'required|in:in stock,out of stock',
        'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'image_links.*' => 'nullable|url',
        'tags' => 'nullable|array',
        'tags.*' => 'exists:tags,id',
        'color' => 'nullable|array',
        'color.*' => 'string|max:50',
    ]);

    $slug = $this->generateUniqueSlug($request->name, $products->id);

    $products->update([
        'name' => $request->name,
        'slug' => $slug,
        'price' => $request->price,
        'discount_price' => $request->discount_price,
        'category_id' => $request->category_id,
        'sub_category_id' => $request->sub_category_id,
        'info' => $request->info,
        'link' => $request->link,
        'description' => $request->description,
        'availability' => $request->availability,
        'color' => !empty($request->color) ? implode(',', $request->color) : null,
    ]);

    // Gallery Images
    if ($request->hasFile('gallery_images')) {
        foreach ($request->file('gallery_images') as $file) {
            $path = $this->uploadImage($file, 'gallery');
            ProductGallery::create([
                'product_id' => $products->id,
                'image' => $path,
            ]);
        }
    }

    // Image Links
    if ($request->has('image_links') && is_array($request->image_links)) {
        foreach ($request->image_links as $imageLink) {
            if (!empty($imageLink) && filter_var($imageLink, FILTER_VALIDATE_URL)) {
                ProductGallery::create([
                    'product_id' => $products->id,
                    'image' => $imageLink,
                ]);
            }
        }
    }

    // Tags
    $productTags = $request->input('tags', []);
    $products->tags()->sync($productTags);

    return redirect()->route('admin.products.index')->with('success', 'Product updated successfully with colors!');
}


    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete main image from storage
        $this->deleteImage($product->image);

        // Delete all related gallery images
        if ($product->galleryImages && $product->galleryImages->count()) {
            foreach ($product->galleryImages as $galleryImage) {
                $this->deleteImage($galleryImage->image);
                $galleryImage->delete();
            }
        }

        // Delete the product
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted with its gallery images!');
    }

    private function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (
            Product::where('slug', $slug)
                ->when($id, fn($query) => $query->where('id', '!=', $id))
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }
   
}