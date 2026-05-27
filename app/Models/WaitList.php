<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaitList extends Model
{
    protected $fillable = [
        'user_id',
        'ticket_zone_id',
        'status',
    ];

    public function ticketZone()
    {
        return $this->belongsTo(TicketZone::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
