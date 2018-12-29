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
}
