<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('main');
        }

        return view('front.default.auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $verification_token = Str::random(64);

        $user = User::create([
            'name'               => $request->name,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'verification_token' => $verification_token,
            'is_verify'          => 0,
        ]);

        $verificationUrl = route('user.verifyEmail', ['token' => $verification_token]);
        $user->notify(new VerifyEmailNotification($user, $verificationUrl));
        // dd($user,$verificationUrl);
        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Registration successful! Please check your email to verify your account.'
            ]);
        }

        return redirect()->route('user.login')->with('success', 'Registration completed! Please check your email to verify your account.');
    }

    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('user.login')->with('error', 'Invalid or expired verification link.');
        }

        $user->is_verify = 1;
        $user->verification_token = null;
        $user->save();

        return redirect()->route('user.login')->with('success', 'Your email has been verified! You can now log in.');
    }
}