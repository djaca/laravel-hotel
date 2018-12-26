<?php

use Faker\Generator as Faker;

$factory->define(App\Room::class, function (Faker $faker) {
    return [
        'name' => $faker->words(2, true),
        'comment' => $faker->sentence,
        'type_id' => function () {
            return factory(\App\RoomType::class)->create()->id;
        },
        'available' => true
    ];
});
