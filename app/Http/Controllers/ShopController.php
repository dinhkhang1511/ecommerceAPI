<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ViewedProduct;
use App\Services\ShopService;
use Illuminate\Support\Facades\Cache;

class ShopController extends Controller
{
    public function index()
    {
        $products = $this->filter();
        $categories = Cache::remember('categories', now()->addMinutes(10), function () {
            return Category::all();
        });
        $subCategories = Cache::remember('subCategories', now()->addMinutes(10), function () {
            return SubCategory::all();
        });
        $sizes = Cache::remember('sizes', now()->addMinutes(10), function () {
            return Size::all();
        });
        $colors = Cache::remember('colors', now()->addMinutes(10), function () {
            return Color::all();
        });
        // $categories = Category::all();
        // $subCategories = SubCategory::all();
        // $sizes = Size::all();
        // $colors = Color::all();
        return view('frontend.shop.index', compact('products', 'categories', 'sizes', 'subCategories', 'colors'));
    }

    public function show(Product $product)
    {
        //thêm vào sản phẩm đã xem cho user đã đăng nhâp
        ViewedProduct::view($product);
        
        //xứ lý problems n + 1
        $product->load(['attributes.images', 'reviews.images']);
        
        $relatedProducts = $product->related;
        $reviews = $product->reviews()->paginate(10);
        $ratingStar = round($reviews->average('rating'));
        
        //các sizes được lấy phải là duy nhất, tránh lỗi hiện thị các size trùng nhau
        $sizes = $product->attributes->unique('size_id');

        //lấy colors có quan hệ với thằng product và thằng sizes[0]
        //chọn thằng sizes[0] vì size[0] được chọn mặc định (dùng nó để lọc bớt các color được hiển thị)
        //các colors được lấy phải là duy nhất, tránh lỗi hiện thị các color trùng nhau
        $colors = $product->attributes()->where('size_id', $sizes[0]->size_id)->get()->unique('color_id');
        return view('frontend.shop.show', compact('product', 'relatedProducts', 'reviews', 'ratingStar', 'colors', 'sizes'));
    }

    //dùng để lọc sản phẩm theo category, giá,...
    public function filter()
    {
        $products = Product::query()->active();
        $shopService = new ShopService($products);
        return $shopService->filter();
    }
}
