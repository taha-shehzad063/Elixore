<?php
// routes/admin.php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Home\BannerImageController;
use App\Http\Controllers\Admin\Home\ProductController;
use App\Http\Controllers\Admin\Pages\BlogController;
use App\Http\Controllers\Admin\Pages\TagController;
use App\Http\Controllers\Admin\Pages\PolicyController;
use App\Http\Controllers\Default\ReviewController;
use App\Http\Controllers\Default\ReviewReplyController;
use App\Http\Controllers\Admin\Home\CollectionBannerController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\Admin;
use App\Http\Controllers\Admin\Components\CategoyController;
use App\Http\Controllers\Admin\Components\SubCategoyController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\Order\OrderController;

    // Login Routes
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    // Protected Routes
    
    
    
    Route::middleware([Admin::class])->group(function () {
          Route::get('/revenue-data', [AdminController::class, 'getRevenueData']);

            Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard'); // GET all
// Home Banner Images

Route::get('banner-images', [BannerImageController::class, 'index'])->name('banner-images.index'); // GET all
Route::get('banner-images/create', [BannerImageController::class, 'create'])->name('banner-images.create'); // GET form
Route::post('banner-images/store', [BannerImageController::class, 'store'])->name('banner-images.store'); // POST form data
Route::get('banner-images/{id}', [BannerImageController::class, 'show'])->name('banner-images.show'); // GET single banner
Route::get('banner-images/{id}/edit', [BannerImageController::class, 'edit'])->name('banner-images.edit'); // GET edit form
Route::post('banner-images/{id}/update', [BannerImageController::class, 'update'])->name('banner-images.update'); // POST update data
Route::post('banner-images/{id}/delete', [BannerImageController::class, 'destroy'])->name('banner-images.destroy'); // POST delete        


// Home Products
Route::get('subcategories/by-category/{category_id}', [ProductController::class, 'getSubcategories'])->name('subcategories.byCategory');
Route::get('products', [ProductController::class, 'index'])->name('products.index'); // GET all
Route::get('products/create', [ProductController::class, 'create'])->name('products.create'); // GET form
Route::post('products/store', [ProductController::class, 'store'])->name('products.store'); // POST form data
Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show'); // GET single banner
Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit'); // GET edit form
Route::put('products/{id}/update', [ProductController::class, 'update'])->name('products.update'); // POST update data
Route::post('products/{id}/delete', [ProductController::class, 'destroy'])->name('products.destroy'); // POST delete        
Route::get('products-gallery/{id}/delete', [ProductController::class, 'deleteGalleryImage'])->name('products.deleteGalleryImage'); // POST delete        
Route::get('/categories/{category}/subcategories', [ProductController::class, 'getSubcategories'])->name('categories.subcategories');
Route::get('/products/{product}/reviews', [ProductController::class, 'showReviews'])
    ->name('products.reviews');
    Route::get('/orders/{order}/tracking', [OrderController::class, 'tracking'])->name('orders.tracking');
Route::post('/orders/{order}/tracking', [OrderController::class, 'updateTracking'])->name('orders.tracking.update');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])
    ->name('reviews.destroy');
    
Route::delete('/review-replies/{reply}', [ReviewReplyController::class, 'destroy'])
    ->name('review-replies.destroy');

// Banner Collection
Route::match(['get', 'post'], 'collection-banner', [CollectionBannerController::class, 'index'])->name('collection-banner.store');



// General Setting
Route::match(['get', 'post'], 'general-settings', [GeneralSettingController::class, 'index'])->name('general-settings.store');


// Tags Page
Route::get('tags', [TagController::class, 'index'])->name('tags.index'); // GET all
Route::get('tags/create', [TagController::class, 'create'])->name('tags.create'); // GET form
Route::post('tags/store', [TagController::class, 'store'])->name('tags.store'); // POST form data
Route::get('tags/{id}/edit', [TagController::class, 'edit'])->name('tags.edit'); // GET edit form
Route::put('tags/{id}', [TagController::class, 'update'])->name('tags.update');
Route::post('tags/{id}/delete', [TagController::class, 'destroy'])->name('tags.destroy'); // POST delete        

