<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tarea;
use App\Models\Proyecto;
use App\Models\Usuario; // ⬅️ CAMBIO: Usando el modelo Usuario
use App\Models\Hito;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminTareaController extends Controller
{
    /**
     * Muestra una lista de TODAS las tareas en el sistema (Admin).
     */
    public function index()
    {
        $tareas = Tarea::with(['proyecto', 'asignado', 'creador'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.tareas.index', compact('tareas'));
    }

    /**
     * Muestra una tarea específica (Admin).
     */
    public function show(Tarea $tarea)
    {
        // El administrador puede ver cualquier tarea
        $tarea->load(['proyecto', 'hito', 'creador', 'asignado', 'archivos', 'actividad']);
        return view('admin.tareas.show', compact('tarea'));
    }

    /**
     * Muestra el formulario para crear una nueva tarea (Admin).
     */
    public function create()
    {
        $proyectos = Proyecto::all(['id', 'nombre']);
        // ⬅️ Uso de Usuario
        $usuarios = Usuario::all(['id', 'nombre as name']); 
        
        // Hitos: El admin puede necesitar seleccionar hitos, pero esto dependerá del frontend
        $hitos = Hito::all(['id', 'nombre', 'proyecto_id']); 

        return view('admin.tareas.create', compact('proyectos', 'usuarios', 'hitos'));
    }

    /**
     * Almacena una tarea recién creada (Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => 'nullable|exists:proyectos,id', 
            'hito_id' => 'nullable|exists:hitos,id',
            // ⬅️ CAMBIO: La tabla de usuarios es 'usuarios'
            'usuario_creador_id' => 'required|exists:usuarios,id', 
            'usuario_asignado_id' => 'required|exists:usuarios,id',
            'titulo' => 'required|string|max:255',
            'detalle' => 'nullable|string',
            'prioridad' => ['required', Rule::in(['baja', 'media', 'alta', 'critica'])],
            'estado' => ['required', Rule::in(['pendiente', 'en_progreso', 'revisión', 'completada', 'cancelada'])],
            'fecha_vencimiento' => 'nullable|date',
            'tiempo_estimado_minutos' => 'nullable|integer|min:0',
            'es_recurrente' => 'boolean',
            'regla_recurrencia' => 'nullable|string|max:255',
        ]);
        
        $validated['completado_en'] = $validated['estado'] === 'completada' ? now() : null;

        $tarea = Tarea::create($validated);

        return redirect()->route('admin.tareas.index')->with('success', 'Tarea (ADMIN) creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una tarea (Admin).
     */
    public function edit(Tarea $tarea)
    {
        // El administrador puede editar cualquier tarea
        $proyectos = Proyecto::all(['id', 'nombre']);
        // ⬅️ Uso de Usuario
        $usuarios = Usuario::all(['id', 'nombre as name']); 
        $hitos = $tarea->proyecto_id ? Hito::where('proyecto_id', $tarea->proyecto_id)->get(['id', 'nombre']) : Hito::all(['id', 'nombre']);

        return view('admin.tareas.edit', compact('tarea', 'proyectos', 'usuarios', 'hitos'));
    }

    /**
     * Actualiza una tarea (Admin).
     */
    public function update(Request $request, Tarea $tarea)
    {
        $validated = $request->validate([
            'proyecto_id' => 'nullable|exists:proyectos,id',
            'hito_id' => 'nullable|exists:hitos,id',
            // ⬅️ CAMBIO: La tabla de usuarios es 'usuarios'
            'usuario_creador_id' => 'required|exists:usuarios,id', 
            'usuario_asignado_id' => 'required|exists:usuarios,id',
            'titulo' => 'required|string|max:255',
            'detalle' => 'nullable|string',
            'prioridad' => ['required', Rule::in(['baja', 'media', 'alta', 'critica'])],
            'estado' => ['required', Rule::in(['pendiente', 'en_progreso', 'revisión', 'completada', 'cancelada'])],
            'fecha_vencimiento' => 'nullable|date',
            'tiempo_estimado_minutos' => 'nullable|integer|min:0',
            'es_recurrente' => 'boolean',
            'regla_recurrencia' => 'nullable|string|max:255',
        ]);
        
        $validated['completado_en'] = $validated['estado'] === 'completada' ? now() : null;

        $tarea->update($validated);

        return redirect()->route('admin.tareas.show', $tarea)->with('success', 'Tarea (ADMIN) actualizada exitosamente.');
    }

    /**
     * Elimina una tarea (Admin).
     */
    public function destroy(Tarea $tarea)
    {
        // El administrador puede eliminar cualquier tarea
        $tarea->delete();

        return redirect()->route('admin.tareas.index')->with('success', 'Tarea (ADMIN) eliminada permanentemente.');
    }
}