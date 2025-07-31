<?php

namespace App\Http\Controllers\Admin\Components;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;

class SubCategoyController extends Controller
{
     public function index()
    {
        $subcategories = SubCategory::with('category')->get();
        return view('admin.frontend.subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.frontend.subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255'
        ]);

        SubCategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.subcategories.index')->with('success', 'Sub-category created!');
    }

    public function edit($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $categories = Category::all();
        return view('admin.frontend.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $subcategory = SubCategory::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255'
        ]);

        $subcategory->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.subcategories.index')->with('success', 'Sub-category updated!');
    }

    public function destroy($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $subcategory->delete();

        return redirect()->route('admin.subcategories.index')->with('success', 'Sub-category deleted!');
    }
}
