<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'content',
    ];

    /**
     * Obtiene el evento
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Obtiene el usuario que escribió el mensaje
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
