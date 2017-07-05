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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('message','ChatController');





Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('sendmessage','ChatController@SendMessage');
Route::get('/FormReceive/{name}/{NameChannel}','ChatController@getFormReceive');
Route::get('/FormSend/{name}/{NameChannel}','ChatController@getFormsend');