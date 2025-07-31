<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
class ReviewController extends Controller
{
   public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'rating' => 'required',
            'message' => 'required|string',
        ]);

        Review::create($request->all());

        return back()->with('success', 'Review submitted successfully!');
    }
}
