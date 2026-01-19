<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'proyecto_id',
        'hito_id',
        'usuario_creador_id',
        'usuario_asignado_id',
        'titulo',
        'detalle',
        'prioridad',
        'estado',
        'tiempo_estimado_minutos',
        'fecha_vencimiento',
        'completado_en',
        'es_recurrente',
        'regla_recurrencia',
    ];

    /**
     * Los atributos que deberían ser casteados a tipos nativos.
     */
    protected $casts = [
        'fecha_vencimiento' => 'datetime',
        'completado_en' => 'datetime',
        'es_recurrente' => 'boolean',
    ];

    // ==========================================================
    // RELACIONES
    // ==========================================================

    // 1. Relación de Pertenencia (Contenedores)
    
    // La tarea pertenece a un proyecto (o es nula si es personal)
    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }

    // La tarea pertenece a un hito (meta principal)
    public function hito(): BelongsTo
    {
        return $this->belongsTo(Hito::class);
    }

    // 2. Relación de Usuarios
    
    // La tarea fue creada por este usuario
    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_creador_id');
    }
    
    // La tarea está asignada a este usuario
    public function asignado(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_asignado_id');
    }
    
    // 3. Relación Polimórfica (Archivos adjuntos)
    // Los archivos que se adjuntan a esta tarea
    public function archivos(): MorphMany
    {
        return $this->morphMany(ArchivoAdjunto::class, 'relacionable');
    }
    
    // 4. Relación Polimórfica (Registro de Actividad)
    // El registro de actividad relacionado con esta tarea
    public function actividad(): MorphMany
    {
        return $this->morphMany(RegistroActividad::class, 'sujeto');
    }
}