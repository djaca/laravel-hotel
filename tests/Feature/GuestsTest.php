<?php

namespace Tests\Feature;

use App\Guest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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

        $this->post('/api/guests', $attributes)->assertJson([
                'status'  => 'success',
                'message' => 'Guest created successfully',
                'guest'   => $attributes
            ]);
    }

    /** @test */
    public function can_update_room()
    {
        $guest = factory(Guest::class)->create();

        $attributes = [
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
            'phone'      => $this->faker->phoneNumber
        ];

        $this->patchJson("/api/guests/{$guest->id}", $attributes)->assertJson([
                'status'  => 'success',
                'message' => 'Guest updated successfully',
                'guest'   => $attributes
            ]);

        $this->assertDatabaseMissing('rooms', [
            'name' => $guest->name
        ]);
    }
}
