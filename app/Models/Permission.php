<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // ==========================================================
    // RELACIONES
    // ==========================================================

    /**
     * Un permiso puede pertenecer a muchos roles (Muchos a Muchos).
     * 🟢 CORRECTO: Usando la tabla pivote 'permission_role'.
     */
    public function roles(): BelongsToMany
    {
        // Añadimos las claves foráneas explícitamente (opcional, pero buena práctica)
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id');
    }
}