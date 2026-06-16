<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketToken extends Model
{
    protected $fillable = [
        'order_item_id',
        'booking_code',
        'qr_code_path',
        'status',
    ];

    //nanti bisa ditambah relasi ke order item
     public function orderItem()
    {
         return $this->belongsTo(OrderItem::class);
    }
}