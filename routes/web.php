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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('', 'PostController@index')->name('post');
Route::post('', 'PostController@post');
Route::get('post-success', 'PostController@postSuccess');

Route::get('facebook-login', 'SocialLoginController@facebookLogin');
//Route::get('redirect-to-facebook', 'SocialLoginController@redirectToProvider');
Route::get('facebook-login-callback', 'SocialLoginController@handleProviderCallback');

Route::get('admin', 'AdminController@verifyPost')->name('admin');
Route::post('admin', 'AdminController@verifyPost');

Route::get('test/home-ui', function(){
//    return view('layouts.app');
    return view('test.ui-x');
});