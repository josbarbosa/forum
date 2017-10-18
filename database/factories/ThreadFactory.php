<?php

use App\User;
use Faker\Generator as Faker;
use App\Thread;

/*
|--------------------------------------------------------------------------
| Thread Factory
|--------------------------------------------------------------------------
*/

$factory->define(Thread::class, function (Faker $faker) {

    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'title'   => $faker->sentence,
        'body'    => $faker->paragraph,
    ];
});
