<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        $clientId = "515502085970-2oeo9o1r5p0afd2di18d6fqag0g8sb97.apps.googleusercontent.com";
        
        $redirectUri = urlencode("https://roshnipk.store/auth/google/callback");
        $scope = urlencode('openid email profile');

        $url = "https://accounts.google.com/o/oauth2/v2/auth?client_id={$clientId}&redirect_uri={$redirectUri}&response_type=code&scope={$scope}&access_type=offline&prompt=select_account";

        return redirect($url);
    }

    public function handleGoogleCallback(Request $request)
    {
        // if ($request->has('error')) {
        //     return redirect()->route('user.login')->with('error', 'Google login failed');
        // }

        $code = $request->get('code');

        // 1. Exchange code for access token
        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => "515502085970-2oeo9o1r5p0afd2di18d6fqag0g8sb97.apps.googleusercontent.com",
            'client_secret' => "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",
            'redirect_uri' => "https://roshnipk.store/auth/google/callback",
            'grant_type' => 'authorization_code',
        ]);

        if (!$tokenResponse->ok()) {
            return redirect()->route('user.login')->with('error', 'Failed to get access token');
        }

        $accessToken = $tokenResponse->json()['access_token'];

        // 2. Get user info from Google
        $googleUser = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/oauth2/v2/userinfo')
            ->json();

        if (!isset($googleUser['id'])) {
            return redirect()->route('user.login')->with('error', 'Failed to get user info');
        }

        // 3. Find or create local user
        $user = User::where('google_id', $googleUser['id'])->first();

        if (!$user) {
            $user = User::where('email', $googleUser['email'])->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser['id'],
                    'avatar' => $googleUser['picture'] ?? null,
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser['name'] ?? $googleUser['email'],
                    'email' => $googleUser['email'],
                    'google_id' => $googleUser['id'],
                    'avatar' => $googleUser['picture'] ?? null,
                    'password' => bcrypt(uniqid()), // random password
                ]);
            }
        }

        // 4. Log the user in
        Auth::login($user);

        return redirect()->route('main');
    }
}
