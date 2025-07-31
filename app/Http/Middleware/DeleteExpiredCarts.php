<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Models\CartItem;
use Illuminate\Console\Command;

class DeleteExpiredCarts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

  public function handle($request, Closure $next)
{
    // Cache key to track the last cleanup
    $cacheKey = 'last_cart_items_cleanup';

    // Only run if 45 minutes have passed
    if (!Cache::has($cacheKey) || now()->diffInMinutes(Cache::get($cacheKey)) >= 45) {
        // Perform cleanup
        $deleted = CartItem::where('created_at', '<', now()->subMinutes(45))->delete();

        // Store current timestamp in cache for 45 minutes
        Cache::put($cacheKey, now(), now()->addMinutes(45));

    }

    return $next($request);
}

}
