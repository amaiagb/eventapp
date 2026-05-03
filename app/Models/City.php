<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_id',
    ];

    public $timestamps = false;

    /**
     * Relationship with events
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
