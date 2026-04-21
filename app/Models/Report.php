<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'reportable_id',
        'reportable_type',
        'reason',
        'status',
        'resolution_note',
    ];

    /**
     * Obtiene el usuario que reporta
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Relación polimórfica con la entidad reportada
     */
    public function reportable()
    {
        return $this->morphTo();
    }
}
