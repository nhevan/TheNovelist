<?php

use Faker\Generator as Faker;

$factory->define(App\Setting::class, function (Faker $faker) {
    return [
        'key' => $faker->name,
        'value' => $faker->randomDigit
    ];
});
