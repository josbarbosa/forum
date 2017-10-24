<?php

Route::post('/replies/{reply}/favorites', [
    'as'   => 'favorite_reply',
    'uses' => 'FavoritesController@store',
]);

Route::delete('/replies/{reply}', [
    'as'   => 'delete_reply',
    'uses' => 'RepliesController@destroy',
]);
