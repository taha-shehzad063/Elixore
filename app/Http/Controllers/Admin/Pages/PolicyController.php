<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Policy;
use Illuminate\Support\Str;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::all();
        return view('admin.frontend.policy.index', compact('policies'));
    }

    public function create()
    {
        return view('admin.frontend.policy.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:policies,title',
            'content' => 'required|string',
        ]);

        $slug = Str::slug($request->title);

        Policy::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
        ]);

        return redirect()->route('admin.policy.index')->with('success', 'Policy created successfully!');
    }

    public function edit($id)
    {
        $policy = Policy::findOrFail($id);
        return view('admin.frontend.policy.edit', compact('policy'));
    }

    public function update(Request $request, $id)
    {
        $policy = Policy::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255|unique:policies,title,' . $id,
            'content' => 'required|string',
        ]);

        $slug = Str::slug($request->title);

        $policy->update([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
        ]);

        return redirect()->route('admin.policy.index')->with('success', 'Policy updated successfully!');
    }

    public function destroy($id)
    {
        $policy = Policy::findOrFail($id);
        $policy->delete();

        return redirect()->route('admin.policy.index')->with('success', 'Policy deleted successfully!');
    }
}
