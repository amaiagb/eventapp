<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Obtiene los eventos con este tag
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_tags');
    }
}
