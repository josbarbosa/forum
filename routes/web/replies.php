<?php

Route::post('/replies/{reply}/favorites', [
    'as'   => 'favorite_reply',
    'uses' => 'FavoritesController@store',
]);
