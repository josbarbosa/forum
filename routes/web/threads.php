<?php

Route::get('/threads', [
    'as' => 'threads',
    'uses' => 'ThreadsController@index'
]);

Route::get('/threads/{thread}', [
    'as' => 'show_thread',
    'uses' => 'ThreadsController@show'
]);
