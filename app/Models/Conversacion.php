<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conversacion extends Model
{
    use HasFactory;

    protected $table = 'conversaciones';

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'nombre',
        'es_grupo',
        'imagen_url', // Sincronizado con la migración
        'creador_id', // Sincronizado con la migración
        'proyecto_id', // Sincronizado con la migración
        'reunion_id', // Sincronizado con la migración
        'ultimo_mensaje_id',
        'ultima_actividad_en',
    ];

    /**
     * Los atributos que deberían ser casteados a tipos nativos.
     */
    protected $casts = [
        'es_grupo' => 'boolean',
        'ultima_actividad_en' => 'datetime',
    ];

    // ==========================================================
    // RELACIONES
    // ==========================================================

    // Una conversación tiene muchos mensajes.
    public function mensajes(): HasMany
    {
        return $this->hasMany(Mensaje::class, 'conversacion_id');
    }
    
    // Relación con el último mensaje (para ordenamiento en la bandeja de entrada).
    public function ultimoMensaje(): BelongsTo
    {
        return $this->belongsTo(Mensaje::class, 'ultimo_mensaje_id');
    }
    
    // Relación con el creador de la conversación.
    public function creador(): BelongsTo
    {
        // Asumiendo que la tabla es 'usuarios' y el modelo es 'Usuario'
        return $this->belongsTo(Usuario::class, 'creador_id');
    }
    
    // Relación con los usuarios participantes (Tabla pivote: conversacion_usuario).
    public function participantes(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'conversacion_usuario', 'conversacion_id', 'usuario_id')
                    ->withPivot('ultimo_visto_en'); 
    }
}
