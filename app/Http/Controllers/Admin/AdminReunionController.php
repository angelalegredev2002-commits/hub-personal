<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reunion;
use App\Models\Conversacion;
use App\Models\RegistroActividad;
use App\Models\Proyecto;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr; // Utilidad de Laravel para arrays

class AdminReunionController extends Controller
{
    /**
     * Muestra una lista de TODAS las reuniones en el sistema (vista de administración).
     */
    public function index()
    {
        // Administradores necesitan ver todas las reuniones, no solo las propias.
        $reuniones = Reunion::with(['creador', 'proyecto'])
            ->orderBy('fecha_hora_inicio', 'desc')
            ->paginate(20);

        return view('admin.reunion.index', compact('reuniones'));
    }

    /**
     * Muestra el formulario para crear una nueva reunión (admin).
     */
    public function create()
    {
        // El administrador ve todos los proyectos activos y todos los usuarios
        $proyectos = Proyecto::where('estado', 'activo')->get(['id', 'nombre']);
        $usuarios = Usuario::orderBy('nombre')->get(['id', 'nombre']);

        return view('admin.reunion.create', compact('proyectos', 'usuarios'));
    }

    /**
     * Almacena una nueva reunión en la base de datos (admin).
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha_hora_inicio' => 'required|date',
            'duracion_minutos' => 'required|integer|min:5',
            'proyecto_id' => 'nullable|exists:proyectos,id',
            'agenda' => 'nullable|string',
            'enlace_videollamada' => 'nullable|url',
            'lugar_fisico' => 'nullable|string|max:255',
            'usuario_creador_id' => 'required|exists:usuarios,id', // Se hizo obligatorio para evitar ambigüedades.
            'asistentes' => 'nullable|array',
            'asistentes.*' => 'required|exists:usuarios,id',
        ]);
        
        DB::beginTransaction();

        try {
            $creadorId = $validatedData['usuario_creador_id'];

            $reunion = Reunion::create([
                'usuario_creador_id' => $creadorId, 
                'proyecto_id' => $validatedData['proyecto_id'] ?? null,
                'titulo' => $validatedData['titulo'],
                'fecha_hora_inicio' => $validatedData['fecha_hora_inicio'],
                'duracion_minutos' => $validatedData['duracion_minutos'],
                'agenda' => $validatedData['agenda'] ?? '',
                'enlace_videollamada' => $validatedData['enlace_videollamada'],
                'lugar_fisico' => $validatedData['lugar_fisico'],
                'tipo_ubicacion' => $validatedData['enlace_videollamada'] ? 
                                    ($validatedData['lugar_fisico'] ? 'hibrida' : 'virtual') : 
                                    'fisica',
            ]);

            // Crea la conversación de chat asociada a la reunión
            Conversacion::create([
                'tipo' => 'grupo',
                'nombre_grupo' => 'Chat de Reunión: ' . $reunion->titulo,
                'reunion_id' => $reunion->id,
            ]);
            
            // Lógica de Asistentes mejorada
            $asistentesIds = collect($validatedData['asistentes'] ?? [])
                                ->push($creadorId) // Asegura que el creador esté
                                ->unique(); // Elimina duplicados
            
            $asistentesAAsignar = $asistentesIds->mapWithKeys(function ($id) use ($creadorId) {
                return [$id => [
                    // El creador siempre está 'aceptado', los demás 'invitado'
                    'estado_invitacion' => ($id == $creadorId) ? 'aceptado' : 'invitado' 
                ]];
            })->toArray();

            $reunion->asistentes()->sync($asistentesAAsignar);

            RegistroActividad::create([
                'usuario_id' => auth()->id(),
                'accion' => 'admin_programo_reunion',
                'detalle' => 'Admin programó reunión para el usuario creador ' . $creadorId,
                'sujeto_type' => Reunion::class,
                'sujeto_id' => $reunion->id,
            ]);

            DB::commit(); 

            return redirect()->route('admin.reuniones.show', $reunion->id)
                             ->with('success', 'Reunión programada con éxito por el administrador.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al programar la reunión: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de una reunión específica (admin).
     */
    public function show(Reunion $reunion)
    {
        $reunion->load(['asistentes', 'conversacion', 'creador', 'proyecto']);

        return view('admin.reunion.show', compact('reunion'));
    }

    /**
     * Muestra el formulario para editar una reunión existente (admin).
     */
    public function edit(Reunion $reunion)
    {
        // El administrador ve todos los proyectos activos y todos los usuarios
        $proyectos = Proyecto::where('estado', 'activo')->get(['id', 'nombre']);
        $usuarios = Usuario::orderBy('nombre')->get(['id', 'nombre']);
        
        // Cargar asistentes actuales para marcar en el formulario
        $asistentesIds = $reunion->asistentes->pluck('id')->toArray();
        
        return view('admin.reunion.edit', compact('reunion', 'proyectos', 'usuarios', 'asistentesIds'));
    }

