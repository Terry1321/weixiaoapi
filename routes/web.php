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
//�����ҵ��Ϣ
Route::post('/assignment','AssignmentController@workStore');
// ͼƬ�ϴ�
Route::post('/upload','AssignmentController@upload');
// ��ȡ�û�openid
Route::get('/openid','LoginController@openid');
// �û�openid��������ݿ�
Route::post('/register','LoginController@register');
//���
Route::post('/type','WorkInfoController@type');
//�༶
Route::post('/group','WorkInfoController@group');
//���԰巢��
Route::post('/message','MessageController@message');
//֪ͨ����
Route::post('/notice','NoticeController@notice');
//���԰�չʾ
Route::post('/message/show','MessageController@show');
//��ҵչʾ
Route::post('/assignment/show','AssignmentController@show');
//��ҵ����
Route::post('/workdetail','AssignmentController@workDetail');
//�û����޸�
Route::post('/namechange','NameChangeController@nameChange');
//�ҵ���Ϣ
Route::post('/myinfo','MyInfoController@myInfo');