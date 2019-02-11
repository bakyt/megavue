<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*  Login and logout routes. Authentication is ready.
    After login you will get token from cookie for
    axios header authentication bearer. */
Route::get('/login', 'Auth\LoginController@apiLogin')->name('login');

// This middleware means that only authenticated user has access to this routes
//Route::group(['middleware' => ['auth_web_api']], function () {
    // After login automatically redirects to homepage
    Route::get('/', 'HomeController@index')->name('home');
    // For vue routing
    Route::get('/{any}', 'HomeController@index')->where('any', '.*');
//});

// @info: Please don't change this routes. Use vue-router for frontend . Thanks!
