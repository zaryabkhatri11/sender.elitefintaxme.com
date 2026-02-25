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

Route::get('/test-push', 'HomeController@testPush');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/* add user device token */
Route::post('/add-user-device', 'Admin\UserController@addUserDevice')->name('add-user-device');

