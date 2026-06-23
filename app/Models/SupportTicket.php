<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    protected $fillable = ['user_id', 'order_id', 'subject', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id')->oldest();
    }

    public function latestMessage(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id')->latest()->limit(1);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'open'     => 'Menunggu Balasan',
            'answered' => 'Sudah Dibalas',
            'closed'   => 'Ditutup',
            default    => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open'     => '#e67e22',
            'answered' => '#27ae60',
            'closed'   => '#888',
            default    => '#333',
        };
    }
}