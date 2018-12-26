<?php

namespace App\Http\Controllers\Api;

use App\RoomType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomTypesController extends Controller
{
    public function index()
    {
        $types = RoomType::all();

        return response()->json($types);
    }

    public function store()
    {
        request()->validate([
            'name'        => 'required',
            'description' => 'required',
            'capacity'    => 'required',
            'price'       => 'required|numeric',
        ]);

        $type = RoomType::create(request()->all());

        return response()->json([
            'status'  => 'success',
            'message' => 'Room type created successfully',
            'type'    => $type
        ]);
    }
}
