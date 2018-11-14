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
    return view('content/content');
});

// Rutas para categorias
Route::get('/category', 'CategoryController@index');
Route::post('/category/store', 'CategoryController@store');
Route::put('/category/update', 'CategoryController@update');
Route::put('/category/deactivate', 'CategoryController@deactivate');
Route::put('/category/activate', 'CategoryController@activate');
Route::get('/category/getCategories', 'CategoryController@getCategories');

// Rutas para articulos
Route::get('/article', 'ArticleController@index');
Route::post('/article/store', 'ArticleController@store');
Route::put('/article/update', 'ArticleController@update');
Route::put('/article/deactivate', 'ArticleController@deactivate');
Route::put('/article/activate', 'ArticleController@activate');

// Rutas para personas
Route::get('/client', 'ClientController@index');
Route::post('/client/store', 'ClientController@store');
Route::put('/client/update', 'ClientController@update');
Route::put('/client/deactivate', 'ClientController@deactivate');
Route::put('/client/activate', 'ClientController@activate');

// Rutas para proveedores
Route::get('/provider', 'ProviderController@index');
Route::post('/provider/store', 'ProviderController@store');
Route::put('/provider/update', 'ProviderController@update');
Route::put('/provider/deactivate', 'ProviderController@deactivate');
Route::put('/provider/activate', 'ProviderController@activate');

// Rutas para Roles
Route::get('/rol', 'RolController@index');
Route::get('/rol/selectRol', 'RolController@selectRol');
Route::post('/rol/store', 'RolController@store');
Route::put('/rol/update', 'RolController@update');
Route::put('/rol/deactivate', 'RolController@deactivate');
Route::put('/rol/activate', 'RolController@activate');

// Rutas para usuarios
Route::get('/user', 'UserController@index');
Route::post('/user/store', 'UserController@store');
Route::put('/user/update', 'UserController@update');
Route::put('/user/deactivate', 'UserController@deactivate');
Route::put('/user/activate', 'UserController@activate');