<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'city_id',
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'cover_image',
        'max_attendees',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
        'max_attendees' => 'integer',
    ];

    /**
     * Obtiene la imagen de portada, retorna default.png si es null
     */
    public function getCoverImageAttribute($value)
    {
        return $value ?? 'default.png';
    }

    /**
     * Obtiene la URL completa de la imagen de portada
     */
    public function getCoverImageUrlAttribute()
    {
        return asset('storage/img/events/' . $this->cover_image);
    }

    /**
     * Obtiene el usuario creador del evento
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene la categoría del evento
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Obtiene la ciudad del evento
     */
    public function city()
    {
        return $this->belongsTo(\Nnjeim\World\Models\City::class, 'city_id');
    }

    /**
     * Obtiene los asistentes al evento
     */
    public function attendees()
    {
        return $this->hasMany(EventAttendee::class);
    }

    /**
     * Obtiene los tags del evento
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'event_tags');
    }
}
