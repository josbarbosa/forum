<?php

Route::post('/replies/{reply}/favorites', [
    'as'   => 'favorite_reply',
    'uses' => 'FavoritesController@store',
]);

Route::delete('/replies/{reply}/favorites', [
    'as'   => 'favorite_delete_reply',
    'uses' => 'FavoritesController@destroy',
]);

Route::patch('/replies/{reply}', [
    'as'   => 'patch_reply',
    'uses' => 'RepliesController@update',
]);

Route::delete('/replies/{reply}', [
    'as'   => 'delete_reply',
    'uses' => 'RepliesController@destroy',
]);
