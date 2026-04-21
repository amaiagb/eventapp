<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'sender_id',
        'receiver_id',
        'content',
        'is_read',
    ];

    /**
     * Obtiene el evento
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Obtiene el remitente
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Obtiene el destinatario
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