    /**
     * Actualiza los datos de la reunión (admin).
     */
    public function update(Request $request, Reunion $reunion)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha_hora_inicio' => 'required|date',
            'duracion_minutos' => 'required|integer|min:5',
            'proyecto_id' => 'nullable|exists:proyectos,id',
            'agenda' => 'nullable|string',
            'enlace_videollamada' => 'nullable|url',
            'lugar_fisico' => 'nullable|string|max:255',
            'estado' => ['required', 'string', Rule::in(['por_iniciar', 'en_curso', 'finalizada', 'cancelada'])],
            'minuta' => 'nullable|string',
            'asistentes' => 'nullable|array',
            'asistentes.*' => 'required|exists:usuarios,id',
            'usuario_creador_id' => 'required|exists:usuarios,id', // Hacemos obligatorio para evitar errores de cambio
        ]);
        
        DB::beginTransaction();

        try {
            $tipoUbicacion = $validatedData['enlace_videollamada'] ? 
                             ($validatedData['lugar_fisico'] ? 'hibrida' : 'virtual') : 
                             'fisica';
            
            $creadorId = $validatedData['usuario_creador_id'];

            $reunion->update([
                'usuario_creador_id' => $creadorId, 
                'proyecto_id' => $validatedData['proyecto_id'] ?? null,
                'titulo' => $validatedData['titulo'],
                'fecha_hora_inicio' => $validatedData['fecha_hora_inicio'],
                'duracion_minutos' => $validatedData['duracion_minutos'],
                'agenda' => $validatedData['agenda'] ?? $reunion->agenda,
                'enlace_videollamada' => $validatedData['enlace_videollamada'],
                'lugar_fisico' => $validatedData['lugar_fisico'],
                'tipo_ubicacion' => $tipoUbicacion,
                'estado' => $validatedData['estado'],
                'minuta' => $validatedData['minuta'] ?? $reunion->minuta,
                'completado_en' => ($validatedData['estado'] === 'finalizada' && !$reunion->completado_en) ? now() : $reunion->completado_en,
            ]);

            // 4. ACTUALIZACIÓN DE ASISTENTES
            $asistentesIds = collect($validatedData['asistentes'] ?? [])
                                ->push($creadorId)
                                ->unique()
                                ->toArray();
            
            $asistentesExistentes = $reunion->asistentes()->pluck('estado_invitacion', 'usuario_id')->toArray();
            $asistentesAAsignar = [];
            
            foreach ($asistentesIds as $usuarioId) {
                $estado = ($usuarioId == $creadorId) 
                          ? 'aceptado' // Creador siempre aceptado
                          : ($asistentesExistentes[$usuarioId] ?? 'invitado'); // Mantiene estado anterior o es 'invitado'
                
                $asistentesAAsignar[$usuarioId] = ['estado_invitacion' => $estado];
            }
            
            // Sincronizar: agrega, actualiza los existentes y elimina a los no seleccionados
            $reunion->asistentes()->sync($asistentesAAsignar);

            RegistroActividad::create([
                'usuario_id' => auth()->id(),
                'accion' => 'admin_actualizo_reunion',
                'sujeto_type' => Reunion::class,
                'sujeto_id' => $reunion->id,
            ]);

            DB::commit();

            return redirect()->route('admin.reuniones.show', $reunion->id)
                             ->with('success', 'Reunión actualizada por el administrador con éxito.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar la reunión: ' . $e->getMessage());
        }
    }
    
    /**
     * Elimina la reunión de la base de datos (admin).
     */
    public function destroy(Reunion $reunion)
    {
        DB::beginTransaction();
        try {
            $reunionTitulo = $reunion->titulo;
            $reunionId = $reunion->id;

            // Eliminar dependencias
            $reunion->asistentes()->detach();
            if ($reunion->conversacion) {
                // Si la conversación tiene mensajes, estos deben ser eliminados también si no usan CASCADE
                $reunion->conversacion->delete(); 
            }
            
            $reunion->delete();

            RegistroActividad::create([
                'usuario_id' => auth()->id(),
                'accion' => 'admin_elimino_reunion',
                'detalle' => 'Reunión eliminada: ' . $reunionTitulo . ' (ID: ' . $reunionId . ')',
                'sujeto_type' => Reunion::class,
                'sujeto_id' => $reunionId,
            ]);

            DB::commit();
            return redirect()->route('admin.reuniones.index')->with('success', 'Reunión "' . $reunionTitulo . '" eliminada por el administrador.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la reunión: ' . $e->getMessage());
        }
    }
}