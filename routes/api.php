<?php

use App\Http\CoUntrollers\LoginController;
use App\Models\Product;
use Illuminate\Http\Request;
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

Route::get('getUser','\App\Http\Controllers\api\UserController@getDetails');
Route::apiResource('users','\App\Http\Controllers\api\UserController');
Route::apiResource('posts','\App\Http\Controllers\api\PostController');




