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

Route::get('/', 'MainPageController@showPage')->name('mainPage.showPage');

Auth::routes(['verify' => true]);

Route::get('/logout', 'HomeController@logout')->name('home.logout');
Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');
