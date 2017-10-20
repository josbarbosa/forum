<?php

use App\Channel;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Channel Factory
|--------------------------------------------------------------------------
*/

$factory->define(Channel::class, function (Faker $faker) {

    $name = $faker->word;
    $slug = Str::slug($name);

    return [
        'name' => $name,
        'slug' => $slug
    ];
});
