<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario; // Importa tu modelo correcto
use Illuminate\Support\Facades\Hash; // Necesario para bcrypt/Hash

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurarse de que los roles se hayan ejecutado (si no se hizo en DatabaseSeeder)
        // $this->call(RoleSeeder::class); 

        // =========================================================
        // 1. SUPER ADMINISTRADOR (ROL ID 1) - El Usuario Clave
        // =========================================================
        Usuario::create([
            'nombre' => 'Super Admin',
            'email' => 'superadmin@app.com',
            'clave' => Hash::make('password'), 
            
            // ASIGNACIÓN DE ROL CRÍTICA
            'role_id' => 1, // ⬅️ ID del Super Administrador
            'estado_cuenta' => 'activo',
            
            // Datos adicionales para los nuevos campos
            'zona_horaria' => 'America/Lima',
            'idioma_preferido' => 'es',
            'fecha_nacimiento' => '1985-05-15',
            'numero_celular' => '+51 987 654 321',
            'titulo_profesional' => 'Director General',
            'departamento' => 'Direccion',
            'posicion_laboral' => 'CEO',
            'fecha_contratacion' => now(),
        ]);
        
        // =========================================================
        // 2. ADMINISTRADOR SECUNDARIO (ROL ID 2)
        // =========================================================
        Usuario::create([
            'nombre' => 'Admin Secundario',
            'email' => 'admin@app.com',
            'clave' => Hash::make('password'),
            'role_id' => 2, // ⬅️ ID del Administrador Estándar
            'estado_cuenta' => 'activo',
            'zona_horaria' => 'America/Bogota',
            'idioma_preferido' => 'es',
        ]);


        // =========================================================
        // 3. USUARIOS FALSOS con ROLES ALEATORIOS (USUARIO ESTÁNDAR)
        // =========================================================
        // Crea 10 usuarios falsos, asignando la mayoría al rol 3 (Usuario Estándar)
        Usuario::factory()->count(10)->create([
            'role_id' => 3, // Asignar el rol 3 por defecto a los falsos
            'estado_cuenta' => 'activo',
        ]);
    }
}