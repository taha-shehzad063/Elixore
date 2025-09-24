<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register the admin routes
        Route::prefix('admin')
            ->middleware('auth')  // Optional: Use middleware for authentication
            ->group(base_path('routes/admin.php'));
    }

    public function register()
    {
        // Any other service registration
    }
}


