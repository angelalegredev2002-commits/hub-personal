<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Solo BelongsToMany es necesario

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'nombre',
        'nivel',
        'descripcion',
    ];

    protected $casts = [
        'nivel' => 'integer',
    ];

    // ==========================================================
    // RELACIONES
    // ==========================================================

    /**
     * Un rol tiene muchos usuarios (Muchos a Muchos).
     * 🟢 CORREGIDO: Usando la tabla pivote 'role_usuario'.
     */
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'role_usuario', 'role_id', 'usuario_id');
    }
    
    /**
     * Un rol tiene muchos permisos (Muchos a Muchos).
     * 🟢 CORRECTO: Usando la tabla pivote 'permission_role'.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }
}