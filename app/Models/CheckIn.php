<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckIn extends Model
{
    protected $fillable = [
        'booking_code',
        'ticket_token_id',
        'order_item_id',
        'gate_id',
        'checked_by',
        'method',
        'status',
        'failure_reason',
        'checked_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    public function gate(): BelongsTo
    {
        return $this->belongsTo(Gate::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('checked_at', today());
    }
}
