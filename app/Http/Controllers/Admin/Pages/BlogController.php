<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UploadsImages;
 use Illuminate\Support\Str;
 use App\Models\Blog;
 use App\Models\Comment;
 use App\Models\Tag;
class BlogController extends Controller
{
     use UploadsImages;

        public function index()
    {
        $blogs = Blog::all();
        return view('admin.frontend.blog.index', compact('blogs'));
    }

    public function create()
    {
$tags = Tag::whereNull('category_id')
           ->whereNull('sub_category_id')
           ->get();

        return view('admin.frontend.blog.create', compact('tags'));
    }

 public function store(Request $request)
 {
        // dd('ok'); 
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $slug = $this->generateUniqueSlug($request->name);

        $imagePath = $this->uploadImage($request->file('image'), 'blog');

        $blog = Blog::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

       $blog->tags()->sync($request->input('tags'));

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully!');
    }


    public function show($id)
    {
        
        $blog = Blog::findOrFail($id);
        return view('blog.show', compact('blog'));
    }

    public function edit($id)
    {
$tags = Tag::whereNull('category_id')
           ->whereNull('sub_category_id')
           ->get();
      $blogs = Blog::with('tags')->findOrFail($id);
        // dd($blogs, $tags); 
        return view('admin.frontend.blog.edit', compact('blogs','tags'));
    }

   public function update(Request $request, $id)
{
    $blog = Blog::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'tags' => 'required|array',
        'tags.*' => 'exists:tags,id',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $slug = $this->generateUniqueSlug($request->name, $id);

    $data = [
        'name' => $request->name,
        'slug' => $slug,
        'description' => $request->description,
    ];

    if ($request->hasFile('image')) {
    // delete old image if exists
    if ($blog->image && file_exists(public_path($blog->image))) {
        unlink(public_path($blog->image));
    }

    // upload new image in public/blog
    $data['image'] = $this->uploadImage($request->file('image'), 'blog');
}


    $blog->update($data);

    // sync updated tags
    $blog->tags()->sync($request->input('tags'));

    return redirect()->route('admin.blogs.index')->with('success', 'Blog updated!');
}

public function showComments(Blog $blog)
{
    $comments = $blog->comments()
        ->with('replies')
        ->whereNull('parent_id') // Only top-level comments
        ->latest()
        ->get();
        
    return view('admin.frontend.blog.partials.comments_content', [
        'blog' => $blog,
        'comments' => $comments
    ]);
}
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog deleted!');
    }

    private function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (
            Blog::where('slug', $slug)
                ->when($id, fn($query) => $query->where('id', '!=', $id))
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }
public function storeComment(Request $request, Blog $blog)
{
    $request->validate([
        'author_name' => 'required',
        'comment' => 'required',
        'parent_id' => 'nullable|exists:comments,id',
    ]);

    $blog->comments()->create([
        'author_name' => $request->author_name,
        'comment' => $request->comment,
        'parent_id' => $request->parent_id,
    ]);

    return back()->with('success', 'Comment added!');
}
public function destroyblog(Comment $comment)
{
    $comment->delete();
    return response()->json(['success' => true]);
}
}
