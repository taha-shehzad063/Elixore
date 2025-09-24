<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Category;

use Illuminate\Validation\Rule;

use App\Models\SubCategory;
class TagController extends Controller
{
     public function index()
    {
$tags = Tag::with('category','subcategory')->get(); // eager load to avoid N+1
        return view('admin.frontend.tags.index', compact('tags'));
    }

    public function create()
    {
     $categories = Category::all(); // Or use select('id', 'name') if you only need those
     $subcategories = SubCategory::all(); // Or use select('id', 'name') if you only need those
    return view('admin.frontend.tags.create', compact('categories','subcategories'));
    }

  public function store(Request $request)
{
    // dd($request->all());
    $request->validate([
'name' => [
    'required',
    'string',
    'max:255',
    Rule::unique('tags')->where(function ($query) use ($request) {
        return $query->where('category_id', $request->category_id)
                     ->where('sub_category_id', $request->sub_category_id);
    }),
],
        'category_id' => 'nullable|exists:categories,id',
        'sub_category_id' => 'nullable|exists:sub_categories,id',
    ]);

    Tag::create([
        'name' => $request->name,
        'category_id' => $request->category_id,
        'sub_category_id' => $request->sub_category_id,
    ]);

    return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully!');
}

  public function edit($id)
{
    $tags = Tag::findOrFail($id);
    $categories = Category::all();
         $subcategories = SubCategory::all(); // Or use select('id', 'name') if you only need those
    return view('admin.frontend.tags.edit', compact('tags', 'categories','subcategories'));
}

public function update(Request $request, $id)
{
    $tag = Tag::findOrFail($id);

    $request->validate([
  'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('tags')->where(function ($query) use ($request) {
                return $query->where('category_id', $request->category_id)
                             ->where('sub_category_id', $request->sub_category_id);
            })->ignore($id), // ignore current tag
        ],        'category_id' => 'nullable|exists:categories,id', // validate category
        'sub_category_id' => 'nullable|exists:sub_categories,id', // validate category
    ]);

    $tag->update([
        'name' => $request->name,
                'category_id' => $request->category_id,

        'sub_category_id' => $request->sub_category_id,
    ]);

    return redirect()->route('admin.tags.index')->with('success', 'Tag updated successfully!');
}


    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted successfully!');
    }
}
