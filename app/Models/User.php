<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use \Nnjeim\World\Models\City;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'name',
        'surname',
        'bio',
        'profile_image',
        'role_id',
        'city_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    #region RELACIONES ----------------------------------

    /**
     * Obtiene el rol del user
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Método que verifica si el user es admin
     */
    public function isAdmin()
    {
        return $this->role && $this->role->name === 'admin';
    }

    /**
     * Obtiene la ciudad del user
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    #region EVENTOS
    /**
     * Obtiene los eventos creados por el user
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Obtiene los asistentes a eventos del user
     */
    public function eventAttendees()
    {
        return $this->hasMany(EventAttendee::class);
    }

    /**
     * Obtiene los eventos a los que el user asiste
     */
    public function attendedEvents()
    {
        return $this->belongsToMany(Event::class, 'event_attendees')
            ->withPivot('status', 'registered_at')
            ->withTimestamps();
    }
    #endregion

    #region USUARIOS
    /**
     * Obtiene los seguidores del user
     */
    public function followers()
    {
        return $this->hasMany(Follow::class, 'followed_id');
    }

    /**
     * Obtiene los usuarios que sigue el user
     */
    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    #endregion
    #endregion
}
