<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReviewReply;

class ReviewReplyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:reviews,id',
            'name' => 'required|string',
            'reply' => 'required|string',
        ]);

        $reply = ReviewReply::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Reply submitted successfully!',
            'reply' => [
                'id' => $reply->id,
                'name' => $reply->name,
                'reply' => $reply->reply,
                'created_at' => $reply->created_at->toDateTimeString(), // Optional for display
            ]
        ]);
    }
    public function destroy(ReviewReply $reply)
{
    $reply->delete();
    return response()->json(['success' => true]);
}
}