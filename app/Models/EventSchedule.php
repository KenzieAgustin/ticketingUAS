<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSchedule extends Model {
    use HasFactory;
    protected $fillable = ['event_id', 'date', 'open_time', 'close_time', 'notes'];

    public function event() {
        return $this->belongsTo(Event::class);
    }
}