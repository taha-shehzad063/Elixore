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

        ReviewReply::create($request->all());

        return back()->with('success', 'Reply submitted successfully!');
    }
}
