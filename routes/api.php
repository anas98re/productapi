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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'Api\RegisterController@register');
Route::post('login', 'Api\RegisterController@login');

Route::middleware('auth:api')->group( function(){

    Route::get('/product','Api\productController@index');
    Route::post('/product/store','Api\productController@store');
    Route::get('/product/show/{id}','Api\productController@show');
    Route::put('/product/update/{id}','Api\productController@update');
    Route::delete('/product/destroy/{id}','Api\productController@destroy');
    Route::delete('/product/destroyMyAccount/{id}','Api\productController@destroyMyAccount');


    Route::post('/product/storeOfComment/{id}','Api\productController@storeOfComment');

    Route::get('/product/user/{id}','Api\productController@userProduct');
    Route::get('/product/sorted/{attribute}','Api\productController@sortedProduct');

    Route::get('/product/searchByName/{req}','Api\productController@searchByName');
    Route::get('/product/searchByCategory/{req}','Api\productController@searchByCategory');
    Route::get('/product/searchByExpirationDate/{req}','Api\productController@searchByExpirationDate');

    // Route::get('product/islikedbyme/{id}', 'Api\productController@isLikedByMe');
    // Route::post('product/like', 'Api\productController@like');
    Route::post('product/storeOfLikes/{id}', 'Api\productController@storeOfLikes');

    //  Route::resource('product', 'Api\productController');
}) ;
//any route within the group must be logged in

