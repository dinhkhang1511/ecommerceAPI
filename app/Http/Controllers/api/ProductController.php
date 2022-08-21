<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Resources\ProductResource;
use App\Models\Album;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Services\ImageServices;
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
        $query = Product::query();
        $limit = request('limit', 10);

        return ProductResource::collection($query->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        $product = Product::create($request->validated());
        $attributes = $product->attributes()->createMany(ProductAttribute::getData());
        ProductImage::storeItem($attributes);

        return success('Created Successful', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        if ($id === 'find') {
            // $arrId = explode(',',$request->product_id); // Dùng đề test postman
            if($request->has('product_id') && is_array($request->product_id))
            {
                $arrId =$request->product_id;
                $products = Product::find($arrId)->load('attributes.images', 'attributes.size', 'attributes.color');
                $payload = new stdClass();
                $payload->products = $products;
                return response()->json($payload);
            }
        } else {
            $product = Product::find($id);
            if ($product) {
                return new ProductResource($product->load(['orders', 'sizes', 'colors', 'allattributes.images']));
            }

            return error('Product Not Found', 404);
        }
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, Product $product)
    // {
    //     $product->update($request->all());
    //     $product->sizes()->detach();
    //     $product->attributes()->createMany(ProductAttribute::getData());
    //     ProductImage::updateItem($product->attributes);
    //     return success($product);
    // }

    public function updateProduct(ProductStoreRequest $request,Product $product)
    {
        $product->update($request->all());
        $product->sizes()->detach();
        $product->attributes()->createMany(ProductAttribute::getData());
        ProductImage::updateItem($product->attributes);
        return success($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        ImageServices::deleteImages($product);
        $product->delete();
        return success('products.index');
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
            return Category::inRandomOrder()->limit(3)->where('category_id', null)->get();
        });

        // $album = Album::display()->first()->load('images');

        $payload = new stdClass();
        $payload->bestSellers = $bestSellers;
        $payload->newArrivals = $newArrivals;
        $payload->hotSales = $hotSales;
        $payload->blogs = $blogs;
        $payload->categories = $categories;
        // $payload->album = $album;
        return response()->json($payload);


        // $bestSellers = $product->best_seller;
        // $newArrivals = $product->new_arrival;
        // $hotSales = $product->hot_sale;
        // $blogs = Blog::latest()->limit(3)->get();
        // $categories = Category::inRandomOrder()->limit(3)->get();
    }

    public function filters(Request $request)
    {
        $product = new Product();
        if($request->has('filters'))
        {
            $filters = $request->filters;
            $data = new stdClass();
            if(in_array('bestSellers', $filters))
                $data->bestSellers = $product->best_seller;
            if(in_array('topFavourite', $filters))
                $data->topFavourite = $product->top_favourite;
            if(in_array('newArrivals', $filters))
                $data->newArrivals = $product->new_arrival;
            if(in_array('hotSales', $filters))
                $data->hotSales = $product->hot_sale;

            return success($data);
        }
    }
}
