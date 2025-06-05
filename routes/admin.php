<?php
// routes/admin.php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Home\BannerImageController;
use App\Http\Controllers\Admin\Home\ProductController;
use App\Http\Controllers\Admin\Pages\BlogController;
use App\Http\Controllers\Admin\Pages\TagController;
use App\Http\Controllers\Admin\Home\CollectionBannerController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Middleware\Admin;



    // Login Routes
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Protected Routes
Route::middleware([Admin::class])->group(function () {
        Route::get('dashboard', function () {
            return view('admin.frontend.dashboard');
        })->name('dashboard');



// Home Banner Images

Route::get('banner-images', [BannerImageController::class, 'index'])->name('banner-images.index'); // GET all
Route::get('banner-images/create', [BannerImageController::class, 'create'])->name('banner-images.create'); // GET form
Route::post('banner-images/store', [BannerImageController::class, 'store'])->name('banner-images.store'); // POST form data
Route::get('banner-images/{id}', [BannerImageController::class, 'show'])->name('banner-images.show'); // GET single banner
Route::get('banner-images/{id}/edit', [BannerImageController::class, 'edit'])->name('banner-images.edit'); // GET edit form
Route::post('banner-images/{id}/update', [BannerImageController::class, 'update'])->name('banner-images.update'); // POST update data
Route::post('banner-images/{id}/delete', [BannerImageController::class, 'destroy'])->name('banner-images.destroy'); // POST delete        


// Home Products

Route::get('products', [ProductController::class, 'index'])->name('products.index'); // GET all
Route::get('products/create', [ProductController::class, 'create'])->name('products.create'); // GET form
Route::post('products/store', [ProductController::class, 'store'])->name('products.store'); // POST form data
Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show'); // GET single banner
Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit'); // GET edit form
Route::post('products/{id}/update', [ProductController::class, 'update'])->name('products.update'); // POST update data
Route::post('products/{id}/delete', [ProductController::class, 'destroy'])->name('products.destroy'); // POST delete        


// Banner Collection
Route::match(['get', 'post'], 'collection-banner', [CollectionBannerController::class, 'index'])->name('collection-banner.store');



// General Setting
Route::match(['get', 'post'], 'general-settings', [GeneralSettingController::class, 'index'])->name('general-settings.store');


// Tags Page
Route::get('tags', [TagController::class, 'index'])->name('tags.index'); // GET all
Route::get('tags/create', [TagController::class, 'create'])->name('tags.create'); // GET form
Route::post('tags/store', [TagController::class, 'store'])->name('tags.store'); // POST form data
Route::get('tags/{id}/edit', [TagController::class, 'edit'])->name('tags.edit'); // GET edit form
Route::post('tags/{id}/update', [TagController::class, 'update'])->name('tags.update'); // POST update data
Route::post('tags/{id}/delete', [TagController::class, 'destroy'])->name('tags.destroy'); // POST delete        

// Blogs Page
Route::get('blog', [BlogController::class, 'index'])->name('blogs.index'); // GET all
Route::get('blog/create', [BlogController::class, 'create'])->name('blogs.create'); // GET form
Route::post('blog/store', [BlogController::class, 'store'])->name('blogs.store'); // POST form data
Route::get('blog/{id}/edit', [BlogController::class, 'edit'])->name('blogs.edit'); // GET edit form
Route::post('blog/{id}/update', [BlogController::class, 'update'])->name('blogs.update'); // POST update data
Route::post('blog/{id}/delete', [BlogController::class, 'destroy'])->name('blogs.destroy'); // POST delete        
Route::post('/blogs/{blog}/comments', [BlogController::class, 'storeComment'])->name('comments.store');

    });


