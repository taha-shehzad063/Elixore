<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Default\FrontendController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Social\GoogleController;
use App\Http\Controllers\Social\FacebookController;

use Illuminate\Support\Facades\Artisan;

Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    Artisan::call('optimize');
    
    return response()->json([
        'status' => true,
        'message' => '✅ Cache cleared and optimized successfully!'
    ]);
});
// Home Page
Route::get('/', [FrontendController::class, 'home'])->name('main');
Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');
Route::get('/blog-details/{slug}', [FrontendController::class, 'detail'])->name('blogs.details');
Route::post('/blog/comment', [FrontendController::class, 'postComment'])->name('blog.comment');
Route::post('/blog/comment-reply', [FrontendController::class, 'storeReply'])->name('comment.reply');


// Login Register
Route::get('/login', [LoginController::class, 'index'])->name('user.login');
Route::post('/login/post', [LoginController::class, 'login'])->name('user.login.store');
Route::get('/register', [RegisterController::class, 'index'])->name('user.register.get');;
Route::post('/register-user', [RegisterController::class, 'register'])->name('user.register');
Route::post('/logout', [LoginController::class, 'logout'])->name('user.logout');

// Google Auth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Facebook Auth
Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);