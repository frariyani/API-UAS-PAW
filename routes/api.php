<?php

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

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');
Route::get('verify/{id}', 'Api\AuthController@verify');

Route::group(['middleware' => 'auth:api'], function(){
    // Route::get('product', 'Api\ProductController@index');
    Route::get('barang/{id}', 'Api\BarangController@show');
    Route::post('barang/{id}', 'Api\BarangController@store');
    Route::put('barang/{id}', 'Api\BarangController@update');
    Route::delete('barang/{id}', 'Api\BarangController@destroy');
    Route::get('profile/{id}', 'Api\ProfileController@show');
    Route::put('profile/{id}', 'Api\ProfileController@update');
    Route::put('profile/upload/{id}', 'Api\ProfileController@upload');
    Route::post('logout', 'Api\AuthController@logout');
    Route::get('feed/{id}', 'Api\FeedController@show');
    Route::post('add/{user_id}/{barang_id}', 'Api\CartController@add');
    Route::get('detail/{id}', 'Api\FeedController@showDetail');
    // Route::get('/verify/{token}', 'VerifyController@VerifyEmail')->name('verify');
});