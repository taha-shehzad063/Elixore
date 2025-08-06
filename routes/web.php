<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Default\FrontendController;
use App\Http\Controllers\Default\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Social\GoogleController;
use App\Http\Controllers\Social\FacebookController;
use App\Http\Controllers\Default\ReviewController;
use App\Http\Controllers\Default\ReviewReplyController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Default\Payment\PaymentController;
use App\Http\Controllers\Default\CartController;
use App\Http\Controllers\Default\OrderController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Default\AuthController;
use App\Http\Controllers\Default\PolicyController;
use App\Http\Controllers\Default\BlogController;
use App\Http\Controllers\Default\Shop\ShopController;
use App\Http\Controllers\Default\ContactController;
use App\Http\Controllers\Default\NewsletterController;
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    Artisan::call('optimize');
    
    return response()->json([
        'status' => true,
        'message' => '✅ Cache cleared and optimized successfully!'
    ]);
});

Route::get('/copy-storage', function () {
    Artisan::call('storage:link');
    return 'Storage link created successfully!';
})->name('copy.storage');

// Home Page
Route::get('/', [FrontendController::class, 'home'])->name('main');

Route::get('/testemail', function () {
    Mail::raw('This is a test email from Gmail SMTP in Laravel.', function ($message) {
        $message->to('tahashehzad063@gmail.com')
                ->subject('Test Gmail SMTP');
    });
    return 'Email sent!';
});
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

// Login Register
Route::get('/login', [LoginController::class, 'index'])->name('user.login');
Route::post('/login/post', [LoginController::class, 'login'])->name('user.login.store');
Route::get('/register', [RegisterController::class, 'index'])->name('user.register.get');;
Route::post('/register-user', [RegisterController::class, 'register'])->name('user.register');
Route::post('/logout', [LoginController::class, 'logout'])->name('user.logout');

// Email Verification
Route::get('/verify-email/{token}', [RegisterController::class, 'verifyEmail'])->name('user.verifyEmail');
// Google Auth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Facebook Auth
Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);


// Blogs
Route::get('/blog', [BlogController::class, 'blog'])->name('blog');
Route::get('/blog-details/{slug}', [BlogController::class, 'detail'])->name('blogs.details');
Route::post('/blog/comment', [BlogController::class, 'comment'])->name('blog.comment');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');

Route::get('/blog/tag/{tag}', [BlogController::class, ' '])->name('blogs.byTag');
//Review
Route::post('/review/store', [ReviewController::class, 'store'])->name('review.store');
Route::post('/review/reply', [ReviewReplyController::class, 'store'])->name('review.reply');
// Products
Route::get('/product-details/{slug}', [ProductController::class, 'index'])->name('product.details');
    Route::get('/category/{name}', [ShopController::class, 'categoryProducts'])->name('category.products');
Route::get('/product/{id}/reviews/summary', [ReviewController::class, 'summary'])->name('product.reviews.summary');

// API Routes


// //Payments
// Route::get('/pay', [PaymentController::class, 'showForm']);
// Route::post('/pay/store', [PaymentController::class, 'initiatePayment']);
// Route::post('/payment/response', [PaymentController::class, 'paymentResponse']);

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/add/wishlsit', [CartController::class, 'addwishlist'])->name('cart.add.wishlsit');
Route::post('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/note', [CartController::class, 'note'])->name('cart.note');
Route::post('/cart/update/{id}', [CartController::class, 'updatecart'])->name('cart.update');
Route::post('/cart/save-note', [CartController::class, 'saveNote'])->name('cart.saveNote');
Route::post('/cart/delete-note', [CartController::class, 'deleteNote'])->name('cart.deleteNote');

// Wishlist
Route::get('/wishlist', [FrontendController::class, 'wishlist'])->name('wishlist');
Route::post('/wishlist/remove/{id}', [FrontendController::class, 'removeWishlist'])->name('wishlist.remove');
Route::post('/wishlist/add', [FrontendController::class, 'addWishlist'])->name('wishlist.add');

// Checkout
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout/place-order', [OrderController::class, 'placeOrder'])->name('checkout.placeOrder');
Route::post('/order/upload-proof', [OrderController::class, 'uploadProof'])->name('order.uploadProof');
Route::post('/checkout/save-total', [OrderController::class, 'saveCartTotal'])->name('checkout.saveTotal');


//payment
Route::get('/payment/alfah', [PaymentController::class, 'showForm']);
Route::post('/payment/alfah/process', [PaymentController::class, 'submitAlfaForm']);
Route::get('/payment/return', [PaymentController::class, 'handleReturn'])->name('alfa.return');
Route::post('/payment/ipn', [PaymentController::class, 'handleIPN']);
Route::post('/pay/alfapay', [PaymentController::class, 'payWithAlfa'])->name('pay.alfapay');
// Route::post('/payment/return', [PaymentController::class, 'handleAlfaReturn'])->name('payment.return');


//shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/tags/by-category', [\App\Http\Controllers\Default\Shop\ShopController::class, 'tagsByCategory'])->name('shop.tags.byCategory');

// Contact
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Newsletter Subscription
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');


//policy
Route::get('{slug}', [PolicyController::class, 'show'])->name('policy.show');



Route::get('get-cart-wishlist-counts', [PolicyController::class, 'wishlist'])->name('get.cart.wishlist.counts');


