<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfiguracionUsuario extends Model
{
    use HasFactory;

    protected $table = 'configuracion_usuario';

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'usuario_id',
        'notificaciones_email',
        'notificaciones_chat',
        'orden_tareas_defecto',
        'tema_ui',
        'dashboard_layout_json',
    ];

    /**
     * Los atributos que deberían ser casteados a tipos nativos.
     */
    protected $casts = [
        'notificaciones_email' => 'boolean',
        'notificaciones_chat' => 'boolean',
        'dashboard_layout_json' => 'array', // Castea el campo JSON a un array de PHP
    ];

    // ==========================================================
    // RELACIONES
    // ==========================================================

    /**
     * Relación Uno a Uno Inversa: Obtiene el usuario propietario de esta configuración.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }
}