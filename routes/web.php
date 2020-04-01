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

//系统登录页面路由
Route::get('/login','Application\LoginController@login');
//验证码路由
Route::get('/code/captcha/{tmp}','Application\LoginController@captcha');
