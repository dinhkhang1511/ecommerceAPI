<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Album;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class LandingPageController extends Controller
{
    public function index(Product $product)
    {
        $bestSellers = Cache::remember('bestSellers', now()->addMinutes(10), function () use ($product) {
            return $product->best_seller;
        });

        $newArrivals = Cache::remember('newArrivals', now()->addMinutes(10), function () use ($product) {
            return $product->new_arrival;
        });

        $hotSales = Cache::remember('hotSales', now()->addMinutes(10), function () use ($product) {
            return $product->hot_sale;
        });

        $blogs = Cache::remember('blogs_limit_3', now()->addMinutes(10), function () {
            return Blog::latest()->limit(3)->get();
        });

        $categories = Cache::remember('categories_limit_3', now()->addMinutes(10), function () {
            return Category::inRandomOrder()->limit(3)->get();
        });
        
        // $bestSellers = $product->best_seller;
        // $newArrivals = $product->new_arrival;
        // $hotSales = $product->hot_sale;
        // $blogs = Blog::latest()->limit(3)->get();
        // $categories = Category::inRandomOrder()->limit(3)->get();
        $album = Album::display()->first();
        return view('frontend.index', compact('bestSellers', 'newArrivals', 'hotSales', 'blogs', 'categories', 'album'));
    }
}
