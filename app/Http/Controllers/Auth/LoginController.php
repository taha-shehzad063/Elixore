<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    public function index(){
         // Check if the user is already logged in
    if (Auth::check()) {
        // If logged in, redirect to the main page
        return redirect()->route('main');
    }
        return view('front.default.auth.login');
    }

    public function login(Request $request)
    {
        // Validate the login request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Check user credentials
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            // If login is successful, redirect to the homepage with success message
            return redirect()->route('main')->with('success', 'Login successful!');
        } else {
            // If login fails, redirect back with an error message
            return back()->withErrors([
                'email' => 'Invalid email or password.'
            ])->withInput();
        }
    }
    public function logout()
{
    Auth::logout(); // Log out the user
    return redirect()->route('user.login'); // Redirect to the login page
}
}
