<?php

use App\Reply;
use App\User;
use App\Thread;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Reply Factory
|--------------------------------------------------------------------------
*/

$factory->define(Reply::class, function (Faker $faker) {
    return [
        'thread_id' => function () {
            return factory(Thread::class)->create()->id;
        },
        'user_id'   => function () {
            return factory(User::class)->create()->id;
        },
        'body'      => $faker->paragraph
    ];
});
