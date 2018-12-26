<?php

namespace App\Http\Controllers\Api;

use App\Room;
use App\Http\Controllers\Controller;

class RoomsController extends Controller
{
    public function index()
    {
        $rooms = Room::with('type')->get();

        return response()->json($rooms);
    }

    public function store()
    {
        request()->validate([
            'name' => 'required',
            'type_id' => 'required|exists:room_types,id',
            'available' => 'required'
        ]);

        $room = Room::create(request()->all());

        $room->load('type');

        return response()->json([
            'status' => 'success',
            'message' => 'Room created successfully',
            'room' => $room
        ]);
    }

    public function update(Room $room)
    {
        request()->validate([
            'name' => 'required',
            'type_id' => 'required|exists:room_types,id',
            'available' => 'required'
        ]);

        $room->update(request()->all());

        $room->load('type');

        return response()->json([
            'status'  => 'success',
            'message' => 'Room updated successfully',
            'room'    => $room
        ]);
    }

    public function destroy(Room $room)
    {
        if ($room->available) {
            $room->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Room deleted'
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Can\'t delete! Room is occupied.'
        ], 400);
    }
}
