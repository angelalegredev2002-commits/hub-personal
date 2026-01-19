<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Proyecto;
use App\Models\Usuario; // ⬅️ CAMBIO: Usando el modelo Usuario
use App\Models\Hito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TareaController extends Controller
{
    /**
     * Muestra una lista de tareas asignadas o creadas por el usuario.
     */
    public function index()
    {
        $user = Auth::user();

        // Tareas del usuario: Asignadas a él O creadas por él
        $tareas = Tarea::where('usuario_asignado_id', $user->id)
            ->orWhere('usuario_creador_id', $user->id)
            // Filtro adicional: Si la tarea pertenece a un proyecto, asegurar que el usuario es miembro.
            ->where(function ($query) use ($user) {
                $query->whereNull('proyecto_id') // Incluir tareas personales
                      ->orWhereHas('proyecto.miembros', function ($q) use ($user) {
                          $q->where('usuario_id', $user->id); // Incluir tareas de proyectos donde es miembro
                      });
            })
            ->with(['proyecto', 'asignado', 'creador'])
            ->orderByRaw("FIELD(prioridad, 'critica', 'alta', 'media', 'baja')") // Ordenar por prioridad
            ->orderBy('fecha_vencimiento')
            ->paginate(15);

        return view('tareas.index', compact('tareas'));
    }

    /**
     * Muestra el formulario para crear una nueva tarea.
     */
    public function create(Request $request)
    {
        // Se pueden recibir 'proyecto_id' o 'hito_id' por URL para precargar
        $proyectoId = $request->query('proyecto_id');
        $hitoId = $request->query('hito_id');

        $user = Auth::user();
        // Proyectos donde el usuario es creador o miembro
        $proyectos = Proyecto::where('usuario_creador_id', $user->id)
            ->orWhereHas('miembros', function ($query) use ($user) {
                $query->where('usuario_id', $user->id);
            })
            ->get(['id', 'nombre']);

        // Usuarios del proyecto seleccionado (si aplica)
        // ⬅️ Uso de Usuario
        $usuarios = Usuario::all(['id', 'nombre as name']);
        
        // Hitos del proyecto seleccionado (si aplica)
        $hitos = $proyectoId ? Hito::where('proyecto_id', $proyectoId)->get(['id', 'nombre']) : collect();

        return view('tareas.create', compact('proyectos', 'usuarios', 'hitos', 'proyectoId', 'hitoId'));
    }

    /**
     * Almacena una tarea recién creada.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => [
                'nullable', 
                'exists:proyectos,id', 
                // Asegurar que solo puede crear tareas en proyectos de los que es miembro
                Rule::exists('proyecto_usuario', 'proyecto_id')->where(function ($query) {
                    return $query->where('usuario_id', Auth::id());
                }),
            ],
            'hito_id' => 'nullable|exists:hitos,id',
            'usuario_asignado_id' => 'required|exists:usuarios,id', // ⬅️ CAMBIO: Tabla 'usuarios'
            'titulo' => 'required|string|max:255',
            'detalle' => 'nullable|string',
            'prioridad' => ['required', Rule::in(['baja', 'media', 'alta', 'critica'])],
            'fecha_vencimiento' => 'nullable|date|after_or_equal:today',
            'tiempo_estimado_minutos' => 'nullable|integer|min:0',
        ]);

        $tarea = Tarea::create(array_merge($validated, [
            'usuario_creador_id' => Auth::id(),
            'estado' => 'pendiente',
        ]));

        // Lógica opcional para notificaciones o registro de actividad aquí

        return redirect()->route('tareas.index')->with('success', 'Tarea creada exitosamente.');
    }

    /**
     * Muestra una tarea específica.
     */
    public function show(Tarea $tarea)
    {
        $user = Auth::user();

        // Política de acceso: 
        // 1. Es el creador O
        // 2. Está asignado O
        // 3. Es miembro del proyecto al que pertenece la tarea
        if ($tarea->usuario_creador_id !== $user->id && $tarea->usuario_asignado_id !== $user->id) {
            if ($tarea->proyecto_id && !$tarea->proyecto->miembros->contains($user)) {
                abort(403, 'No tienes permiso para ver esta tarea.');
            }
        }

        $tarea->load(['proyecto', 'hito', 'creador', 'asignado', 'archivos']);
        return view('tareas.show', compact('tarea'));
    }

    /**
     * Muestra el formulario para editar una tarea.
     */
    public function edit(Tarea $tarea)
    {
        // Política de acceso: solo el creador de la tarea o un miembro del proyecto (con permiso de edición)
        $user = Auth::user();
        if ($tarea->usuario_creador_id !== $user->id && !$tarea->proyecto?->miembros->contains($user)) {
            abort(403, 'No tienes permiso para editar esta tarea.');
        }

        $proyectos = Proyecto::all(['id', 'nombre']);
        // ⬅️ Uso de Usuario
        $usuarios = Usuario::all(['id', 'nombre as name']);
        $hitos = $tarea->proyecto_id ? Hito::where('proyecto_id', $tarea->proyecto_id)->get(['id', 'nombre']) : collect();

        return view('tareas.edit', compact('tarea', 'proyectos', 'usuarios', 'hitos'));
    }

    /**
     * Actualiza una tarea.
     */
    public function update(Request $request, Tarea $tarea)
    {
        // Re-validar la política de acceso
        $user = Auth::user();
        if ($tarea->usuario_creador_id !== $user->id && !$tarea->proyecto?->miembros->contains($user)) {
            abort(403, 'No tienes permiso para actualizar esta tarea.');
        }

        $validated = $request->validate([
            'proyecto_id' => 'nullable|exists:proyectos,id',
            'hito_id' => 'nullable|exists:hitos,id',
            'usuario_asignado_id' => 'required|exists:usuarios,id', // ⬅️ CAMBIO: Tabla 'usuarios'
            'titulo' => 'required|string|max:255',
            'detalle' => 'nullable|string',
            'prioridad' => ['required', Rule::in(['baja', 'media', 'alta', 'critica'])],
            'estado' => ['required', Rule::in(['pendiente', 'en_progreso', 'revisión', 'completada', 'cancelada'])],
            'fecha_vencimiento' => 'nullable|date',
            'tiempo_estimado_minutos' => 'nullable|integer|min:0',
            // ... otros campos
        ]);
        
        $validated['completado_en'] = $validated['estado'] === 'completada' ? now() : null;

        $tarea->update($validated);

        return redirect()->route('tareas.show', $tarea)->with('success', 'Tarea actualizada exitosamente.');
    }

    /**
     * Marca una tarea como completada o no completada (solo el asignado puede hacer esto fácilmente).
     */
    public function toggleCompletion(Tarea $tarea)
    {
        $user = Auth::user();
        
        // Solo el usuario asignado o el creador tienen permiso para marcar como completada
        if ($tarea->usuario_asignado_id !== $user->id && $tarea->usuario_creador_id !== $user->id) {
            abort(403, 'No tienes permiso para cambiar el estado de esta tarea.');
        }
        
        if ($tarea->estado === 'completada') {
            $tarea->update(['estado' => 'en_progreso', 'completado_en' => null]);
            $message = 'Tarea marcada como pendiente.';
        } else {
            $tarea->update(['estado' => 'completada', 'completado_en' => now()]);
            $message = 'Tarea marcada como completada.';
        }

        return back()->with('success', $message);
    }

    /**
     * Elimina una tarea (solo el creador).
     */
    public function destroy(Tarea $tarea)
    {
        if ($tarea->usuario_creador_id !== Auth::id()) {
            abort(403, 'Solo el creador puede eliminar esta tarea.');
        }

        $tarea->delete();

        return redirect()->route('tareas.index')->with('success', 'Tarea eliminada exitosamente.');
    }
}