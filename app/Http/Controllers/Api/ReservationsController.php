<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Reservation;

class ReservationsController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('guest', 'rooms')->paginate(10);

        return response()->json($reservations);
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('guest', 'rooms', 'rooms.type');

        return response()->json($reservation);
    }

    public function store()
    {
        request()->validate([
            'guest_id'   => 'required|exists:guests,id',
            'start_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'end_date'   => 'required|date_format:Y-m-d|after:' . request('start_date'),
            'rooms'      => 'required|array'
        ]);

        $reservation = Reservation::create(request()->only('guest_id', 'start_date', 'end_date'));

        $reservation->rooms()->attach(request('rooms'));

        $reservation->load('guest', 'rooms');

        return response()->json([
            'status'      => 'success',
            'message'     => 'Reservation created successfully',
            'reservation' => $reservation
        ]);
    }

    public function update(Reservation $reservation)
    {
        $reservation->rooms()->sync(request('rooms'));

        $reservation->load('guest', 'rooms');

        return response()->json([
            'status'      => 'success',
            'message'     => 'Reservation updated successfully',
            'reservation' => $reservation
        ]);
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
