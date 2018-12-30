<?php

namespace Tests\Feature;

use App\Guest;
use App\Reservation;
use App\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_new_reservation()
    {
        $guest = factory(Guest::class)->create();
        $reservedRooms = factory(Room::class, 2)->create();

        $attributes = [
            'guest_id' => $guest->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'rooms' => $reservedRooms->pluck('id')
        ];

        $this->postJson('/api/reservations', $attributes)
             ->assertJson([
                'status'      => 'success',
                'message'     => 'Room created successfully',
                'reservation' => [
                    'guest_id'   => $guest->id,
                    'start_date' => now()->toDateString(),
                    'end_date'   => now()->addDay()->toDateString(),
                ]
            ]);

        $reservation = Reservation::first();

        $this->assertDatabaseHas('reservation_room', [
            'reservation_id' => $reservation->id,
            'room_id' => $reservedRooms[0]->id
        ]);

        $this->assertDatabaseHas('reservation_room', [
            'reservation_id' => $reservation->id,
            'room_id' => $reservedRooms[1]->id
        ]);
    }
}
