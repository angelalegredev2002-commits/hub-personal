<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ArchivoAdjunto extends Model
{
    use HasFactory;

    protected $table = 'archivos_adjuntos';

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'usuario_subidor_id',
        'nombre',
        'nombre_servidor',
        'ruta_almacenamiento',
        'tipo_mime',
        'tamaño_bytes',
        'hash_sha256',
        'es_publico',
        'escaneo_virus_ok',
    ];

    /**
     * Los atributos que deberían ser casteados a tipos nativos.
     */
    protected $casts = [
        'tamaño_bytes' => 'integer',
        'es_publico' => 'boolean',
        'escaneo_virus_ok' => 'boolean',
    ];

    // ==========================================================
    // RELACIONES
    // ==========================================================

    // 1. Relación de Pertenencia
    
    // El archivo fue subido por este usuario
    public function subidor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_subidor_id');
    }

    // 2. Relación Polimórfica (El Objeto al que se adjunta)
    
    /**
     * Obtiene el modelo padre (Projecto, Tarea, Mensaje, etc.) al que pertenece el archivo.
     */
    public function relacionable(): MorphTo
    {
        return $this->morphTo();
    }
}