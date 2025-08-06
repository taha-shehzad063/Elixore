<?php
// app/Http/Controllers/Default/NewsletterController.php
namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ]);

        Newsletter::create($validated);

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Thank you for subscribing!']);
        }

        return back()->with('success', 'Thank you for subscribing!');
    }
}