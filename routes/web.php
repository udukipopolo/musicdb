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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('search/music', 'SearchMusicController@index')->name('search.music.index');
Route::get('search/music/{music}', 'SearchMusicController@show')->name('search.music.show');
Route::get('search/artist', 'SearchArtistController@index')->name('search.artist.index');
Route::get('search/artist/{artist}', 'SearchArtistController@show')->name('search.artist.show');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'manage', 'as'=>'manage.'], function () {
        Route::resource('album', 'ManageAlbumController');
        Route::get('album/{album}/music/{music}', 'ManageAlbumController@editMusic')->name('album.music.edit');
        Route::put('album/{album}/music/{music}', 'ManageAlbumController@updateMusic')->name('album.music.update');
        Route::resource('artist', 'ManageArtistController');
    });
});
