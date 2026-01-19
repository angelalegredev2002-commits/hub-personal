<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\Mensaje;
use App\Models\Contacto;

class BusquedaController extends Controller
{
    /**
     * Realiza una búsqueda global a través de múltiples entidades.
     */
    public function index(Request $request)
    {
        // 1. VALIDACIÓN
        $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $query = $request->input('query');
        $usuarioId = auth()->id();
        $resultados = [];
        
        // El operador LIKE con comodines ('%') se usa para búsquedas parciales.

        // 2. BÚSQUEDA EN PROYECTOS (Solo los proyectos a los que pertenece el usuario)
        $proyectos = Proyecto::whereHas('miembros', function ($q) use ($usuarioId) {
            $q->where('usuario_id', $usuarioId);
        })
        ->where('nombre', 'LIKE', '%' . $query . '%')
        ->limit(5)
        ->get(['id', 'nombre', 'descripcion', 'estado']);
        $resultados['proyectos'] = $proyectos;

        // 3. BÚSQUEDA EN TAREAS (Solo las tareas dentro de los proyectos del usuario o asignadas a él)
        $tareas = Tarea::where(function ($q) use ($usuarioId, $query) {
            // Tareas en proyectos donde el usuario es miembro
            $q->whereHas('proyecto.miembros', function ($q2) use ($usuarioId) {
                $q2->where('usuario_id', $usuarioId);
            })
            // O tareas asignadas directamente al usuario
            ->orWhere('usuario_asignado_id', $usuarioId);
        })
        ->where('titulo', 'LIKE', '%' . $query . '%')
        ->limit(10)
        ->get(['id', 'titulo', 'estado', 'prioridad', 'fecha_vencimiento']);
        $resultados['tareas'] = $tareas;
        
        // 4. BÚSQUEDA EN CONTACTOS (Solo los contactos que pertenecen al usuario)
        $contactos = Contacto::where('usuario_propietario_id', $usuarioId)
        ->where('nombre_completo', 'LIKE', '%' . $query . '%')
        ->orWhere('organizacion', 'LIKE', '%' . $query . '%')
        ->limit(5)
        ->get(['id', 'nombre_completo', 'email', 'organizacion']);
        $resultados['contactos'] = $contactos;
        
        // 5. BÚSQUEDA EN MENSAJES (Opcional, puede ser costosa)
        // Solo en conversaciones donde el usuario participa.

        return view('busqueda.resultados', compact('query', 'resultados'));
    }
}