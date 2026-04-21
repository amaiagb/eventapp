<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'follower_id',
        'followed_id',
    ];

    /**
     * Obtiene el usuario que sigue
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * Obtiene el usuario seguido
     */
    public function followed()
    {
        return $this->belongsTo(User::class, 'followed_id');
    }
}
