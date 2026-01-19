<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mensaje extends Model
{
    use HasFactory;

    protected $table = 'mensajes';

    protected $fillable = [
        'conversacion_id',
        'usuario_emisor_id',
        'contenido',
        'tipo_mensaje',
        'editado_en',
    ];

    protected $casts = [
        'editado_en' => 'datetime',
        'created_at' => 'datetime', 
    ];

    // ==========================================================
    // RELACIONES
    // ==========================================================

    public function conversacion(): BelongsTo
    {
        return $this->belongsTo(Conversacion::class);
    }
    
    public function emisor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_emisor_id');
    }

    // ==========================================================
    // SOLUCIÓN AL ERROR DE FORMATO ESTILÍSTICO 💥
    // ==========================================================
    
    /**
     * Convierte los saltos de línea (\n) del texto plano en etiquetas <br />
     * y sanitiza el contenido antes de enviarlo al frontend.
     */
    protected function getContenidoAttribute(string $value): string
    {
        // 1. Sanitizar el valor (escapar cualquier HTML malicioso)
        $sanitizedValue = e($value); 
        
        // 2. Convertir saltos de línea a <br /> (SOLUCIÓN DEL FORMATO)
        return nl2br($sanitizedValue);
    }
}