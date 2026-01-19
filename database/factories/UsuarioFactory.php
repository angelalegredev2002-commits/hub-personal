<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        // 🚨 La clave: Elimina 'es_administrador' y 'es_empleado'
        
        return [
            'nombre' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'clave' => Hash::make('password'), // Clave por defecto
            
            // ⬅️ ASIGNACIÓN DE ROL: Por defecto, usar el ID 3 (Usuario Estándar)
            // Esto es crucial para que los 10 usuarios falsos funcionen.
            'role_id' => 3, 
            
            // CONFIGURACIÓN
            'zona_horaria' => $this->faker->timezone(),
            'idioma_preferido' => $this->faker->randomElement(['es', 'en', 'fr']),
            
            // ESTADO DE CUENTA
            'estado_cuenta' => $this->faker->randomElement(['activo', 'inactivo', 'pendiente']),
            
            // DATOS PERSONALES EXPANDIDOS
            'identificacion_dni' => $this->faker->unique()->numerify('##########'),
            'fecha_nacimiento' => $this->faker->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
            'numero_celular' => $this->faker->phoneNumber(),
            'genero' => $this->faker->randomElement(['masculino', 'femenino', 'otro']),
            
            // DATOS ORGANIZACIONALES
            'titulo_profesional' => $this->faker->jobTitle(),
            'departamento' => $this->faker->randomElement(['IT', 'Ventas', 'RRHH', 'Finanzas']),
            'posicion_laboral' => $this->faker->jobTitle(),
            'fecha_contratacion' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'es_supervisor' => $this->faker->boolean(20), // 20% de probabilidad
            
            // UBICACIÓN
            'direccion_calle' => $this->faker->streetAddress(),
            'direccion_ciudad' => $this->faker->city(),
            'direccion_pais' => $this->faker->country(),
            'codigo_postal' => $this->faker->postcode(),
            
            'email_verificado_en' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}