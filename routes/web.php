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
    return view('landing');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('compose', 'MessagesController@getCompose')->name('compose');
Route::post('compose', 'MessagesController@postSendMessage')->name('sendMessage');

Route::get('/account', 'AccountController@getSettings');