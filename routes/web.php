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

Route::get('/analytics.js', 'Collector\PageController@js');

Route::get('/', 'Admin\ProjectController@index');

Route::group(['namespace'=>'Admin'], function() {
    Route::post('/project','ProjectController@add');

    Route::get('/project/{project}/page','PageController@page');
});
