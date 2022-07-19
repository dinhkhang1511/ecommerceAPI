<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ViewedProduct;
use Illuminate\Http\Request;
use stdClass;

class ShopController extends Controller
{
    //
    public function showProduct(Request $request)
    {
        if($request->has('product_id'))
        {
            if($product = Product::find($request->product_id))
            {
                ViewedProduct::view($product);

                //xứ lý problems n + 1
                $product->load(['attributes.images', 'reviews.images','reviews.user','attributes.color','attributes.size','subCategory.category']);

                $reviews = $product->reviews;

                //các sizes được lấy phải là duy nhất, tránh lỗi hiện thị các size trùng nhau
                // $sizes = $product->attributes->unique('size_id');
                //lấy colors có quan hệ với thằng product và thằng sizes[0]
                //chọn thằng sizes[0] vì size[0] được chọn mặc định (dùng nó để lọc bớt các color được hiển thị)
                //các colors được lấy phải là duy nhất, tránh lỗi hiện thị các color trùng nhau
                // $colors = $product->attributes->unique('color_id');
                $payload = new stdClass();
                $payload->product = $product;
                $payload->reviews = $reviews;
                // $payload->sizes = $sizes;
                // $payload->colors = $colors;
                return response()->json($payload,200);
            }
        }
        return response('Not found',404);
    }

    public function showRelatedProduct(Request $request)
    {
        if($request->has('product_id'))
        {
            if($product = Product::find($request->product_id))
            {
                $product = Product::find($request->product_id);
                $payload = $product->related;

                return response()->json($payload,200);
            }
        }

        return response('Not found',404);
    }
}
