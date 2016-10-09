<?php

Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', [
        'as'    => 'app.waiting-room',
        'uses'  => 'GameController@showWaitingRoom'
    ]);

    Route::get('game/{game_uuid}', [
        'as'    => 'app.game',
        'uses'  => 'GameController@game'
    ]);

    Route::get('challenge/{user}', [
        'as'    => 'app.game.challenge',
        'uses'  => 'GameController@challenge'
    ]);

    Route::get('game/wait/{game_uuid}', [
        'as'    => 'app.game.wait',
        'uses'  => 'GameController@showWaitingChallenge'
    ]);
});

Route::post('user/check', [
    'as'    => 'app.game.userCheck',
    'uses'  => 'GameController@userCheck'
]);