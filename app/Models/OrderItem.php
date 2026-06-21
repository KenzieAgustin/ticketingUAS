<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TicketZone;

class OrderItem extends Model
{
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function ticketZone()
    {
        return $this->belongsTo(TicketZone::class);
    }

    public function tokens()
    {
        return $this->hasMany(TicketToken::class, 'order_item_id');
    }
}
