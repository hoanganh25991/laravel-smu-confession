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

Route::get('admin', 'AdminController@verifyPost')->name('admin');
Route::get('admin/login', 'SocialLoginController@facebookLogin');
Route::get('admin/facebook-login-callback', 'SocialLoginController@handleProviderCallback');
Route::post('admin', 'AdminController@verifyPost');

Route::get('new-facebook-id', 'SocialLoginController@getNewFacebookId');
Route::get('admin/add', 'AdminController@addAdmin');
Route::post('admin/add', 'AdminController@addAdmin');

Route::get('test/home-ui', function(){
//    return view('layouts.app');
    return view('test.ui-x');
});