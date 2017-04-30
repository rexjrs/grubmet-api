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

// Users
Route::post('/registernormal', 'UsersController@registerNormal');
Route::post('/registersocial', 'UsersController@registerSocial');
Route::post('/normallogin', 'UsersController@normalLogin');
Route::post('/sociallogin', 'UsersController@socialLogin');
Route::post('/meallog', 'UsersController@mealLog');