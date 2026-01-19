<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminProyectoController extends Controller
{
    /**
     * Muestra una lista de TODOS los proyectos en el sistema (Admin).
     */
    public function index()
    {
        // Cargar TODOS los proyectos, sin restricciones de usuario
        $proyectos = Proyecto::with('creador')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.proyectos.index', compact('proyectos'));
    }

    /**
     * Muestra el formulario para crear un nuevo proyecto (Admin).
     * ESTE ES EL MÉTODO FALTANTE
     */
    public function create()
    {
        // Necesitas la lista de usuarios para seleccionar al creador y a los miembros.
        $usuarios = Usuario::select('id', 'nombre as name', 'email')
                           ->orderBy('nombre')
                           ->get();

        return view('admin.proyectos.create', compact('usuarios'));
    }

    /**
     * Almacena un proyecto recién creado (Admin).
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'usuario_creador_id' => 'required|exists:usuarios,id', 
            'fecha_limite_estimada' => 'nullable|date|after_or_equal:today',
            'es_privado' => 'boolean',
            'progreso_porcentaje' => 'required|integer|min:0|max:100',
            'presupuesto_estimado' => 'nullable|numeric|min:0',
            'miembros' => 'nullable|array',
            'miembros.*' => 'exists:usuarios,id',
        ]);
        
        $proyecto = Proyecto::create(array_merge($validated, [
            'estado' => 'pendiente', 
            'prioridad' => 'media',
        ]));

        // Sincronizar miembros, asegurando que el creador sea el 'lider'
        $miembrosToAttach = collect($validated['miembros'] ?? [])->mapWithKeys(function ($id) {
            return [$id => ['rol' => 'editor', 'activo' => true]];
        })->toArray();
        
        // Asegurar que el creador esté incluido como líder
        $miembrosToAttach[$validated['usuario_creador_id']] = ['rol' => 'lider', 'activo' => true];

        $proyecto->miembros()->sync($miembrosToAttach);
        
        return redirect()->route('admin.proyectos.index')->with('success', 'Proyecto (ADMIN) creado exitosamente.');
    }

    /**
     * Muestra un proyecto específico (Admin).
     */
    public function show(Proyecto $proyecto)
    {
        // El administrador puede ver cualquier proyecto
        $proyecto->load(['creador', 'miembros', 'tareas', 'reuniones', 'archivos']);

        return view('admin.proyectos.show', compact('proyecto'));
    }

    /**
     * Muestra el formulario para editar un proyecto (Admin).
     */
    public function edit(Proyecto $proyecto)
    {
        // El administrador puede editar cualquier proyecto
        $usuarios = Usuario::select('id', 'nombre as name', 'email')->orderBy('nombre')->get();
        return view('admin.proyectos.edit', compact('proyecto', 'usuarios'));
    }

    /**
     * Actualiza un proyecto en el almacenamiento (Admin).
     */
    public function update(Request $request, Proyecto $proyecto)
    {
        // El administrador puede cambiar más campos, incluido el creador si es necesario (añadido 'usuario_creador_id')
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'usuario_creador_id' => 'required|exists:usuarios,id', 
            'estado' => ['required', Rule::in(['pendiente', 'en_progreso', 'finalizado', 'cancelado'])],
            'prioridad' => ['required', Rule::in(['baja', 'media', 'alta', 'critica'])],
            'fecha_limite_estimada' => 'nullable|date',
            'es_privado' => 'boolean',
            'progreso_porcentaje' => 'required|integer|min:0|max:100', 
            'presupuesto_estimado' => 'nullable|numeric|min:0',
            'miembros' => 'nullable|array',
            'miembros.*' => 'exists:usuarios,id', 
        ]);

        $proyecto->update($validated);
        
        // La lógica de asignación de miembros
        $miembrosToSync = [];
        if (!empty($validated['miembros'])) {
            foreach ($validated['miembros'] as $userId) {
                $rol = $proyecto->miembros()->where('usuario_id', $userId)->first()?->pivot->rol ?? 'editor';
                
                if ($userId == $validated['usuario_creador_id']) {
                    $rol = 'lider'; 
                }
                
                $miembrosToSync[$userId] = ['rol' => $rol, 'activo' => true];
            }
        }
        
        // Asegurar que el creador esté en la lista final si no fue seleccionado como miembro regular
        $creatorId = $validated['usuario_creador_id'];
        if (!isset($miembrosToSync[$creatorId])) {
            $miembrosToSync[$creatorId] = ['rol' => 'lider', 'activo' => true];
        }

        $proyecto->miembros()->sync($miembrosToSync);

        return redirect()->route('admin.proyectos.show', $proyecto)->with('success', 'Proyecto (ADMIN) actualizado exitosamente.');
    }

    /**
     * Elimina un proyecto (Admin).
     */
    public function destroy(Proyecto $proyecto)
    {
        // El administrador puede eliminar cualquier proyecto sin restricciones
        $proyecto->delete();

        return redirect()->route('admin.proyectos.index')->with('success', 'Proyecto (ADMIN) eliminado permanentemente.');
    }
}