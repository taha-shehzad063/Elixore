<?php

namespace App\Http\Controllers\Admin\Components;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
class CategoyController extends Controller
{
     public function index()
    {
        $categories = Category::with('subCategories')->get();
        return view('admin.frontend.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.frontend.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created!');
    }

    public function edit($id)
    {
        $category = Category::with('subCategories')->findOrFail($id);
        return view('admin.frontend.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted!');
    }
}
