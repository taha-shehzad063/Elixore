<?php
namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function show()
    {
        return view('front.default.contact.contact');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($validated);

        // If AJAX, return JSON
        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Your message has been sent!']);
        }

        // Fallback for non-AJAX
        return back()->with('success', 'Your message has been sent!');
    }
}