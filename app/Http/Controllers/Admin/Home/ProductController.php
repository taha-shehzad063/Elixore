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
// New (Correct)
use App\Models\ProductOption;
use App\Models\ProductSpecification;
class ProductController extends Controller
{
        use UploadsImages;

 public function index(Request $request)
{
    $query = Product::query();

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $perPage = $request->get('per_page', 10);

    $products = $query->paginate($perPage)->appends($request->all());

    if ($request->ajax()) {
        return view('admin.frontend.home.products.index', compact('products'))->render();
    }

    return view('admin.frontend.home.products.index', compact('products'));
}



    public function create()
    {
        $categories= Category::all();
        $tags= Tag::all();
        return view('admin.frontend.home.products.create', compact('categories','tags'));
    }
  public function store(Request $request)
    {
        // dd($request->all()); // Uncomment for debugging

        $request->validate([
            'name' => 'required|string',
            'info' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'availability' => 'required|in:in stock,out of stock',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            // Validation for specifications (optional, but good practice)
            'specifications.*.key' => 'nullable|string|max:255',
            'specifications.*.value' => 'nullable|string|max:255',
            // Validation for additional options
            'options.*.key' => 'nullable|string|max:255',
            'options.*.value' => 'nullable|string|max:255',
        ]);

        $slug = $this->generateUniqueSlug($request->name);

        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'category_id' => $request->category_id,
            'info' => $request->info,
            'description' => $request->description,
            'availability' => $request->availability,
        ]);

        // --- Handle Gallery Images ---
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $galleryImage) {
                // Ensure you have an 'uploadImage' helper or method available
                // For demonstration, let's assume it saves to 'public/uploads/gallery'
                $path = $galleryImage->store('uploads/gallery', 'public'); 
                
                ProductGallery::create([
                    'product_id' => $product->id,
                    'image' => $path,
                ]);
            }
        }

        // --- Handle Product Tags ---
        // Assuming your Product model has a many-to-many relationship with Tag model
      // --- Handle Product Tags ---
