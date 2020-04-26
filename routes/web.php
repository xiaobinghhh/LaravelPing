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

    //评分项页面路由
    Route::get('course/{course}/basis', 'BasisController@index');
    //评分项列表路由
    Route::get('course/{course}/basis/list', 'BasisController@list');
    //评分项添加路由
    Route::any('course/{course}/basis/add', 'BasisController@add');
    //评分项编辑路由
    Route::post('course/{course}/basis/edit', 'BasisController@edit');
    //评分项删除路由
    Route::delete('course/{course}/basis/{basis}', 'BasisController@delete');

    //签到评分页面
    Route::get('course/{course}/signment_ping', 'SignmentController@ping');
    //签到列表内容
    Route::get('course/{course}/signment_list', 'SignmentController@list');
    //签到列表表头
    Route::get('course/{course}/signment_columns', 'SignmentController@columns');
    //新增签到
    Route::any('course/{course}/signment_add', 'SignmentController@add');
    //签到图表
    Route::get('course/{course}/signment_chart', 'ChartController@signment');
    //签到图表数据
    Route::post('course/{course}/signment_chart_data', 'ChartController@signment_chart_data');
    //签到修改
    Route::post('course/{course}/signment_edit', 'SignmentController@edit');
    //签到依据页面（文件）
    Route::get('course/{course}/signment_file', 'SignmentController@file');
    // 添加如下路由
    //上传文件
    Route::post('course/{course}/signment/upload/file', 'SignmentController@uploadFile');
    //删除文件
    Route::delete('course/{course}/signment/upload/file', 'SignmentController@deleteFile');
    //创建目录
    Route::post('course/{course}/signment/upload/folder', 'SignmentController@createFolder');
    //删除目录
    Route::delete('course/{course}/signment/upload/folder', 'SignmentController@deleteFolder');


    //作业列表
    Route::resource('course/{course}/homework', 'HomeworkController');
    //作业评分
    Route::get('course/{course}/homework_ping', 'HomeworkController@ping');
    //作业图表
    Route::get('course/{course}/homework_chart', 'ChartController@homework');
    //作业图表数据
    Route::post('course/{course}/homework_chart_data', 'ChartController@homework_chart_data');
    //作业评分表头
    Route::get('course/{course}/homework_ping_columns', 'HomeworkController@columns');
    //作业评分列表内容
    Route::get('course/{course}/homework_ping_list', 'HomeworkController@list');
    //学生作业下载
    Route::get('course/{course}/teacher/homework/download', 'HomeworkController@download');
    //作业评分编辑
    Route::post('course/{course}/homework_ping_edit', 'HomeworkController@ping_edit');
    //作业文件页面（文件）
    Route::get('course/{course}/homework_file', 'HomeworkController@file');
    // 添加如下路由
    //作业文件的上传从其他入口操作，以下路由为文件管理
    //上传文件
    Route::post('course/{course}/homework/upload/file', 'HomeworkController@uploadFile');
    //删除文件
    Route::delete('course/{course}/homework/upload/file', 'HomeworkController@deleteFile');
    //创建目录
    Route::post('course/{course}/homework/upload/folder', 'HomeworkController@createFolder');
    //删除目录
    Route::delete('course/{course}/homework/upload/folder', 'HomeworkController@deleteFolder');


    //报告列表
    Route::resource('course/{course}/report', 'ReportController');
    //报告评分
    Route::get('course/{course}/report_ping', 'ReportController@ping');
    //报告评分表头
    Route::get('course/{course}/report_ping_columns', 'ReportController@columns');
    //评分列表内容
    Route::get('course/{course}/report_ping_list', 'ReportController@list');
    //学生报告下载
    Route::get('course/{course}/teacher/report/download', 'ReportController@download');
    //报告图表
    Route::get('course/{course}/report_chart', 'ChartController@report');
    //作业图表数据
    Route::post('course/{course}/report_chart_data', 'ChartController@report_chart_data');
    //报告评分编辑
    Route::post('course/{course}/report_ping_edit', 'ReportController@ping_edit');
    //报告文件页面（文件）
    Route::get('course/{course}/report_file', 'ReportController@file');
    // 添加如下路由
    //报告文件的上传从其他入口操作，以下路由为文件管理
    //上传文件
    Route::post('course/{course}/report/upload/file', 'ReportController@uploadFile');
    //删除文件
    Route::delete('course/{course}/report/upload/file', 'ReportController@deleteFile');
    //创建目录
    Route::post('course/{course}/report/upload/folder', 'ReportController@createFolder');
    //删除目录
    Route::delete('course/{course}/report/upload/folder', 'ReportController@deleteFolder');


    //期末考试页面
    Route::get('course/{course}/final_exam_ping', 'FinalExamController@index');
    //期末考试成绩表
    Route::get('course/{course}/final_exam_list', 'FinalExamController@list');
    //期末考试成绩编辑
    Route::post('course/{course}/final_exam_edit', 'FinalExamController@edit');
    //期末考试图表
    Route::get('course/{course}/final_exam_chart', 'ChartController@final_exam');
    //期末考试图表数据
    Route::post('course/{course}/final_exam_chart_data', 'ChartController@final_exam_chart_data');
    //期末考试试卷页面
    Route::get('course/{course}/final_exam_file', 'FinalExamController@file');
    //上传文件
    Route::post('course/{course}/final_exam/upload/file', 'FinalExamController@uploadFile');
    //删除文件
    Route::delete('course/{course}/final_exam/upload/file', 'FinalExamController@deleteFile');
    //创建目录
    Route::post('course/{course}/final_exam/upload/folder', 'FinalExamController@createFolder');
    //删除目录
    Route::delete('course/{course}/final_exam/upload/folder', 'FinalExamController@deleteFolder');
});

//学生界面中间件路由
Route::group(['middleware' => ['web', 'user.login'], 'prefix' => 'student', 'namespace' => 'Student'], function () {
    //学生-首页
    Route::get('index', 'IndexController@index');
    //学生-欢迎页
    Route::get('welcome', 'IndexController@welcome');
    //退出登录路由
    Route::get('logout', 'IndexController@logout');
    //修改密码路由
    Route::any('changePass', 'IndexController@changePass');

    //课程-首页
    Route::get('course/{course}', 'IndexController@course');
    //课程-欢迎页
    Route::get('course/{course}/welcome', 'CourseController@welcome');

    //我的作业-页面
    Route::get('course/{course}/homework', 'HomeworkController@index');
    //我的作业-修改
    Route::any('course/{course}/homework/{homework}/edit', 'HomeworkController@edit');
    //我的作业-提交
    Route::any('course/{course}/homework/{homework}/commit', 'HomeworkController@commit');
    //作业下载路由
    Route::get('homework/download', 'HomeworkController@download');

    //我的报告-页面
    Route::get('course/{course}/report', 'ReportController@index');
    //我的报告-修改
    Route::any('course/{course}/report/{report}/edit', 'ReportController@edit');
    //我的报告-提交
    Route::any('course/{course}/report/{report}/commit', 'ReportController@commit');
    //报告下载路由
    Route::get('report/download', 'ReportController@download');

});
