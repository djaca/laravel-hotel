<?php

namespace Tests\Unit;

use App\Guest;
use App\Reservation;
use App\Room;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_has_all_fields()
    {
        $attributes = [
            'guest_id'   => factory(Guest::class)->create(),
            'start_date' => now()->toDateString(),
            'end_date'   => now()->addDay()->toDateString(),
            'comment'    => $this->faker->sentence
        ];

        $reservation = factory(Reservation::class)->create($attributes);

        $this->assertEquals($attributes['start_date'], $reservation->start_date);
        $this->assertEquals($attributes['end_date'], $reservation->end_date);
        $this->assertEquals($attributes['comment'], $reservation->comment);
        $this->assertInstanceOf(Guest::class, $reservation->guest);
        $this->assertInstanceOf(Collection::class, $reservation->rooms);
    }
}
