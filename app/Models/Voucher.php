<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_amount',
        'discount_type',
        'quota',
        'used',
        'expired_at',
        'valid_until',
        'required_points',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function isValid(): bool
    {
        if($this->quota !== null && $this->used >= $this->quota){
            return false;
        }

        if($this->expired_at && $this->expired_at->isPast()){
            return false;
        }
        return true;
    }
}
