<?php

namespace Tests\Unit;

use App\Room;
use App\RoomType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_has_all_fields()
    {
        $attributes = [
            'name'      => $this->faker->randomLetter . $this->faker->numberBetween(1, 50),
            'comment'   => $this->faker->sentence,
            'type_id'   => factory(RoomType::class)->create()->id,
            'available' => true
        ];

        $room = factory(Room::class)->create($attributes);

        $this->assertEquals($attributes['name'], $room->name);
        $this->assertEquals($attributes['comment'], $room->comment);
        $this->assertTrue($room->available);
        $this->assertInstanceOf(RoomType::class, $room->type);
        $this->assertInstanceOf(Collection::class, $room->reservations);
    }
}
