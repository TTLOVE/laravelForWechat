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

//Route::get('/', function () {
    //return view('welcome');
//});

Route::auth();

Route::group(['middleware' => 'auth', 'namespace' => 'Admin', 'prefix' => 'admin'], function() {
    Route::get('/', 'HomeController@index');
    Route::resource('article', 'ArticleController');
    Route::post('article/send', 'ArticleController@send');
    Route::post('article/pageGet', 'ArticleController@pageGet');
});

Route::get('/', 'HomeController@index');

Route::get('forMailQQ', 'HomeController@sendMailForQQ');
Route::get('forMail163', 'HomeController@sendMailFor163');

Route::resource('article', 'ArticleController');

Route::resource('post', 'PostController');

Route::get('home/{name}', 'HomeController@home');

Route::controller('request','RequestController');

