<?php

Route::get('/profiles/{user}', [
    'as'   => 'show_profile',
    'uses' => 'ProfilesController@show',
]);
