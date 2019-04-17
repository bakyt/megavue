<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => ['localization', 'throttle:60,1']], function () {
    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('/user', function (Request $request) {
            return new \App\Http\Resources\UserCurrentResource($request->user());
        });
        Route::resource('roles', 'Api\RoleController');
        Route::resource('orders', 'Api\OrderController');
    });
    Route::get('/home', 'Api\HomeController@index');
    Route::resource('products', 'Api\ProductController');
    Route::resource('sections', 'Api\SectionController');
    Route::resource('categories', 'Api\CategoryController');
    Route::resource('stores', 'Api\StoreController');
    Route::resource('store_types', 'Api\StoreTypeController');
    Route::resource('users', 'Api\UserController');
    Route::post('/login', 'Api\AuthController@login');
    Route::put('/reset-password', 'Api\AuthController@resetPassword');
});
