<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Category;
class TagController extends Controller
{
     public function index()
    {
$tags = Tag::with('category')->get(); // eager load to avoid N+1
        return view('admin.frontend.tags.index', compact('tags'));
    }

    public function create()
    {
     $categories = Category::all(); // Or use select('id', 'name') if you only need those
    return view('admin.frontend.tags.create', compact('categories'));
    }

  public function store(Request $request)
{
    // dd($request->all());
    $request->validate([
        'name' => 'required|string|unique:tags,name|max:255',
        'category_id' => 'required|exists:categories,id',
    ]);

    Tag::create([
        'name' => $request->name,
        'category_id' => $request->category_id,
    ]);

    return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully!');
}

  public function edit($id)
{
    $tags = Tag::findOrFail($id);
    $categories = Category::all(); // Fetch all categories for dropdown
    return view('admin.frontend.tags.edit', compact('tags', 'categories'));
}

public function update(Request $request, $id)
{
    $tag = Tag::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255|unique:tags,name,' . $id,
        'category_id' => 'required|exists:categories,id', // validate category
    ]);

    $tag->update([
        'name' => $request->name,
        'category_id' => $request->category_id,
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
