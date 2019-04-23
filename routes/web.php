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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//购物车列表
Route::get('cart', 'CartController@cartlist');
//购物车添加
Route::get('add/cart/{id?}', 'CartController@addcart');
//生成订单
Route::get('add/order', 'OrderController@index');
//订单列表
Route::get('order/list', 'OrderController@list');
//微信支付
Route::get('weixin/pay/{id}', 'WxPayController@pay');


//查询支付状态
Route::get('find/pay/{id}', 'WxPayController@findpay');
//支付成功
Route::get('/pay/success/{id}', 'WxPayController@success');
//回调
Route::post('/Weixin/pay_notify', 'WxPayController@pay_notify');


//浏览量
Route::get('/goods/history/{id?}', 'GoodsController@history');
//浏览量排行
Route::get('/goods/getscore', 'GoodsController@getscore');


//jssdk
Route::get('/weixin/jssdk', 'WxJController@test');