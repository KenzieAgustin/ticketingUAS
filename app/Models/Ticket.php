<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'event_id',
        'ticket_type',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    //relasi ke ticket_zones
    public function zones()
    {
        return $this->hasMany(TicketZone::class);
    }

    //relasi ke pricing_rules
    public function pricingRules()
    {
        return $this->hasMany(PricingRule::class);
    }
}
