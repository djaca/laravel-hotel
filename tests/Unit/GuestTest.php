<?php

namespace Tests\Unit;

use App\Guest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GuestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_has_all_fields()
    {
        $attributes = [
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
            'phone'      => $this->faker->email
        ];

        $guest = factory(Guest::class)->create($attributes);

        $this->assertEquals($attributes['first_name'], $guest->first_name);
        $this->assertEquals($attributes['last_name'], $guest->last_name);
        $this->assertEquals($attributes['email'], $guest->email);
        $this->assertEquals($attributes['phone'], $guest->phone);
    }
}
