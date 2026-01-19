<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class ProyectoController extends Controller
{
    /**
     * Muestra una lista de proyectos visibles para el usuario autenticado.
     */
    public function index()
    {
        $userId = Auth::id();

        $proyectos = Proyecto::with(['creador', 'tareas', 'reuniones'])
            // INICIO: Lógica de Visibilidad
            ->where(function ($query) use ($userId) {
                // Criterio 1: Proyectos que son públicos (es_privado = false)
                $query->where('es_privado', false);
            })
            ->orWhere(function ($query) use ($userId) {
                // Criterio 2: Proyectos privados (es_privado = true) DONDE el usuario es el creador O miembro.
                $query->where('es_privado', true)
                      ->where(function ($q) use ($userId) {
                          // El usuario es el creador
                          $q->where('usuario_creador_id', $userId)
                            // O el usuario es un miembro
                            ->orWhereHas('miembros', function ($qMiembros) use ($userId) {
                                $qMiembros->where('usuario_id', $userId);
                            });
                      });
            })
            // FIN: Lógica de Visibilidad
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('proyectos.index', compact('proyectos'));
    }

    /**
     * Muestra un proyecto específico (solo si es miembro, creador, o si el proyecto es público).
     */
    public function show(Proyecto $proyecto)
    {
        $user = Auth::user();
        
        // 🚀 DEFINIR LAS VARIABLES NECESARIAS PARA LA VISTA:
        $isCreator = $proyecto->usuario_creador_id === $user->id;
        $isMember = $proyecto->miembros->contains($user->id); // Esta es la variable faltante
        $isMemberOrCreator = $isCreator || $isMember; 

        // Política de acceso: Denegar si es privado y el usuario NO es miembro/creador.
        if ($proyecto->es_privado && !$isMemberOrCreator) {
             abort(403, 'No tienes acceso a este proyecto privado.');
        }

        // Cargar relaciones necesarias
        $proyecto->load(['creador', 'miembros', 'tareas' => function ($query) {
             $query->orderBy('prioridad', 'desc')->limit(5); // Tareas más importantes
        }, 'reuniones' => function ($query) {
            $query->where('fecha_hora_inicio', '>', now())->orderBy('fecha_hora_inicio')->limit(5); // Próximas reuniones
        }]);

        // 🟢 PASAR LAS VARIABLES $isMember y $isCreator a la vista
        // La vista usa $isMember para mostrar u ocultar el botón "Solicitar acceso".
        return view('proyectos.show', compact('proyecto', 'isMember', 'isCreator')); 
    }
    
    // NOTA: Los métodos create, store, edit, update y destroy han sido eliminados 
    // de este controlador ya que están reservados para el AdminProyectoController.
}