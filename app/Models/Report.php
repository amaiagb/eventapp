<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

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

    /**
     * Obtiene el nombre del tipo de elemento reportado
     */
    public function getReportableTypeNameAttribute()
    {
        return class_basename($this->reportable_type);
    }

    /**
     * Obtiene la información formateada del elemento reportado
     */
    public function getReportableDisplayInfoAttribute()
    {
        if (!$this->reportable) {
            return 'N/A';
        }

        switch ($this->reportable_type) {
            case 'App\Models\Event':
                return "Evento: " . Str::limit($this->reportable->title ?? 'N/A', 30);
            case 'App\Models\User':
                return "Usuario: " . ($this->reportable->username ?? 'N/A');
            default:
                return 'N/A';
        }
    }

    /**
     * Obtiene el estado formateado con etiqueta
     */
    public function getStatusLabelAttribute()
    {
        return $this->status === 'reviewed' ? 'Rechazado' : ucfirst($this->status);
    }

    /**
     * Obtiene la clase CSS para el badge de estado
     */
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'bg-warning';
            case 'resolved':
                return 'bg-success';
            case 'reviewed':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    /**
     * Obtiene las opciones de tipos reportables para filtros
     */
    public static function getReportableTypes()
    {
        return [
            'App\Models\Event' => 'Eventos',
            'App\Models\User' => 'Usuarios',
        ];
    }
}
