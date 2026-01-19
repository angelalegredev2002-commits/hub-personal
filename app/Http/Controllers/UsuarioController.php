<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UsuarioController extends Controller
{
    /**
     * Muestra el perfil de un usuario específico.
     * Restringe el acceso a perfiles ajenos a menos que seas administrador.
     */
    public function show(Usuario $usuario)
    {
        // 🚨 CAMBIO CRÍTICO: Usamos el método isAdmin() del modelo en lugar de 'es_administrador'.
        if ($usuario->id !== auth()->id() && !auth()->user()->isAdmin()) {
             // Si el usuario no es el dueño del perfil Y no es un administrador
             abort(403, 'Acceso denegado a este perfil.');
        }

        $usuario->load('proyectos', 'actividades');
        
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Muestra el formulario de edición del perfil del usuario autenticado.
     */
    public function edit()
    {
        $usuario = auth()->user();
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Actualiza los datos del perfil del usuario autenticado.
     */
    public function update(Request $request)
    {
        $usuario = auth()->user();

        // 1. VALIDACIÓN
        // 🚨 Actualización de validación para incluir nuevos campos de la migración
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('usuarios')->ignore($usuario->id)],
            
            // CAMPOS DE CONFIGURACIÓN
            'zona_horaria' => 'nullable|string|max:60',
            'idioma_preferido' => 'nullable|string|max:5',

            // DATOS PERSONALES EXPANDIDOS
            'identificacion_dni' => ['nullable', 'string', 'max:30', Rule::unique('usuarios')->ignore($usuario->id)],
            'fecha_nacimiento' => 'nullable|date',
            'genero' => ['nullable', Rule::in(['masculino', 'femenino', 'otro'])],

            'telefono_principal' => 'nullable|string|max:50',
            'numero_celular' => 'nullable|string|max:50',
            'foto_perfil_ruta' => 'nullable|string|max:255',
            'enlace_linkedin' => 'nullable|url|max:255',
            
            // DATOS ORGANIZACIONALES (Solo para el propio usuario o si se implementa una UI de RRHH)
            // Por ahora, solo se permite actualizar si el usuario es un supervisor o admin (se asume en el front-end)
            'titulo_profesional' => 'nullable|string|max:150',
            'departamento' => 'nullable|string|max:100',
            'posicion_laboral' => 'nullable|string|max:100',
            // 'fecha_contratacion' => 'nullable|date', // Este campo es generalmente gestionado por RRHH, se omite de la edición de perfil estándar
            // 'es_supervisor' => 'boolean', // Campo gestionado por administración

            // UBICACIÓN
            'direccion_calle' => 'nullable|string|max:255',
            'direccion_linea_2' => 'nullable|string|max:255',
            'direccion_ciudad' => 'nullable|string|max:100',
            'direccion_estado_provincia' => 'nullable|string|max:100',
            'direccion_pais' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:20',
            
            // Validación de Cambio de Contraseña
            'password_actual' => 'nullable|string',
            'password_nuevo' => 'nullable|string|min:8|required_with:password_actual',
        ]);
        
        // 2. Lógica de Cambio de Contraseña
        $cambioContraseña = false;
        // La columna de contraseña se llama 'clave' en el modelo Usuario
        if (!empty($validatedData['password_actual']) && !empty($validatedData['password_nuevo'])) {
            
            // Verificar que la contraseña actual sea correcta antes de aplicar la nueva
            if (!Hash::check($validatedData['password_actual'], $usuario->clave)) {
                throw ValidationException::withMessages([
                    'password_actual' => ['La contraseña actual no es correcta.'],
                ]);
            }
            
            // Aplicar la nueva contraseña (el cast 'hashed' en el modelo se encarga de hashearla)
            $usuario->clave = $validatedData['password_nuevo'];
            $cambioContraseña = true;
        }

        // 3. ACTUALIZACIÓN DEL RESTO DE DATOS
        // Excluir los campos de contraseña del fill() para evitar problemas
        $camposAActualizar = collect($validatedData)->except(['password_actual', 'password_nuevo']);

        // 🚨 Importante: Evitar la actualización de campos sensibles o administrados por RRHH/Sistema
        $camposAActualizar = $camposAActualizar->except([
            'role_id',
            'estado_cuenta',
            'razon_estado',
            'fecha_contratacion',
            'es_supervisor',
            'banco_nombre',
            'numero_cuenta',
            'codigo_swift',
            'departamento',
            'posicion_laboral'
        ]);

        $usuario->fill($camposAActualizar->toArray());
        $usuario->save();
        
        $mensaje = 'Perfil actualizado correctamente.';
        if ($cambioContraseña) {
             $mensaje .= ' La contraseña ha sido cambiada.';
        }

        return redirect()->route('perfil.show', $usuario->id)
                         ->with('success', $mensaje);
    }
}
