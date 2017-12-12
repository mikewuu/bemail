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

// Compose
Route::get('compose', 'MessagesController@getCompose')->name('compose');
Route::post('compose', 'MessagesController@postSendMessage')->name('sendMessage');

// Account
Route::get('/account', 'AccountController@getSettings');
Route::post('/account', 'AccountController@postUpdateSettings');

Route::get('/test', function (\App\Translation\Contracts\Translator $translator) {

//    dd(gmdate('U'));
//    dd(hash_hmac('sha1', "1508572868429", env('GENGO_SECRET')));

    $service = new \Gengo\Service;

    $arrayOfLanguagePairs = json_decode($service->getLanguagePairs('zh'))->response;


    return array_filter($arrayOfLanguagePairs, function ($languagePair) {
        return true;
    });

});

