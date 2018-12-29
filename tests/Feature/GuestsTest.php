<?php

namespace Tests\Feature;

use App\Guest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuestsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function can_add_new_room()
    {
        $attributes = [
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
            'phone'      => $this->faker->phoneNumber
        ];

        $this->post('/api/guests', $attributes)
             ->assertJson([
                 'status'  => 'success',
                 'message' => 'Guest created successfully',
                 'guest'    => $attributes
             ]);
    }
}
