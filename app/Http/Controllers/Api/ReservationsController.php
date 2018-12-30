<?php

namespace App\Http\Controllers\Api;

use App\Reservation;
use App\Http\Controllers\Controller;

class ReservationsController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('guest', 'rooms')->paginate(5);

        return response()->json($reservations);
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('guest', 'rooms', 'rooms.type');

        return response()->json($reservation);
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Reservation deleted'
        ]);
    }
}
