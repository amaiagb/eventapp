<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'tag_id',
    ];

    /**
     * Obtiene el evento
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Obtiene el tag
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
