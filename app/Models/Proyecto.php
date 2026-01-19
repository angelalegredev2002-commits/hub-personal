<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'usuario_creador_id',
        'nombre',
        'descripcion',
        'estado',
        'prioridad',
        'fecha_inicio_estimada',
        'fecha_limite_estimada',
        'presupuesto_estimado',
        'progreso_porcentaje',
        'es_privado',
    ];

    /**
     * Los atributos que deberían ser casteados a tipos nativos.
     */
    protected $casts = [
        'fecha_inicio_estimada' => 'date',
        'fecha_limite_estimada' => 'date',
        'presupuesto_estimado' => 'decimal:2',
        'es_privado' => 'boolean',
    ];

    // ==========================================================
    // RELACIONES
    // ==========================================================

    // 1. Relación de Pertenencia (Creator)
    // El proyecto pertenece a un usuario (el creador)
    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_creador_id');
    }

    // 2. Relación Muchos a Muchos (Miembros)
    // Los usuarios que participan en este proyecto
    public function miembros(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'proyecto_usuario', 'proyecto_id', 'usuario_id')
                    ->withPivot('rol', 'fecha_ingreso', 'activo')
                    ->withTimestamps();
    }

    // 3. Relaciones Uno a Muchos (Contenido Interno)
    // Las tareas que pertenecen a este proyecto
    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class);
    }

    // Los hitos (metas) de este proyecto
    public function hitos(): HasMany
    {
        return $this->hasMany(Hito::class);
    }

    // Las reuniones relacionadas con este proyecto
    public function reuniones(): HasMany
    {
        return $this->hasMany(Reunion::class);
    }

    // Las transacciones financieras relacionadas
    public function transacciones(): HasMany
    {
        return $this->hasMany(Transaccion::class);
    }
    
    // 4. Relación Polimórfica (Archivos adjuntos)
    // Los archivos que se adjuntan a este proyecto
    public function archivos(): MorphMany
    {
        return $this->morphMany(ArchivoAdjunto::class, 'relacionable');
    }
    
    // 5. Relación Polimórfica (Registro de Actividad)
    // El registro de actividad relacionado con este proyecto
    public function actividad(): MorphMany
    {
        return $this->morphMany(RegistroActividad::class, 'sujeto');
    }
}