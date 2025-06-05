<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerImage;
use App\Models\CollectionBanner;
use App\Models\Product;
use App\Models\Blog;
use App\Models\Tag;
use App\Models\GeneralSetting;
use App\Models\Comment;

class FrontendController extends Controller
{
   public function home() {
    $data = [
        'banners' => BannerImage::all(),
        'products' => Product::all(),
        'collections' => CollectionBanner::first(),
        'setting' => GeneralSetting::first(),
    ];

    return view('front.default.homepage')->with($data);
}

    public function blog(){
        $data = [
        'blogs' => Blog::all(),
        'tags' => Tag::all(),
       'latestBlogs' =>  Blog::latest()->take(4)->get()
       
    ];
        return view('front.default.blog')->with($data);
    }
public function detail($slug)
{
    $blogs = Blog::with('tags')->where('slug', $slug)->firstOrFail();
   
    $comments = Comment::where('blog_id', $blogs->id)
                ->whereNull('parent_id')
                ->with('replies')
                ->latest()
                ->get();

    $tags = Tag::all();
    $latestBlogs = Blog::latest()->take(4)->get();

    return view('front.default.blog_details', compact('blogs', 'tags', 'latestBlogs', 'comments'));
}


public function postComment(Request $request)
{
    $request->validate([
        'blog_id' => 'required|exists:blogs,id',
        'comment' => 'required|string',
        'name' => 'required|string|max:255',
    ]);

    Comment::create([
        'blog_id' => $request->blog_id,
        'parent_id' => $request->parent_id, // Can be null
        'author_name' => $request->name,
        'comment' => $request->comment,
    ]);

    return back()->with('success', 'Comment submitted!');
}
public function storeReply(Request $request)
{
    $request->validate([
        'comment' => 'required',
        'author_name' => 'required',
        'email' => 'required|email',
        'blog_id' => 'required|exists:blogs,id',
        'parent_id' => 'required|exists:comments,id'
    ]);

    Comment::create([
        'blog_id' => $request->blog_id,
        'parent_id' => $request->parent_id,
        'author_name' => $request->author_name,
        'comment' => $request->comment,
    ]);

    return back()->with('success', 'Reply added successfully!');
}

}
