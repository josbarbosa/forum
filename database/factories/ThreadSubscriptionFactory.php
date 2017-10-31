<?php

use App\ThreadSubscription;
use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Thread Subscription Factory
|--------------------------------------------------------------------------
*/

$factory->define(ThreadSubscription::class, function (Faker $faker) {

    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'thread_id' => function () {
            return factory(Thread::class)->create()->id;
        }
    ];
});
