<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['name', 'comment', 'type_id', 'available'];

    protected $casts = [
        'available' => 'Boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(RoomType::class, 'type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function reservations()
    {
        return $this->belongsToMany(Reservation::class);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array                                 $dates
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetAvailableRooms(Builder $query, $dates)
    {
        return $query->whereDoesntHave('reservations', function ($query) use ($dates) {
            $query->whereDate('reservations.start_date', '<', $dates['end_date'])
                  ->whereDate('reservations.end_date', '>', $dates['start_date']);
        });
    }
}
