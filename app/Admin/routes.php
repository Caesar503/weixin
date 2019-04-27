<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    //商品管理
    $router->resource('/goods',GoodsController::class);
    //订单管理
    $router->resource('/order',OrderController::class);
    //用户管理
    $router->resource('/weixin',WxController::class);
    //素材列表
    $router->resource('/suclist',ScController::class);
    //新增素材
    $router->get('/addsuc', 'ObController@add');
    $router->post('/adddo', 'ObController@adddo');

    //群发
    $router->get('/moresend', 'ObController@more_send');
    //群发执行
    $router->post('/senddo', 'ObController@send_do');
});
