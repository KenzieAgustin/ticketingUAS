<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'date_start', 'date_end',
        'capacity_total', 'event_category_id', 'stage_id', 'status', 'slug'
    ];

    public function category() {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }
    public function stage() {
        return $this->belongsTo(Stage::class);
    }
    public function performers() {
        return $this->hasMany(Performer::class);
    }
    public function schedules() {
        return $this->hasMany(EventSchedule::class);
    }
    public function media() {
        return $this->hasMany(EventMedia::class);
    }
}