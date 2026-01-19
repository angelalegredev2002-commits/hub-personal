<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Transaccion extends Model
{
    use HasFactory;

    protected $table = 'transacciones';

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'usuario_id',
        'proyecto_id',
        'monto',
        'tipo', // 'ingreso', 'gasto', 'transferencia'
        'categoria',
        'descripcion',
        'fecha_transaccion',
    ];

    /**
     * Los atributos que deberían ser casteados a tipos nativos.
     */
    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_transaccion' => 'date',
    ];

    // ==========================================================
    // RELACIONES
    // ==========================================================

    // 1. Relación de Pertenencia
    
    // La transacción fue registrada por este usuario
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    // La transacción está vinculada a este proyecto (si aplica)
    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }
    
    // 2. Relación Polimórfica (Registro de Actividad)
    
    // El registro de actividad relacionado con esta transacción
    public function actividad(): MorphMany
    {
        return $this->morphMany(RegistroActividad::class, 'sujeto');
    }
}