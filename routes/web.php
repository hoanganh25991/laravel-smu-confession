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

//Route::get('home', 'HomeController@index')->name('home');
Route::get('home', 'PostController@index')->name('home');


Route::get('facebook-login', 'SocialLoginController@facebookLogin');
Route::get('redirect-to-facebook', 'SocialLoginController@redirectToProvider');
Route::get('facebook-login-callback', 'SocialLoginController@handleProviderCallback');

Route::get('admin', 'AdminController@verifyPost')->name('admin');

Route::get('post', 'PostController@index');
Route::post('post', 'PostController@post');