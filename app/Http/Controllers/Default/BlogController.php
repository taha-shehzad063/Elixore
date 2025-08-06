<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerImage;
use App\Models\CollectionBanner;
use App\Models\Cart;
use App\Models\Review;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Blog;
use App\Models\Tag;
use App\Models\GeneralSetting;
use App\Models\Wishlist;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class BlogController extends Controller
{
    public function blog(Request $request)
    {
        $blogsQuery = Blog::with(['tags', 'comments' => function ($query) {
            $query->whereNull('parent_id')->with('replies');
        }]);

        // Search by keyword
        if ($request->has('keyword') && $request->input('keyword')) {
            $keyword = $request->input('keyword');
            $blogsQuery->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                      ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        // Filter by tag
        if ($request->has('tag') && $request->input('tag')) {
            $tagName = $request->input('tag');
            $blogsQuery->whereHas('tags', function ($query) use ($tagName) {
                $query->where('name', $tagName);
            });
        }

        // Sorting (default: latest)
        $blogsQuery->latest();

        // Pagination
        $perPage = 2; // 2 blogs per page
        $blogs = $blogsQuery->paginate($perPage);

        // Additional data
        $tags = Tag::has('blogs')->get(); // Only tags with blogs
        $latestBlogs = Blog::latest()->take(4)->get();

        // Handle AJAX requests
        if ($request->ajax()) {
            return view('front.default.blog_products.blog_items', compact('blogs'))->render();
        }

        return view('front.default.blog', compact('blogs', 'tags', 'latestBlogs'));
    }
public function detail($slug, Request $request)
{
    $blogs = Blog::with('tags')->where('slug', $slug)->firstOrFail();
    $comments = Comment::where('blog_id', $blogs->id)
        ->whereNull('parent_id')
        ->with('replies')
        ->latest()
        ->get();
    $tags = Tag::has('blogs')->get();
    $latestBlogs = Blog::where('id', '!=', $blogs->id)->latest()->take(4)->get();

    // Fetch suggested blogs similar to the current blog
    $tagNames = $blogs->tags->pluck('name')->toArray();
    $title = $blogs->name;

    $suggestedBlogs = Blog::where('id', '!=', $blogs->id)
        ->when(!empty($tagNames), function ($query) use ($tagNames) {
            // Join with blog_tag pivot table to match blogs with similar tags
            $query->whereHas('tags', function ($q) use ($tagNames) {
                foreach ($tagNames as $tag) {
                    $q->orWhere('name', 'LIKE', '%' . $tag . '%');
                }
            });
        })
        ->orWhere('name', 'LIKE', '%' . $title . '%') // Match blogs with similar title
        ->latest()
        ->paginate(7); // 2 per page, as in the original code
$suggestedBlogsQuery = $suggestedBlogs;
    if ($request->ajax() && $request->input('section') === 'suggested_blogs') {
        return view('front.default.suggested_blogs', compact('suggestedBlogs','suggestedBlogsQuery'))->render();
    }

    return view('front.default.blog_details', compact('blogs', 'tags', 'latestBlogs', 'comments', 'suggestedBlogsQuery','suggestedBlogs'));
}
public function blogsByTag($name)
{
    $tag = Tag::where('name', $name)->firstOrFail();

    $blogs = $tag->blogs()->with(['tags', 'comments' => function($q) {
        $q->whereNull('parent_id')->with('replies');
    }])->latest()->paginate(2); // 2 blogs per page

    $tags = Tag::all();
    $latestBlogs = Blog::latest()->take(4)->get();

    if (request()->ajax()) {
        return view('front.default.blog_products.blog_items', compact('blogs'))->render();
    }

    return view('front.default.blog', compact('blogs', 'tags', 'latestBlogs'));
}
    public function comment(Request $request)
    {
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'comment' => 'required|string|max:1000',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'blog_id' => $request->blog_id,
            'parent_id' => $request->parent_id,
            'comment' => $request->comment,
            'author_name' => $request->name,
            'author_email' => $request->email,
            'author_website' => $request->website,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => [
                    'author_name' => $comment->author_name,
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'replies' => []
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Comment posted successfully.');
    }
    public function searchBlog(Request $request)
{
    $keyword = $request->input('keyword');
    $blogs = Blog::with(['tags', 'comments' => function($query) {
        $query->whereNull('parent_id')->with('replies');
    }])
    ->where('name', 'LIKE', "%{$keyword}%")
    ->orWhere('description', 'LIKE', "%{$keyword}%")
    ->latest()
    ->paginate(2);

    if ($request->ajax()) {
        return view('front.default.blog_products.blog_items', compact('blogs'))->render();
    }

    $tags = Tag::all();
    $latestBlogs = Blog::latest()->take(4)->get();
    return view('front.default.blog', compact('blogs', 'tags', 'latestBlogs'));
}
}
