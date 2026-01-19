<?php

namespace App\Http\Requests;

use App\Models\Usuario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            // ==========================================================
            // 1. CAMPOS DE PERFIL BÁSICO Y ACCESO
            // ==========================================================
            'nombre' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                // Validación de unicidad de email, ignorando al usuario actual
                Rule::unique('usuarios', 'email')->ignore($userId),
            ],
            
            // ARCHIVO DE IMAGEN
            'foto_perfil' => ['nullable', 'image', 'max:2048'], // Acepta solo imágenes, máx. 2MB
            
            // ==========================================================
            // 2. DATOS PERSONALES Y CONTACTO EXPANDIDOS
            // ==========================================================
            'identificacion_dni' => [
                'nullable', 
                'string', 
                'max:30',
                // Validación de unicidad de DNI, ignorando al usuario actual
                Rule::unique('usuarios', 'identificacion_dni')->ignore($userId),
            ],
            'fecha_nacimiento' => ['nullable', 'date', 'before_or_equal:today'],
            'genero' => ['nullable', Rule::in(['masculino', 'femenino', 'otro', 'no_especificado'])],
            
            // Teléfonos
            'telefono_principal' => ['nullable', 'string', 'max:50'],
            'numero_celular' => ['nullable', 'string', 'max:50'],
            
            // Redes Sociales
            'enlace_linkedin' => ['nullable', 'url', 'max:255'],
            
            // ==========================================================
            // 3. PREFERENCIAS Y CONFIGURACIÓN
            // ==========================================================
            'zona_horaria' => ['required', 'string', 'max:60'], // Se hizo REQUIRED ya que tiene un valor por defecto al registrar
            'idioma_preferido' => ['required', 'string', 'max:5'], // Se hizo REQUIRED ya que tiene un valor por defecto al registrar
            
            // ==========================================================
            // 4. DIRECCIÓN (UBICACIÓN)
            // ==========================================================
            'direccion_calle' => ['nullable', 'string', 'max:255'],
            'direccion_linea_2' => ['nullable', 'string', 'max:255'],
            'direccion_ciudad' => ['nullable', 'string', 'max:100'],
            'direccion_estado_provincia' => ['nullable', 'string', 'max:100'],
            'direccion_pais' => ['nullable', 'string', 'max:100'],
            'codigo_postal' => ['nullable', 'string', 'max:20'],

            // ⚠️ NOTA: Los campos de RRHH (departamento, posicion_laboral, fecha_contratacion, es_supervisor) 
            // y Financieros (banco, cuenta) generalmente se gestionan en un controlador de 'Admin' o 'RRHH',
            // y no se incluyen aquí para seguridad y consistencia.
        ];
    }
}