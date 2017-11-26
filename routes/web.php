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
    if(!isset($_COOKIE['identity']))
        setcookie('identity', uniqid(rand(), true), time() + (10 * 365 * 24 * 60 * 60)); // stores the cookie for 10 year
    return view('map');
});

Route::get('/map/getTwitterFeeds', 'MapController@getTwitterFeeds');
Route::get('/map/getSearchHistory', 'MapController@getSearchHistory');
