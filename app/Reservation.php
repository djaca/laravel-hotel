<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class)->withTimestamps();
    }
}
