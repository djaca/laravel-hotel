<?php

use Faker\Generator as Faker;

$factory->define(App\Guest::class, function (Faker $faker) {
    return [
        'first_name' => $this->faker->firstName,
        'last_name'  => $this->faker->lastName,
        'email'      => $this->faker->email,
        'phone'      => $this->faker->email
    ];
});
