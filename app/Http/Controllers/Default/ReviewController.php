<?php
namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

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
}