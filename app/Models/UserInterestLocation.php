<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserInterestLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Obtiene el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene la ciudad (de nnjeim/world)
     */
    public function city()
    {
        return $this->belongsTo(\Nnjeim\World\Models\City::class, 'city_id');
    }
}
