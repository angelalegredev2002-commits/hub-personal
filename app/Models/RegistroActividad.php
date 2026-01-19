<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RegistroActividad extends Model
{
    use HasFactory;

    protected $table = 'registro_actividad';

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'usuario_id',
        'accion',
        'ip_origen',
        'navegador_cliente',
        'url_origen',
        'datos_anteriores',
    ];

    /**
     * Los atributos que deberían ser casteados a tipos nativos.
     */
    protected $casts = [
        'datos_anteriores' => 'json',
    ];

    // ==========================================================
    // RELACIONES
    // ==========================================================

    // 1. Relación de Pertenencia (El Actor)
    
    // El registro pertenece al usuario que realizó la acción
    public function usuario(): BelongsTo
    {
        // El usuario puede ser nulo si la acción fue del sistema (ej. tarea recurrente)
        return $this->belongsTo(Usuario::class);
    }

    // 2. Relación Polimórfica (El Sujeto Afectado)
    
    /**
     * Obtiene el modelo afectado por la acción (Proyecto, Tarea, Contacto, etc.).
     */
    public function sujeto(): MorphTo
    {
        return $this->morphTo();
    }
}