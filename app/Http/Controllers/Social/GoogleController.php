<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

   public function handleGoogleCallback()
{
    $googleUser = Socialite::driver('google')->stateless()->user();

    // Try finding the user by Google ID
    $user = User::where('google_id', $googleUser->getId())->first();

    if (!$user) {
        // Try finding user by email (in case registered by password before)
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Attach Google ID to existing user
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        } else {
            // No user found, create a new one
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        }
    }

    Auth::login($user);

    return redirect()->route('main');
}

}
