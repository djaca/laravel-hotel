<?php

namespace App\Http\Controllers\Api;

use App\Guest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuestsController extends Controller
{
    public function index()
    {
        $guests = Guest::paginate(10);

        return response()->json($guests);
    }
}
