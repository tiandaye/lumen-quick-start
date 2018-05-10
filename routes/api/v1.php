<?php

/**
 * @Author: tianwangchong
 * @Date:   2018-05-07 14:21:08
 * @Last Modified by:   tianwangchong
 * @Last Modified time: 2018-05-08 15:18:23
 */

use Illuminate\Http\Request;

$router->group(['middleware' => 'cors', 'prefix' => 'api', 'namespace' => 'Api\V1'], function () use ($router) {
    $router->get('/lwj', function () use ($router) {
        return 'lwj';
    });

    /**
     * 登录
     */
    // 登录认证
    $router->post('/login', 'AuthController@login');
    // 刷新token
    $router->post('/refresh_token', 'AuthController@refreshToken');
    // // 发送-短信快捷登录验证码短信
    // $router->get('/send_login_sms', 'Api\V1\SendSmsController@sendLoginSms');
    // // 短信快捷登录-认证
    // $router->post('/sms_login', 'Api\V1\AuthController@smsLogin');
    // // 找回密码/修改密码
    // $router->post('/password/reset', 'Api\V1\PassWordController@reset');
    // // 注册
    // $router->post('/register', 'Api\V1\RegisterController@postRegister');
    // // 发送-注册验证码短信
    // $router->post('/sms', 'Api\V1\SendSmsController@sendSms');
});

// dingo
$api = app('Dingo\Api\Routing\Router');
// v1 version API
// add in header    Accept:application/vnd.lumen.v1+json
$api->version('v1', [
    'namespace'  => 'App\Http\Controllers\Api\V1',
    'middleware' => [
        'cors',
        'auth:api',
        // 'auth'
        //'api.throttle'
    ],
    // each route have a limit of 100 of 1 minutes
    // ['middleware' => ['api.throttle'], 'limit' => 100, 'expires' => 1, 'uses' => '']
], function ($api) {
    /**
     * 路由例子
     */
    $api->get('/thc', function (Request $request) {
        return $request->user();
    });

    // User
    $api->post('users', [
        'as' => 'users.store',
        'uses' => 'UserController@store',
    ]);
    // user list
    $api->get('users', [
        'as' => 'users.index',
        'uses' => 'UserController@index',
    ]);
    // user detail
    $api->get('users/{id}', [
        'as' => 'users.show',
        'uses' => 'UserController@show',
    ]);
});
