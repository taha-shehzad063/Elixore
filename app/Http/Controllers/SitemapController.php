<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = $this->generateSitemap();
        
        return response($sitemap, 200)
            ->header('Content-Type', 'text/xml');
    }
    
    private function generateSitemap(): string
    {
        $baseUrl = 'https://roshnipk.store/';
        $now = Carbon::now()->toDateString();
        
        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Homepage
        $xml[] = $this->addUrl($baseUrl, $now, 'daily', '1.0');
        
        // Shop page
        $xml[] = $this->addUrl($baseUrl . 'shop', $now, 'daily', '0.9');
        
        // Categories
        $categories = Category::where('status', 1)->get();
        foreach ($categories as $category) {
            $xml[] = $this->addUrl(
                $baseUrl . 'category/' . $category->slug,
                $category->updated_at->toDateString(),
                'weekly',
                '0.8'
            );
        }
        
        // Products
        $products = Product::where('status', 1)->get();
        foreach ($products as $product) {
            $xml[] = $this->addUrl(
                $baseUrl . 'product/' . $product->slug,
                $product->updated_at->toDateString(),
                'weekly',
                '0.7'
            );
        }
        
        // Static pages
        $staticPages = ['about', 'contact', 'privacy-policy', 'terms-conditions'];
        foreach ($staticPages as $page) {
            $xml[] = $this->addUrl($baseUrl . $page, $now, 'monthly', '0.6');
        }
        
        $xml[] = '</urlset>';
        
        return implode(PHP_EOL, $xml);
    }
    
    private function addUrl(string $loc, string $lastmod, string $changefreq, string $priority): string
    {
        return "
        <url>
            <loc>" . htmlspecialchars($loc) . "</loc>
            <lastmod>{$lastmod}</lastmod>
            <changefreq>{$changefreq}</changefreq>
            <priority>{$priority}</priority>
        </url>";
    }
}