<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gate extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'code',
        'name',
        'type',
        'stage_id',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'type' => 'string',
    ];

    public function staffAssignments(): HasMany
    {
        return $this->hasMany(StaffAssignment::class);
    }

    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
