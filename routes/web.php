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

Route::get('/', 'Web\AppController@getApp')
    ->middleware('auth'); //本应用只允许认证用户才能访问，所以我们在入口路由上使用了 auth 中间件。

Route::get('/login', 'Web\AppController@getLogin' )
    ->name('login')
    ->middleware('guest'); //guest中间件的用途是登录用户访问该路由会跳转到指定认证后页面，而非登录用户访问才会显示登录页面。

Route::get( '/auth/{social}', 'Web\AuthenticationController@getSocialRedirect' )
    ->middleware('guest');

Route::get( '/auth/{social}/callback', 'Web\AuthenticationController@getSocialCallback' )
    ->middleware('guest');

//经纬度
Route::get('geocode', function () {
    return \App\Utilities\GaodeMaps::geocodeAddress('天城路1号', '杭州', '浙江');
});