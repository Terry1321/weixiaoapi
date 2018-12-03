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
Route::post('/workstore','AssignmentController@workStore');
// 图片上传
Route::post('/upload','AssignmentController@upload');
// 获取用户openid
Route::post('/openid','LoginController@openid');
// 用户openid添加至数据库
Route::post('/register','LoginController@register');
//类别
Route::post('/type','WorkInfoController@type');
//班级
Route::post('/group','ClassController@group');
//留言板发布
Route::post('/message','MessageController@message');
//通知发布
Route::post('/notice','NoticeController@notice');
//留言板展示
Route::post('/message/show','MessageController@show');
//作业展示
Route::post('/assignment/show','AssignmentController@show');
// 通知展示
Route::post('/notice/show','NoticeController@show');
//用户名修改
Route::post('/namechange','NameChangeController@nameChange');
//我的信息
Route::post('/myinfo','MyInfoController@myInfo');
//群成员列表
Route::post('/chatparentlist','MyInfoController@chatParentList');
// 获取作业详情图片
Route::post('/picture','AssignmentController@picture');
// 获取学生列表
Route::post('/studentlist','AssignmentController@studentList');
// 创建班级列表
Route::post('/creategroup','ClassController@createGroup');
//班级信息
Route::post('/classdetail','ClassController@classDetail');
//已申请列表
Route::post('/joined','ClassController@joined');
//待审核申请
Route::post('/appliedlist','ClassController@appliedList');
//审核操作
Route::post('/checked','ClassController@checked');
// 获取星星
Route::post('/star','AssignmentController@star');
// 留言板评论
Route::post('/comment','MessageController@comment');
// 留言板评论
Route::post('/join','ClassController@join');
// 作业评价
Route::post('/submited','AssignmentController@submited');
// 通知详情
Route::post('/notice/detail','NoticeController@noticesDetail');
// 退群//解散
Route::post('quit','ClassController@quit');
// // 解散群
// Route::post('disband','ClassController@disband');

