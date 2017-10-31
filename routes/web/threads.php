<?php

Route::get('/threads', [
    'as'   => 'threads',
    'uses' => 'ThreadsController@index',
]);

Route::post('/threads', [
    'as'   => 'store_thread',
    'uses' => 'ThreadsController@store',
]);

Route::get('/threads/create', [
    'as'   => 'create_thread',
    'uses' => 'ThreadsController@create',
]);

Route::get('/threads/{channel}', [
    'as'   => 'channel_threads',
    'uses' => 'ThreadsController@index',
]);

Route::get('/threads/{channel}/{thread}', [
    'as'   => 'show_thread',
    'uses' => 'ThreadsController@show',
]);

Route::delete('/threads/{channel}/{thread}', [
    'as'   => 'delete_thread',
    'uses' => 'ThreadsController@destroy',
]);

Route::get('/threads/{channel}/{thread}/replies', [
    'as'   => 'get_replies',
    'uses' => 'RepliesController@index',
]);

Route::post('/threads/{channel}/{thread}/replies', [
    'as'   => 'store_reply',
    'uses' => 'RepliesController@store',
]);

Route::post('/threads/{channel}/{thread}/subscriptions', [
    'as'   => 'subscription_thread',
    'uses' => 'ThreadSubscriptionsController@store',
])->middleware('auth');

Route::delete('/threads/{channel}/{thread}/subscriptions', [
    'as'   => 'subscription_delete_thread',
    'uses' => 'ThreadSubscriptionsController@destroy',
])->middleware('auth');
