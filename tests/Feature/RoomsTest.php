<?php

namespace Tests\Feature;

use App\Room;
use App\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function can_add_new_room()
    {
        $type = factory(RoomType::class)->create();

        $attributes = [
            'name'      => $this->faker->word,
            'type_id'   => $type->id,
            'comment'   => $this->faker->sentence,
            'available' => true
        ];

        $this->post('/api/rooms', $attributes)
             ->assertJson([
                'status'  => 'success',
                'message' => 'Room created successfully',
                'room'    => $attributes
            ]);
    }

    /** @test */
    public function can_update_room()
    {
        $type = factory(RoomType::class)->create(['name' => 'This type']);
        $room = factory(Room::class)->create();

        $attributes = [
            'name'      => $this->faker->word,
            'type_id'   => $type->id,
            'comment'   => $this->faker->sentence,
            'available' => false
        ];

        $this->patchJson("/api/rooms/{$room->id}", $attributes)
             ->assertJson([
                'status'  => 'success',
                'message' => 'Room updated successfully',
                'room'    => $attributes
            ]);

        $this->assertDatabaseMissing('rooms', [
            'name' => $room->name
        ]);
    }
}
