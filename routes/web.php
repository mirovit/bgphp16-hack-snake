<?php

Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', 'GameController@showWaitingRoom');
    Route::get('/game', 'GameController@game');
    Route::get('/challenge/{user}', 'GameController@challenge');
});

Route::post('user/check', 'GameController@userCheck');