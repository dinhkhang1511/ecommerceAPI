<?php

use App\Http\CoUntrollers\LoginController;
use App\Http\Resources\SubCategories;
use App\Http\Resources\SubCategoryCollection;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('')
// * -ROUTE FOR CLIENTS NON LOGIN----------------------------------
Route::get('home','\App\Http\Controllers\api\ProductController@homePage');

Route::get('getProduct','\App\Http\Controllers\api\ShopController@showProduct');
Route::get('getRelatedProduct','\App\Http\Controllers\api\ShopController@showRelatedProduct');
Route::post('checkout','\App\Http\Controllers\api\CheckoutController@checkout');

// * AJAX ROUTE
Route::get('get-colors/{product}/{size}', '\App\Http\Controllers\api\AjaxController@getColor');
Route::get('get-attribute', '\App\Http\Controllers\api\AjaxController@getAttribute');
Route::get('find-promos/{code}', '\App\Http\Controllers\api\AjaxController@findPromos');
Route::get('districts/{province}', '\App\Http\Controllers\api\AjaxController@getDistrict');
Route::get('wards/{district}', '\App\Http\Controllers\api\AjaxController@getWards');
Route::get('paypal-paid', '\App\Http\Controllers\api\AjaxController@paypalPaid');

// * AUTH ROUTE
Route::post('login','\App\Http\Controllers\api\LoginController@login');
Route::post('register','\App\Http\Controllers\api\LoginController@register');
Route::post('authenticate','\App\Http\Controllers\api\LoginController@verifyAuthen');
Route::get('filter', 'ShopController@filter');

// * ROUTE FOR USER HAS LOGIN--------------------------------
Route::middleware('auth.api')->group(function() {
    Route::get('getUser','\App\Http\Controllers\api\UserController@getDetails');
    Route::apiResource('users','\App\Http\Controllers\api\UserController');
    Route::get('users/wishlist/{id}','\App\Http\Controllers\api\UserController@getUserWishlist');
    Route::get('users-month','\App\Http\Controllers\api\UserController@getUserByMonth');
    Route::patch('users/set-admin/{id}','\App\Http\Controllers\api\UserController@setAdmin');

    Route::apiResource('products','\App\Http\Controllers\api\ProductController');
    Route::post('products/{product}','\App\Http\Controllers\api\ProductController@updateProduct');
    Route::get('products-filters', '\App\Http\Controllers\api\ProductController@filters');

    Route::apiResource('tags','\App\Http\Controllers\api\TagController');

    Route::apiResource('categories','\App\Http\Controllers\api\CategoryController');

    Route::apiResource('colors','\App\Http\Controllers\api\ColorController');

    Route::apiResource('sizes','\App\Http\Controllers\api\SizeController');

    Route::apiResource('contacts','\App\Http\Controllers\api\ContactController');

    Route::apiResource('promos','\App\Http\Controllers\api\PromoController');
    Route::get('find-promo','\App\Http\Controllers\api\PromoController@findPromo');
    Route::apiResource('blogs','\App\Http\Controllers\api\BlogController');
    Route::post('blogs/{blog}','\App\Http\Controllers\api\BlogController@updateBlog');

    Route::apiResource('reviews','\App\Http\Controllers\api\ReviewController');

    Route::apiResource('wishlists','\App\Http\Controllers\api\WishlistController');

    Route::apiResource('roles','\App\Http\Controllers\api\RoleController');

    Route::apiResource('orders','\App\Http\Controllers\api\OrderController');
    Route::get('orders-month','\App\Http\Controllers\api\OrderController@getOrderByMonth');
    Route::get('blogs/related/{id}','\App\Http\Controllers\api\BlogController@related');
    Route::apiResource('systemSettings','\App\Http\Controllers\api\SettingController');

    Route::post('check-password','\App\Http\Controllers\api\UserController@checkPassword');
    Route::post('update-password','\App\Http\Controllers\api\UserController@updatePassword');

    Route::post('notifications/mark-all', '\App\Http\Controllers\api\NotificationController');

    Route::prefix('excel')->group(function () {
        Route::get('categories', 'ExportExcelController@categories');
        Route::get('products', 'ExportExcelController@products');
        Route::get('orders', 'ExportExcelController@orders');
    });
});

Route::prefix('locations')->group(function(){
    Route::get('provinces','\App\Http\Controllers\api\LocationController@getProvinces');
    Route::get('districts','\App\Http\Controllers\api\LocationController@getDistricts');
    Route::get('wards','\App\Http\Controllers\api\LocationController@getWards');
});

Route::post('test',function(Request $request){
    Log::info($request->all());
    return response()->json($request->all());
});






