<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Hito extends Model
{
    use HasFactory;

    protected $table = 'hitos';

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'proyecto_id',
        'usuario_responsable_id',
        'titulo',
        'descripcion',
        'fecha_meta',
        'estado',
        'progreso_porcentaje',
    ];

    /**
     * Los atributos que deberían ser casteados a tipos nativos.
     */
    protected $casts = [
        'fecha_meta' => 'date',
    ];

    // ==========================================================
    // RELACIONES
    // ==========================================================

    // 1. Relación de Pertenencia
    
    // El hito pertenece a un proyecto
    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }

    // El hito es responsabilidad principal de este usuario
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_responsable_id');
    }

    // 2. Relación Uno a Muchos
    
    // Las tareas que contribuyen a este hito
    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class);
    }
    
    // 3. Relación Polimórfica (Registro de Actividad)
    // El registro de actividad relacionado con este hito
    public function actividad(): MorphMany
    {
        return $this->morphMany(RegistroActividad::class, 'sujeto');
    }
}