<?php

Route::get('/profiles/{user}', [
    'as'   => 'show_profile',
    'uses' => 'ProfilesController@show',
]);

Route::delete('/profiles/{user}/notifications/{notification}', [
    'as'   => 'notification_profile',
    'uses' => 'UserNotificationsController@destroy',
]);

Route::get('/profiles/{user}/notifications', [
    'as'   => 'show_notifications_profile',
    'uses' => 'UserNotificationsController@index',
]);
