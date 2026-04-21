<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventAttendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    /**
     * Obtiene el evento al que asiste el usuario
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Obtiene el usuario asistente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
