<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'TasksController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::post('/tasks/create', 'TasksController@store');
Route::post('/tasks/complete', 'TasksController@complete');
Route::post('/tasks/delete', 'TasksController@destroy');
Route::post('/tasks/show', 'TasksController@show');
Route::post('/tasks/update', 'TasksController@update');
