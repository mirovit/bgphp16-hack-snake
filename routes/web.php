<?php

Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', 'GameController@showWaitingRoom');
    Route::get('/game/{challenger}/{challenged}', 'GameController@game');
    Route::get('/challenge/{user}', 'GameController@challenge');
    Route::get('/challenge/wait/{challenger}/{challenged}', 'GameController@showWaitingChallenge');
});

Route::post('user/check', 'GameController@userCheck');