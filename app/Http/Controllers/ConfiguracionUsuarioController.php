<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracionUsuario;
use Illuminate\Validation\Rule;

class ConfiguracionUsuarioController extends Controller
{
    /**
     * Muestra el formulario de configuración del usuario autenticado.
     */
    public function show()
    {
        // Obtiene la configuración existente o crea una nueva si no existe
        $configuracion = auth()->user()->configuracion()->firstOrCreate([
            'usuario_id' => auth()->id()
        ]);
        
        return view('configuracion.show', compact('configuracion'));
    }

    /**
     * Actualiza la configuración personal del usuario.
     */
    public function update(Request $request)
    {
        // 1. VALIDACIÓN
        $validatedData = $request->validate([
            'notificaciones_email' => 'boolean',
            'notificaciones_chat' => 'boolean',
            'orden_tareas_defecto' => ['required', Rule::in(['prioridad', 'vencimiento', 'creacion'])],
            'tema_ui' => ['required', Rule::in(['claro', 'oscuro', 'sistema'])],
            // 'dashboard_layout_json' se maneja mejor a través de una API o servicio
        ]);

        // 2. OBTENER/CREAR LA CONFIGURACIÓN
        // Garantiza que el usuario tenga un registro en la tabla de configuración.
        $configuracion = auth()->user()->configuracion()->firstOrCreate([
            'usuario_id' => auth()->id()
        ]);

        // 3. ACTUALIZACIÓN
        $configuracion->update([
            'notificaciones_email' => $request->has('notificaciones_email'), // Checkbox handling
            'notificaciones_chat' => $request->has('notificaciones_chat'), // Checkbox handling
            'orden_tareas_defecto' => $validatedData['orden_tareas_defecto'],
            'tema_ui' => $validatedData['tema_ui'],
        ]);

        // No es necesario registrar la actividad, ya que son preferencias privadas.

        return back()->with('success', 'Configuración de usuario actualizada correctamente.');
    }
}