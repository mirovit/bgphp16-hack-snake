<?php

Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', 'GameController@showWaitingRoom');
    Route::get('/game/{me}/{challenged}', 'GameController@game');
    Route::get('/challenge/{user}', 'GameController@challenge');
    Route::get('/challenge/wait/{me}/{challenged}', 'GameController@showWaitingChallenge');
});

Route::post('user/check', 'GameController@userCheck');