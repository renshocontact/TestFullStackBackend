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

Route::pattern('quantity', '[0-9]+');
Route::pattern('page', '[0-9]+');
Route::pattern('lang', '[a-z]+');

Route::group(['middleware' => ['cors']], function () {
        Route::group(['prefix' => '{lang}'], function () {
            //para usuarios estandar, app movil de terreno
            Route::group(['prefix' => 'users'], function () {
                Route::get('{quantity}/{page}', 'Users\UserController@paginate');
                Route::get('{id}', 'Users\UserController@get');
                Route::post('/', 'Users\UserController@insert');
                Route::put('{id}', 'Users\UserController@update');
                Route::delete('/{id}', 'Users\UserController@delete');
            });
        });
    
});
