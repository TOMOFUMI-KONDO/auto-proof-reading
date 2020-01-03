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

//Route::get('/', function () {
////    return view('welcome');
//});

/**
 * トップページ
 */
Route::get('/', 'AppController@index');
Route::post('/', 'AppController@post');

/**
 * テスト用ページ
 */
if(app('env') === 'local') {
    Route::get('test', 'TestController');
}
