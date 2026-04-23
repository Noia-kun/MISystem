<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomSchedule extends Model
{
    protected $fillable = [
        'room_name',
        'requester_name',
        'purpose',
        'material',
        'scheduled_at',
        'time_from',
        'time_to',
        'level',
        'department',
        'approved_at',
    ];
}
