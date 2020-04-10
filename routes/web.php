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
Route::get('/login', 'Application\LoginController@login');
//验证码路由
Route::get('/code/captcha/{tmp}', 'Application\LoginController@captcha');
//系统登录验证路由
Route::any('/login/doLogin', 'Application\LoginController@doLogin');

//学生界面中间件路由
Route::group(['middleware' => ['web', 'user.login'], 'prefix' => 'student', 'namespace' => 'Student'], function () {
    //学生--首页
    Route::get('index', 'IndexController@index');
    //退出登录路由
    Route::get('logout', 'IndexController@logout');
    //修改密码路由
    Route::any('changePass', 'IndexController@changePass');
});


//教师界面中间件路由
Route::group(['middleware' => ['web', 'user.login'], 'prefix' => 'teacher', 'namespace' => 'Teacher'], function () {
    //教师-首页
    Route::get('index', 'IndexController@index');
    //教师-欢迎页
    Route::get('welcome', 'IndexController@welcome');
    //退出登录路由
    Route::get('logout', 'IndexController@logout');
    //修改密码路由
    Route::any('changePass', 'IndexController@changePass');

});

//课程评分系统界面路由
Route::group(['middleware' => ['web', 'user.login'], 'namespace' => 'Teacher'], function () {
    //课程-首页
    Route::get('course/{course}', 'IndexController@course');
    //课程-欢迎页
    Route::get('course/{course}/welcome', 'CourseController@welcome');

    //签到评分页面
    Route::get('course/{course}/signment_ping', 'SignmentController@ping');
    //签到列表内容
    Route::get('course/{course}/signment_list', 'SignmentController@list');
    //签到列表表头
    Route::get('course/{course}/signment_columns', 'SignmentController@columns');
    //新增签到
    Route::any('course/{course}/signment_add', 'SignmentController@add');
    //签到修改
    Route::post('course/{course}/signment_edit', 'SignmentController@edit');
    //签到依据页面（文件）
    Route::get('course/{course}/signment_file', 'SignmentController@file');
    // 添加如下路由
    Route::post('course/{course}/signment/upload/file', 'SignmentController@uploadFile');
    Route::delete('course/{course}/signment/upload/file', 'SignmentController@deleteFile');
    Route::post('course/{course}/signment/upload/folder', 'SignmentController@createFolder');
    Route::delete('course/{course}/signment/upload/folder', 'SignmentController@deleteFolder');
});
