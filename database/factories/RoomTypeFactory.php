<?php

use Faker\Generator as Faker;

$factory->define(App\RoomType::class, function (Faker $faker) {
    return [
        'name'        => $faker->words(2, true),
        'description' => $faker->sentence,
        'capacity'    => $faker->numberBetween(1, 5),
        'price'       => $faker->randomFloat(2, 100, 600)
    ];
});
