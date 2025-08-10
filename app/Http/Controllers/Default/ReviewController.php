<?php
namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'rating' => 'required|numeric|min:0.5|max:5',
            'message' => 'required|string',
        ]);

        $review = Review::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Review added!',
            'review' => [
                'id' => $review->id,
                'name' => $review->name,
                'rating' => $review->rating,
                'message' => $review->message,
                'created_at' => $review->created_at->toDateTimeString(), // Optional for display
            ]
        ]);
    }

    public function summary($productId)
    {
        $product = Product::findOrFail($productId);
        $average = $product->reviews->avg('rating');
        $count = $product->reviews->count();
        $ratingBreakdown = $product->reviews->groupBy('rating')->map->count();

        return response()->json([
            'average' => $average,
            'count' => $count,
            'ratingBreakdown' => $ratingBreakdown
        ]);
    }
    public function destroy(Review $review)
{
    $review->delete();
    return response()->json(['success' => true]);
}


public function storeUserEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'product_id' => 'required|exists:products,id'
    ]);

    session(['user_email_' . $request->product_id => $request->email]);

    return response()->json(['status' => true]);
}

public function getUserEmail(Request $request)
{
    $productId = $request->query('product_id');
    $email = session('user_email_' . $productId);

    return response()->json([
        'status' => true,
        'email' => $email
    ]);
}

public function getUserReview($productId, Request $request)
{
    $email = $request->query('email');
    $review = Review::where('product_id', $productId)
        ->where('email', $email)
        ->first();

    return response()->json([
        'status' => true,
        'review' => $review
    ]);
}

public function update(Request $request)
{
    $request->validate([
        'review_id' => 'required|exists:reviews,id',
        'product_id' => 'required|exists:products,id',
        'rating' => 'required|numeric|min:0.5|max:5',
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:20',
        'message' => 'required|string'
    ]);

    $review = Review::findOrFail($request->review_id);
    $review->update([
        'rating' => $request->rating,
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'message' => $request->message
    ]);

    return response()->json([
        'status' => true,
        'review' => $review
    ]);
}
}