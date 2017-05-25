<?php
date_default_timezone_set('Asia/Bangkok');
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
Route::post('/editprofile', 'UsersController@editProfile');
Route::post('/addprofile', 'UsersController@addProfile');
Route::post('/profileimage', 'UsersController@uploadProfilePic');

// Address
Route::post('/newaddress', 'AddressController@newAddress');
Route::post('/getaddress', 'AddressController@getAddress');
Route::post('/editaddress', 'AddressController@editAddress');
Route::post('/deleteaddress', 'AddressController@deleteAddress');

// Alice app
Route::post('/meallog', 'UsersController@mealLog');
Route::post('/getmeals', 'UsersController@getDay');

// Workshop app
Route::post('/workshop', 'UsersController@workshop');
Route::get('/getworkshop', 'UsersController@getWorkshop');