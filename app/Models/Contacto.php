<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Contacto extends Model
{
    use HasFactory;

    protected $table = 'contactos';

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'usuario_propietario_id',
        'nombre_completo',
        'email',
        'organizacion',
        'cargo_puesto',
        'sitio_web',
        'telefono_principal',
        'segundo_telefono',
        'direccion_empresa',
        'relacion',
        'es_proveedor',
        'es_cliente',
        'fecha_ultima_interaccion',
        'notas_privadas',
    ];

    /**
     * Los atributos que deberían ser casteados a tipos nativos.
     */
    protected $casts = [
        'es_proveedor' => 'boolean',
        'es_cliente' => 'boolean',
        'fecha_ultima_interaccion' => 'datetime',
    ];

    // ==========================================================
    // RELACIONES
    // ==========================================================

    // 1. Relación de Pertenencia (Propiedad)
    
    // El contacto pertenece al usuario que lo registró
    public function propietario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_propietario_id');
    }
    
    // 2. Relación Polimórfica (Registro de Actividad)
    
    // El registro de actividad relacionado con este contacto
    public function actividad(): MorphMany
    {
        return $this->morphMany(RegistroActividad::class, 'sujeto');
    }
}