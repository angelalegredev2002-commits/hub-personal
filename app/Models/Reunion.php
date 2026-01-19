<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Reunion extends Model
{
    use HasFactory;

    protected $table = 'reuniones';

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'usuario_creador_id',
        'proyecto_id',
        'titulo',
        'agenda',
        'estado',
        'fecha_hora_inicio',
        'duracion_minutos',
        'tipo_ubicacion',
        'enlace_videollamada',
        'servicio_video',
        'contraseña_acceso',
        'lugar_fisico',
        'direccion_completa',
        'latitud',
        'longitud',
        'minuta',
        'grabacion_url',
    ];

    /**
     * Los atributos que deberían ser casteados a tipos nativos.
     */
    protected $casts = [
        'fecha_hora_inicio' => 'datetime',
        'latitud' => 'float',
        'longitud' => 'float',
    ];

    // ==========================================================
    // RELACIONES
    // ==========================================================

    // 1. Relación de Pertenencia
    
    // La reunión fue creada por este usuario
    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_creador_id');
    }

    // La reunión pertenece a un proyecto (si aplica)
    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }
    
    // 2. Relaciones Uno a Uno y Uno a Muchos
    
    // La conversación (chat) asociada a esta reunión
    public function conversacion(): HasOne
    {
        return $this->hasOne(Conversacion::class);
    }

    // 3. Relación Muchos a Muchos (Asistentes)
    
    // Los usuarios invitados a la reunión
    public function asistentes(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'reunion_usuario', 'reunion_id', 'usuario_id')
                    ->withPivot('estado_invitacion', 'fecha_respuesta', 'unido_en')
                    ->withTimestamps();
    }
    
    // 4. Relación Polimórfica (Registro de Actividad)
    
    public function actividad(): MorphMany
    {
        return $this->morphMany(RegistroActividad::class, 'sujeto');
    }
}