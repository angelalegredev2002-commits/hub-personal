<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Asegúrate de que el modelo Role esté importado

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la tabla antes de sembrar (útil en desarrollo)
        Role::truncate(); 

        // 1. ROL SUPER ADMINISTRADOR (Máximo Nivel de Acceso)
        Role::create([
            'id' => 1, // Es crucial mantener el ID 1 para la lógica de Super Admin en el controlador
            'nombre' => 'super_administrador',
            'nivel' => 100,
            'descripcion' => 'Control total del sistema. No puede ser eliminado por otros administradores.',
        ]);

        // 2. ROL ADMINISTRADOR (Nivel de Acceso Medio)
        Role::create([
            'id' => 2,
            'nombre' => 'administrador',
            'nivel' => 50,
            'descripcion' => 'Acceso completo al panel de administración para gestionar usuarios, proyectos y chat.',
        ]);

        // 3. ROL USUARIO ESTÁNDAR (Nivel Base)
        Role::create([
            'id' => 3,
            'nombre' => 'usuario_estandar',
            'nivel' => 10,
            'descripcion' => 'Usuario regular del sistema, con acceso a dashboard y chat.',
        ]);
        
        // (No necesitamos asignar permisos aquí, eso sería en un PermissionSeeder aparte)
    }
}