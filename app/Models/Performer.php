<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performer extends Model {
    use HasFactory;
    protected $fillable = ['name', 'bio', 'photo', 'genre', 'event_id'];

    public function event() {
        return $this->belongsTo(Event::class);
    }
}