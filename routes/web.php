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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('faq', 'HomeController@faq')->name('home.faq');

// 楽曲検索
Route::get('search/music', 'SearchMusicController@index')->name('search.music.index');
Route::get('search/music/{music}', 'SearchMusicController@show')->name('search.music.show');
Route::get('search/album/{album}', 'SearchMusicController@album')->name('search.album.show');

// アーティスト検索
Route::get('search/artist', 'SearchArtistController@index')->name('search.artist.index');
Route::get('search/artist/{artist}', 'SearchArtistController@show')->name('search.artist.show');
Route::get('search/album_artist/{artist}', 'SearchArtistController@showAlbumArtist')->name('search.album_artist.show');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'manage', 'as'=>'manage.'], function () {
        // アルバム管理
        Route::resource('album', 'ManageAlbumController');
        Route::get('album/{album}/music/{music}', 'ManageAlbumController@editMusic')->name('album.music.edit');
        Route::put('album/{album}/music/{music}', 'ManageAlbumController@updateMusic')->name('album.music.update');
        Route::post('album/create/phg', 'ManageAlbumController@createFromPhg')->name('album.create.phg');

        // アーティスト管理
        Route::resource('artist', 'ManageArtistController');

        // 一括登録
        Route::get('bulk/regist', 'ManageBulkRegistrationController@index')->name('bulk.regist.index');
        Route::post('bulk/regist/csv', 'ManageBulkRegistrationController@csv')->name('bulk.regist.csv');
        Route::post('bulk/regist/gss', 'ManageBulkRegistrationController@googlespreadsheet')->name('bulk.regist.gss');
    });

    // プロフィール
    Route::get('profile', 'ProfileController@index')->name('profile.index');
    Route::get('profile/edit', 'ProfileController@edit')->name('profile.edit');
    Route::put('profile/edit', 'ProfileController@update')->name('profile.update');

    Route::group(['middleware' => 'check.role:admin'], function() {
        // ユーザ管理
        Route::resource('user', 'UserController')->except(['create', 'store']);
    });
});
