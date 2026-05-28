<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'event_id',
        'order_id',
        'rating',
        'title',
        'body',
        'is_approved',
        'status',
        'rejected_reason',
        'approved_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeForEvent($query, $event_id)
    {
        return $query->where('event_id', $event_id);
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->is_approved) {
            return $this->user ? $this->user->name : 'Anonymous';
        }
        return $this->user ? $this->user->name ?? 'unknown' : 'Anonymous';
    }
}
