<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Obtiene los usuarios con este rol
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Verifica si este rol es admin
     */
    public function isAdmin(): bool
    {
        return $this->name === 'admin';
    }
}
