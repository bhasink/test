<?php


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => ['auth', 'instagram']], function(){

    Route::get('/', 'AppController@index');

    Route::get('/search', 'AppController@search');

    Route::get('/instagram', 'InstagramController@redirectToInstagramProvider');

    Route::get('/instagram/callback', 'InstagramController@handleProviderInstagramCallback');
});
