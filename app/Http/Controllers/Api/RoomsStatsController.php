<?php

namespace App\Http\Controllers\Api;

use App\Room;
use App\Http\Controllers\Controller;

class RoomsStatsController extends Controller
{
    public function index()
    {
        $bookedToday = Room::select(\DB::raw('count(*) as count'))
                           ->whereHas('reservations', function ($query) {
                               $query->whereDate('reservations.created_at', now()->toDateString());
                           })
                           ->first()
                           ->count;

        $pendingRooms = Room::select(\DB::raw('count(*) as count'))
                            ->whereHas('reservations', function ($query) {
                                $query->whereDate('reservations.start_date', now()->toDateString());
                            })
                            ->first()
                            ->count;

        return response()->json([
            'bookedToday'  => $bookedToday,
            'pendingRooms' => $pendingRooms,
        ]);
    }
}