// Blogs Page
Route::get('blog', [BlogController::class, 'index'])->name('blogs.index'); // GET all
Route::get('blog/create', [BlogController::class, 'create'])->name('blogs.create'); // GET form
Route::post('blog/store', [BlogController::class, 'store'])->name('blogs.store'); // POST form data
Route::get('blog/{id}/edit', [BlogController::class, 'edit'])->name('blogs.edit'); // GET edit form
Route::post('blog/{id}/update', [BlogController::class, 'update'])->name('blogs.update'); // POST update data
Route::post('blog/{id}/delete', [BlogController::class, 'destroy'])->name('blogs.destroy'); // POST delete        
Route::post('/blogs/{blog}/comments', [BlogController::class, 'storeComment'])->name('comments.store');
Route::get('/blogs/{blog}/comments', [BlogController::class, 'showComments'])
    ->name('blogs.comments');
    
Route::delete('/comments/{comment}', [BlogController::class, 'destroyblog'])
    ->name('comments.destroy');


// Categrory

Route::get('categories', [CategoyController::class, 'index'])->name('categories.index'); // GET all
Route::get('categories/create', [CategoyController::class, 'create'])->name('categories.create'); // GET form
Route::post('categories/store', [CategoyController::class, 'store'])->name('categories.store'); // POST form data
Route::get('categories/{id}/edit', [CategoyController::class, 'edit'])->name('categories.edit'); // GET edit form
Route::post('categories/{id}/update', [CategoyController::class, 'update'])->name('categories.update'); // POST update data
Route::post('categories/{id}/delete', [CategoyController::class, 'destroy'])->name('categories.destroy'); // POST delete        


Route::get('subcategories', [SubCategoyController::class, 'index'])->name('subcategories.index'); // GET all
Route::get('subcategories/create', [SubCategoyController::class, 'create'])->name('subcategories.create'); // GET form
Route::post('subcategories/store', [SubCategoyController::class, 'store'])->name('subcategories.store'); // POST form data
Route::get('subcategories/{id}/edit', [SubCategoyController::class, 'edit'])->name('subcategories.edit'); // GET edit form
Route::post('subcategories/{id}/update', [SubCategoyController::class, 'update'])->name('subcategories.update'); // POST update data
Route::post('subcategories/{id}/delete', [SubCategoyController::class, 'destroy'])->name('subcategories.destroy'); // POST delete        

// Policy

Route::get('policy', [PolicyController::class, 'index'])->name('policy.index'); // GET all
Route::get('policy/create', [PolicyController::class, 'create'])->name('policy.create'); // GET form
Route::post('policy/store', [PolicyController::class, 'store'])->name('policy.store'); // POST form data
Route::get('policy/{id}/edit', [PolicyController::class, 'edit'])->name('policy.edit'); // GET edit form
Route::post('policy/{id}/update', [PolicyController::class, 'update'])->name('policy.update'); // POST update data
Route::post('policy/{id}/delete', [PolicyController::class, 'destroy'])->name('policy.destroy'); // POST delete        



 Route::get('checkout-options', [OrderController::class, 'index'])->name('checkout-options.index');
    Route::get('checkout-options/create', [OrderController::class, 'create'])->name('checkout-options.create');
    Route::post('checkout-options/store', [OrderController::class, 'store'])->name('checkout-options.store');
    Route::get('checkout-options/{id}/edit', [OrderController::class, 'edit'])->name('checkout-options.edit');
    
    // Update using POST instead of PUT/PATCH
    Route::post('checkout-options/{id}/update', [OrderController::class, 'update'])->name('checkout-options.update');
    
    // Delete using POST instead of DELETE
    Route::post('checkout-options/{id}/delete', [OrderController::class, 'destroy'])->name('checkout-options.destroy');
 Route::resource('contact-messages', ContactMessageController::class)
        ->only(['index', 'show', 'destroy']);

     Route::get('orders', [OrderController::class, 'orders'])->name('orders');
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });


