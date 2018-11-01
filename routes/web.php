<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
//添加作业信息
Route::post('/assignment','AssignmentController@workStore');
// 图片上传
Route::post('/upload','AssignmentController@upload');
// 获取用户openid
Route::get('/openid','LoginController@openid');
// 用户openid添加至数据库
Route::post('/register','LoginController@register');
//类别
Route::post('/type','WorkInfoController@type');
//班级
Route::post('/group','WorkInfoController@group');
//留言板发布
Route::post('/message','MessageController@message');
//通知发布
Route::post('/notice','NoticeController@notice');
//留言板展示
Route::post('/message/show','MessageController@show');
//作业展示
Route::post('/assignment/show','AssignmentController@show');
//作业详情
Route::post('/workdetail','AssignmentController@workDetail');
//用户名修改
Route::post('/namechange','NameChangeController@nameChange');
//我的信息
Route::post('/myinfo','MyInfoController@myInfo');