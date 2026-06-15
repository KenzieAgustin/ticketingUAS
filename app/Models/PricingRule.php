<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingRule extends Model
{
    protected $fillable = [
        'ticket_id',
        'rule_name',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    //relasi ke ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
