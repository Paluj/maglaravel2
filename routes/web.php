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
Route::auth();

Route::group(['middleware' => 'magmiddle'], function () {
	// Главная страница
	Route::get('/',['as'=>'MagControllerGet','uses' => 'MagController2@route_tree']);
	Route::get('/admin',['as'=>'MagControllerAdminGet','uses' => 'MagControllerAdmin@route_tree']);

	// Запрос на список товара,
	Route::post('/',['as'=>'MagControllerPost','uses' => 'MagController2@route_post']);
	Route::post('/admin',['as'=>'MagControllerAdminPost','uses' => 'MagController2@route_post']);

	// Запрос на описание товара
	Route::patch('/',['as'=>'MagControllerPatch','uses' => 'MagController2@route_patch']);
	Route::patch('/admin',['as'=>'MagControllerAdminPatch','uses' => 'MagControllerAdmin@route_patch']);

	// Добавить/обновить товар/каталог
	Route::put('/admin',['as'=>'MagControllerPut','uses' => 'MagControllerAdmin@route_put']);

	// Удалить товар/каталог
	Route::delete('/admin',['as'=>'MagControllerDelete','uses' => 'MagControllerAdmin@route_delete']);
});

