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

//トップページ
Route::get('/', 'AppController@index');
Route::post('/', 'AppController@post');

//校正後の文章をダウンロードするためのコントローラにアクセス
Route::get('download', 'DownloadController@index');
Route::get('download/docx', 'DownloadController@docx');

/**
 * テスト用ページ（ローカルのみ）
 */
if(app('env') === 'local') {
    Route::get('test', 'TestController@index');
    Route::post('test', 'TestController@post');
}