// This assumes a many-to-many relationship with Tag model
$tags = $request->input('tags', []);
if (!empty($tags)) {
    $product->tags()->sync($tags); // This line requires the product_tag pivot table
}

        // --- Handle Product Specifications ---
        if ($request->has('specifications') && is_array($request->specifications)) {
            foreach ($request->specifications as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    ProductSpecification::create([
                        'product_id' => $product->id,
                        'key' => $spec['key'],
                        'value' => $spec['value'],
                    ]);
                }
            }
        }

        // --- Handle Additional Options ---
        // Make sure you have created the ProductOption model and migration for 'product_options' table
        // with 'product_id', 'key', and 'value' columns.
        if ($request->has('options') && is_array($request->options)) {
            foreach ($request->options as $option) {
                if (!empty($option['key']) && !empty($option['value'])) {
                    ProductOption::create([
                        'product_id' => $product->id,
                        'key' => $option['key'],
                        'value' => $option['value'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully with specifications, gallery images, and additional options!');
    }


    public function show($id)
    {
        $products = Product::findOrFail($id);
        return view('products.show', compact('products'));
    }

  public function edit($id)
    {
        // Eager load relationships: 'specifications', 'options', 'tags', and 'galleries'
        $products = Product::with(['specifications', 'options', 'tags', 'galleries'])->findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all(); // Get all available tags to populate the checkboxes

        // dd($products->toArray()); // Uncomment for debugging to see loaded data

        return view('admin.frontend.home.products.edit', compact('products', 'categories', 'tags'));
    }



public function deleteGalleryImage($id)
{
    $image = ProductGallery::findOrFail($id);

    if ($image->image && \Storage::exists($image->image)) {
        \Storage::delete($image->image);
    }

    $image->delete();

    return back()->with('success', 'Gallery image deleted successfully.');
}


 public function update(Request $request, $id)
    {
        $products = Product::findOrFail($id); // Using $products as per your existing code

        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric', // Changed to numeric
            'discount_price' => 'nullable|numeric', // Changed to numeric
            'category_id' => 'required|exists:categories,id',
            'info' => 'required|string',
            'description' => 'required|string',
            'availability' => 'required|in:in stock,out of stock', // Added availability validation
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // --- Validation for Specifications ---
            'specifications' => 'nullable|array',
            'specifications.*.key' => 'required_with:specifications.*.value|string|max:255', // Require key if value is present
            'specifications.*.value' => 'required_with:specifications.*.key|string|max:255', // Require value if key is present

            // --- Validation for Options ---
            'options' => 'nullable|array',
            'options.*.key' => 'required_with:options.*.value|string|max:255',
            'options.*.value' => 'required_with:options.*.key|string|max:255',

            // --- Validation for Tags (Many-to-Many) ---
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id', // Ensure each tag ID exists in the tags table
        ]);

        // Generate unique slug, excluding the current product's ID
        $slug = $this->generateUniqueSlug($request->name, $products->id);

        $products->update([
            'name' => $request->name,
            'slug' => $slug,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'category_id' => $request->category_id,
            'info' => $request->info,
            'description' => $request->description,
            'availability' => $request->availability,
            // 'tag_id' => $request->tag_id, // ONLY include this if you have a single tag_id column (one-to-many)
        ]);

        // --- Handle Gallery Images (Update Logic) ---
        // Your current logic deletes all old images if new ones are uploaded.
        // If you want to only *add* new images and keep existing ones (unless explicitly deleted via route),
        // then remove the 'delete old gallery image files' block.
        // The current implementation is: if user selects *any* files in gallery_images input, all old are purged.
        if ($request->hasFile('gallery_images')) {
            // Delete old gallery image files from storage and DB records
            foreach ($products->galleries as $gallery) {
                // Assuming 'deleteImage' helper exists and uses Storage facade
                // Example: Storage::disk('public')->delete($gallery->image);
                $this->deleteImage($gallery->image);
                $gallery->delete(); // Delete DB record
            }

            // Upload new gallery images
            foreach ($request->file('gallery_images') as $file) {
                $path = $this->uploadImage($file, 'gallery'); // Ensure this method correctly saves and returns path
                ProductGallery::create([
                    'product_id' => $products->id,
                    'image' => $path,
                ]);
            }
        }
        // NOTE: Individual image deletion (from blade `delete-gallery-image` link) is handled by a separate route/method.


        // --- Handle Product Specifications ---
        // Delete all old specifications and create new ones based on current submission
        $products->specifications()->delete();
        if ($request->has('specifications') && is_array($request->specifications)) {
            foreach ($request->specifications as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) { // Only save if both key and value are present
                    $products->specifications()->create([
                        'key' => $spec['key'],
                        'value' => $spec['value'],
                    ]);
                }
            }
        }

        // --- Handle Additional Options ---
        // Delete all old options and create new ones based on current submission
        $products->options()->delete();
        if ($request->has('options') && is_array($request->options)) {
            foreach ($request->options as $option) {
                if (!empty($option['key']) && !empty($option['value'])) { // Only save if both key and value are present
                    $products->options()->create([
                        'key' => $option['key'],
                        'value' => $option['value'],
                    ]);
                }
            }
        }

        // --- Handle Product Tags (Many-to-Many) ---
        // Sync the submitted tags. This will detach old ones and attach new ones.
        $productTags = $request->input('tags', []);
        $products->tags()->sync($productTags);


        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }



public function destroy($id)
{
    $product = Product::findOrFail($id);

    // Delete main image from storage
    $this->deleteImage($product->image);

    // Delete all related gallery images
    if ($product->galleryImages && $product->galleryImages->count()) {
        foreach ($product->galleryImages as $galleryImage) {
            $this->deleteImage($galleryImage->image); // delete file from storage
            $galleryImage->delete(); // delete DB record
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

    // Check if slug exists, and append counter if needed
    while (
        Product::where('slug', $slug)
            ->when($id, fn($query) => $query->where('id', '!=', $id)) // exclude current ID in update
            ->exists()
    ) {
        $slug = $originalSlug . '-' . $counter++;
    }

    return $slug;
}
public function getSubcategories($id)
{
    $subcategories = SubCategory::where('category_id', $id)->get();
    return response()->json($subcategories);
}

}
