<?php

use App\Http\CoUntrollers\LoginController;
use App\Http\Resources\SubCategories;
use App\Http\Resources\SubCategoryCollection;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

Route::get('home','\App\Http\Controllers\api\ProductController@homePage');

Route::get('getProduct','\App\Http\Controllers\api\ShopController@showProduct');
Route::get('getRelatedProduct','\App\Http\Controllers\api\ShopController@showRelatedProduct');


Route::post('login','\App\Http\Controllers\api\LoginController@login');
Route::post('authenticate','\App\Http\Controllers\api\LoginController@verifyAuthen');

Route::get('filter', 'ShopController@filter');

Route::get('getUser','\App\Http\Controllers\api\UserController@getDetails');

Route::apiResource('users','\App\Http\Controllers\api\UserController');
Route::get('users/wishlist/{id}','\App\Http\Controllers\api\UserController@getUserWishlist');

Route::apiResource('products','\App\Http\Controllers\api\ProductController');

Route::apiResource('categories','\App\Http\Controllers\api\CategoryController');

Route::apiResource('colors','\App\Http\Controllers\api\ColorController');

Route::apiResource('sizes','\App\Http\Controllers\api\SizeController');

Route::apiResource('contacts','\App\Http\Controllers\api\ContactController');

Route::apiResource('promos','\App\Http\Controllers\api\PromoController');

Route::apiResource('blogs','\App\Http\Controllers\api\BlogController');
Route::get('blogs/related/{id}','\App\Http\Controllers\api\BlogController@related');

Route::apiResource('systemSettings','\App\Http\Controllers\api\SettingController');





