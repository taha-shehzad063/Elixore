<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class ProfileController extends Controller
{
     public function changePasswordForm()
    {
        return view('profile.change-password');
    }

    public function updatePasswordAjax(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return response()->json([
                'status' => 'error',
                'field'  => 'current_password',
                'message' => 'Your current password is incorrect.'
            ], 422);
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully!'
        ]);
    }
    public function checkAuth(Request $request)
    {
        return response()->json([
            'authenticated' => Auth::check()
        ]);
    }
}
