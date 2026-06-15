<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketZone extends Model
{
    protected $fillable = [
        'ticket_id',
        'zone_name',
        'price',
        'quota_total',
        'quota_remaining',
    ];

    //relasi ke ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    //relasi ke wait_lists
    public function waitLists()
    {
        return $this->hasMany(WaitList::class);
    }

    public function isAvailable()
    {
        return $this->quota_remaining > 0;
    }
}
