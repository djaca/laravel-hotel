<?php

namespace App\Http\Controllers\Api;

use App\Guest;
use App\Http\Controllers\Controller;

class GuestsController extends Controller
{
    public function index()
    {
        $guests = Guest::paginate(10);

        return response()->json($guests);
    }

    public function store()
    {
        request()->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'phone'      => 'required'
        ]);

        $guest = Guest::create(request()->all());

        return response()->json([
            'status'  => 'success',
            'message' => 'Guest created successfully',
            'guest'   => $guest
        ]);
    }

    public function show(Guest $guest)
    {
        return response()->json($guest);
    }

    public function update(Guest $guest)
    {
        request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
        ]);

        $guest->update(request()->all());

        return response()->json([
            'status'  => 'success',
            'message' => 'Guest updated successfully',
            'guest'    => $guest
        ]);
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Guest deleted'
        ]);
    }
}
