<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Album;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use stdClass;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($param,Request $request)
    {
        if($param === 'find')
        {
            // $arrId = explode(',',$request->product_id); // Dùng đề test postman
            $arrId =$request->product_id;
            $products = Product::find($arrId)->load('attributes.images','attributes.size','attributes.color');
            $payload = new stdClass();
            $payload->products = $products;
            return response()->json($payload);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function homePage()
    {
        $product = new Product();
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

        $album = Album::display()->first()->load('images');

        $payload = new stdClass();
        $payload->bestSellers = $bestSellers;
        $payload->newArrivals = $newArrivals;
        $payload->hotSales = $hotSales;
        $payload->blogs = $blogs;
        $payload->categories = $categories;
        $payload->album = $album;

        return json_encode($payload);


        // $bestSellers = $product->best_seller;
        // $newArrivals = $product->new_arrival;
        // $hotSales = $product->hot_sale;
        // $blogs = Blog::latest()->limit(3)->get();
        // $categories = Category::inRandomOrder()->limit(3)->get();

    }
}
