<?php

use Faker\Generator as Faker;

$factory->define(App\Reservation::class, function (Faker $faker) {
    return [
        'guest_id'   => function () {
            return factory(App\Guest::class)->create()->id;
        },
        'start_date' => now()->addDay()->toDateString(),
        'end_date'   => now()->addDays(3)->toDateString(),
        'comment'    => $this->faker->sentence
    ];
});
