<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffAssignment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'gate_id',
        'event_id',
        'assignment_date',
        'shift',
        'shift_start',
        'shift_end',
        'status',
        'notes',
    ];

    protected $casts = [
        'assignment_date' => 'datetime',
        'shift_start' => 'string',
        'shift_end' => 'string',
    ];

    public function gate(): BelongsTo
    {
        return $this->belongsTo(Gate::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForDate($query, string $date)
    {
        return $query->where('assignment_date', $date);
    }

    public function scopeForEvent($query, $event_id)
    {
        return $query->where('event_id', $event_id);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
