<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios'; 
    
    public function getAuthPassword(): string
    {
        return $this->clave;
    }
    
    /**
     * Los atributos que son asignables masivamente (Mass Assignable).
     */
    protected $fillable = [
        'nombre',
        'email',
        'clave', 
        
        // CAMPOS DE CONFIGURACIÓN
        'zona_horaria',
        'idioma_preferido',
        
        // CONTACTO Y PERSONALES
        'identificacion_dni',
        'fecha_nacimiento',
        'genero',
        'telefono_principal',
        'numero_celular', 
        'foto_perfil_ruta',
        'enlace_linkedin', 
        
        // ORGANIZACIONALES (RRHH)
        'titulo_profesional',
        'departamento',
        'posicion_laboral', 
        'fecha_contratacion', 
        'es_supervisor', 
        
        // FINANCIEROS
        'banco_nombre', 
        'numero_cuenta', 
        'codigo_swift', 
        
        // UBICACIÓN
        'direccion_calle',
        'direccion_linea_2', 
        'direccion_ciudad',
        'direccion_estado_provincia', 
        'direccion_pais',
        'codigo_postal',

        // ESTADO DE CUENTA
        'estado_cuenta', 
        'razon_estado', 
        
        'ultimo_login_en',
    ];

    /**
     * Los atributos que deberían estar ocultos para arrays.
     */
    protected $hidden = [
        'clave', 
        'remember_token',
    ];

    /**
     * Los atributos que deberían ser casteados a tipos nativos.
     */
    protected $casts = [
        'email_verificado_en' => 'datetime',
        'ultimo_login_en' => 'datetime',
        'fecha_nacimiento' => 'date', 
        'fecha_contratacion' => 'date', 
        'es_supervisor' => 'boolean', 
        'clave' => 'hashed', 
    ];

    // ==========================================================
    // RELACIONES
    // ==========================================================
    
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_usuario', 'usuario_id', 'role_id');
    }

    public function configuracion(): HasOne
    {
        return $this->hasOne(ConfiguracionUsuario::class);
    }
    
    public function proyectosCreados(): HasMany
    {
        return $this->hasMany(Proyecto::class, 'usuario_creador_id');
    }

    public function tareasAsignadas(): HasMany
    {
        return $this->hasMany(Tarea::class, 'usuario_asignado_id');
    }

    public function contactosPropios(): HasMany
    {
        return $this->hasMany(Contacto::class, 'usuario_propietario_id');
    }

    public function transacciones(): HasMany
    {
        return $this->hasMany(Transaccion::class, 'usuario_id');
    }
    
    public function proyectos(): BelongsToMany
    {
        return $this->belongsToMany(Proyecto::class, 'proyecto_usuario', 'usuario_id', 'proyecto_id')
                    ->withPivot('rol', 'fecha_ingreso', 'activo') 
                    ->withTimestamps();
    }

    public function reuniones(): BelongsToMany
    {
        return $this->belongsToMany(Reunion::class, 'reunion_usuario', 'usuario_id', 'reunion_id')
                    ->withPivot('estado_invitacion', 'fecha_respuesta', 'unido_en')
                    ->withTimestamps();
    }
    
    public function conversaciones(): BelongsToMany
    {
        return $this->belongsToMany(Conversacion::class, 'conversacion_usuario', 'usuario_id', 'conversacion_id')
                    ->withPivot('ultimo_visto_en', 'silenciada') 
                    ->withTimestamps();
    }
    
    public function mensajes(): HasMany
    {
        return $this->hasMany(Mensaje::class, 'usuario_emisor_id');
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(RegistroActividad::class, 'usuario_id');
    }
    
    // ==========================================================
    // MÉTODOS DE ROL Y PERMISO (Helper Functions)
    // ==========================================================

    /**
     * Determina si el usuario tiene el rol de Super Administrador (nivel 100).
     */
    public function isSuperAdmin(): bool
    {
        return $this->roles->contains('nombre', 'super_administrador');
    }

    /**
     * Determina si el usuario tiene un rol de administrador (Nivel >= 50).
     */
    public function isAdmin(): bool
    {
        return $this->roles->contains(function ($role) {
            return $role->nivel >= 50;
        });
    }
    
    /**
     * Determina si el usuario tiene un permiso específico.
     */
    public function hasPermissionTo(string $permissionName): bool
    {
        if ($this->isSuperAdmin()) {
            return true; // Super Admin siempre tiene todos los permisos
        }
        
        // Carga dinámicamente si no se cargó previamente
        if (!$this->roles->first()->relationLoaded('permissions')) {
             $this->load('roles.permissions');
        }

        return $this->roles->pluck('permissions') 
                           ->flatten()          
                           ->contains('nombre', $permissionName);
    }
    
    // ==========================================================
    // ACCESSORS (Propiedades virtuales para las vistas)
    // ==========================================================
    
    /**
     * Accessor: Permite usar Auth::user()->es_administrador.
     */
    public function getEsAdministradorAttribute(): bool
    {
        return $this->isAdmin();
    }
    
    /**
     * Accessor: Permite usar Auth::user()->es_empleado.
     * Asume que Empleado son niveles 20-49, y no son administradores.
     */
    public function getEsEmpleadoAttribute(): bool
    {
        if ($this->isAdmin()) {
            return false;
        }

        // Busca cualquier rol con nivel entre 20 (ejemplo) y 49.
        return $this->roles->contains(function ($role) {
            return $role->nivel >= 20 && $role->nivel < 50;
        });
    }
    
    /**
     * Accessor: Devuelve la cadena de texto del rol más alto para el Dashboard.
     * Permite usar Auth::user()->roles_as_string.
     */
    public function getRolesAsStringAttribute(): string
    {
        // Si no hay roles cargados, los cargamos para evitar errores
        if ($this->roles->isEmpty()) {
            // Esto es solo un fallback de seguridad. En la práctica, deberían estar cargados.
            $this->load('roles');
        }

        $highestRole = $this->roles->sortByDesc('nivel')->first();

        if ($highestRole) {
            // Devuelve el nombre legible del rol
            return ucfirst(str_replace('_', ' ', $highestRole->nombre));
        }

        // Default
        return 'Usuario Básico';
    }
}
