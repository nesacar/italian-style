<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->resource('users', 'UsersController');
Route::middleware('auth:api')->post('users/{id}/image', 'UsersController@uploadImage');

Route::middleware('auth:api')->get('categories/lists', 'CategoriesController@lists');
Route::middleware('auth:api')->resource('categories', 'CategoriesController');
Route::middleware('auth:api')->post('categories/{id}/image', 'CategoriesController@uploadImage');
Route::middleware('auth:api')->post('categories/{id}/lang', 'CategoriesController@updateLang');

Route::middleware('auth:api')->resource('posts', 'PostsController');
Route::middleware('auth:api')->post('posts/{id}/image', 'PostsController@uploadImage');
Route::middleware('auth:api')->post('posts/{id}/lang', 'PostsController@updateLang');
Route::middleware('auth:api')->post('posts/{id}/gallery', 'PostsController@galleryUpdate');
Route::middleware('auth:api')->get('posts/{id}/gallery', 'PostsController@gallery');

Route::middleware('auth:api')->post('photos/{id}/destroy', 'PhotosController@destroy');

Route::middleware('auth:api')->get('settings/{id}/edit', 'SettingsController@edit');
Route::middleware('auth:api')->patch('settings/{id}/update', 'SettingsController@update');
Route::middleware('auth:api')->post('settings/{id}/updateLang', 'SettingsController@updateLang');

Route::middleware('auth:api')->resource('themes', 'ThemesController');
Route::middleware('auth:api')->post('themes/{id}/image', 'ThemesController@uploadImage');

Route::middleware('auth:api')->resource('menus', 'MenusController');

Route::middleware('auth:api')->resource('menu-links', 'MenuLinksController');