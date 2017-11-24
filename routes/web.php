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
        setcookie('identity', md5($_SERVER['REMOTE_ADDR']), time() + 86000, '/', 'localhost'); // user identity in the cookie. expires after 1 day
    return view('map');
});

Route::get('/map/getTwitterFeeds', 'MapController@getTwitterFeeds');
Route::get('/map/getSearchHistory', 'MapController@getSearchHistory');
