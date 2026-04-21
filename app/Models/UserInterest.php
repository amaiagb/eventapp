<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserInterest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'interest_name',
    ];

    /**
     * Obtiene el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
