<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomTypeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function can_add_new_room_type()
    {
        $attributes = [
            'name'        => $this->faker->words(2, true),
            'description' => $this->faker->sentence,
            'capacity'    => 2,
            'price'       => 45
        ];

        $this->post('/api/room-types', $attributes)
             ->assertJson([
                'status'  => 'success',
                'message' => 'Room type created successfully',
                'type'    => $attributes
            ]);
    }
}
