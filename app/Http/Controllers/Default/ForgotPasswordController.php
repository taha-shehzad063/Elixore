<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Notifications\SendOtpNotification;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
      public function showForgotForm()
    {
        return view('front.default.forgot-password');
    }

public function sendOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email'
    ]);

    $user = User::where('email', $request->email)->first();

    // Generate OTP
    $otp = rand(100000, 999999);
    $user->otp = $otp;
    $user->otp_expires_at = Carbon::now()->addMinutes(10);
    $user->save();

    // Send OTP via Notification
    $user->notify(new SendOtpNotification($otp));

    // Redirect to OTP form with email parameter
// sendOtp()
session(['reset_email' => $request->email]);

return redirect()->route('password.otp.form')
                 ->with('success', 'OTP has been sent to your email.');

}

 public function verifyOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'otp' => 'required|numeric',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = User::where('email', $request->email)
        ->where('otp', $request->otp)
        ->where('otp_expires_at', '>', Carbon::now())
        ->first();

    if (!$user) {
        return back()->with('error', 'Invalid or expired OTP.');
    }

    $user->password = Hash::make($request->password);
    $user->otp = null;
    $user->otp_expires_at = null;
    $user->save();

    // Clear session email
    session()->forget('reset_email');

    // Redirect with success so login page can show a message
    return redirect()->route('user.login')->with('success', 'Password reset successfully. Please login.');
}

}
