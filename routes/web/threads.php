<?php

Route::get('/threads', [
    'as' => 'threads',
    'uses' => 'ThreadsController@index'
]);

Route::post('/threads', [
    'as' => 'store_thread',
    'uses' => 'ThreadsController@store'
]);

Route::get('/threads/{thread}', [
    'as' => 'show_thread',
    'uses' => 'ThreadsController@show'
]);

Route::post('/threads/{thread}/replies', [
    'as' => 'store_reply',
    'uses' => 'RepliesController@store'
]);
