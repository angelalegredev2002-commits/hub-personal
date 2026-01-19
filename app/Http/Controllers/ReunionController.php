<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reunion;
use App\Models\Conversacion;
use App\Models\RegistroActividad;
// OTRAS IMPORTACIONES YA NO SON NECESARIAS EN ESTE CONTROLADOR DE USUARIO
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException; // Para manejar permisos

class ReunionController extends Controller
{
    /**
     * Muestra una lista de todas las reuniones (o las del usuario autenticado).
     * RUTA: GET /reuniones
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        
        // Carga reuniones donde el usuario es el creador o un asistente
        $reuniones = Reunion::where('usuario_creador_id', $userId)
            ->orWhereHas('asistentes', function ($query) use ($userId) {
                $query->where('usuario_id', $userId);
            })
            ->with(['creador', 'proyecto']) // Precarga relaciones esenciales
            ->orderBy('fecha_hora_inicio', 'desc')
            ->paginate(15);

        return view('reuniones.index', compact('reuniones'));
    }

    /**
     * Muestra el formulario para crear una nueva reunión.
     * ESTE MÉTODO FUE ELIMINADO Y MOVIDO A AdminReunionController.
     */
    
    /**
     * Muestra los detalles de una reunión específica.
     * RUTA: GET /reuniones/{reunion}
     */
    public function show(Reunion $reunion)
    {
        // Verificar si el usuario está autorizado a ver la reunión
        if ($reunion->usuario_creador_id !== auth()->id() && !$reunion->asistentes->contains(auth()->id())) {
            return abort(403, 'No tienes permiso para ver esta reunión.');
        }

        // Carga el chat y los asistentes con sus estados de invitación
        $reunion->load(['asistentes', 'conversacion']);

        return view('reuniones.show', compact('reunion'));
    }
    
    /**
     * Almacena una nueva reunión en la base de datos.
     * ESTE MÉTODO FUE ELIMINADO Y MOVIDO A AdminReunionController.
     */
    public function store(Request $request)
    {
        throw new AuthorizationException('Los usuarios no pueden crear reuniones. Esta funcionalidad es solo para administradores.');
    }

    /**
     * Actualiza los datos de la reunión.
     * ESTE MÉTODO FUE ELIMINADO Y MOVIDO A AdminReunionController.
     */
    public function update(Request $request, Reunion $reunion)
    {
        throw new AuthorizationException('Los usuarios no pueden editar reuniones. Esta funcionalidad es solo para administradores.');
    }
    
    /**
     * Elimina la reunión de la base de datos.
     * ESTE MÉTODO FUE ELIMINADO Y MOVIDO A AdminReunionController.
     */
    public function destroy(Reunion $reunion)
    {
        throw new AuthorizationException('Los usuarios no pueden eliminar reuniones. Esta funcionalidad es solo para administradores.');
    }

    /**
     * Actualiza la minuta de la reunión (después de que se lleva a cabo). 
     */
    public function guardarMinuta(Request $request, Reunion $reunion)
    {
        // 1. VERIFICACIÓN DE PERMISOS
        // Lógica: Solo el creador o un administrador puede guardar la minuta
        if ($reunion->usuario_creador_id !== auth()->id() && !auth()->user()->es_administrador) {
            return back()->with('error', 'No tienes permiso para guardar la minuta de esta reunión.');
        }
        
        // 2. VALIDACIÓN
        $validatedData = $request->validate([
            'minuta' => 'required|string',
        ]);
        
        // 3. ACTUALIZACIÓN
        $reunion->update([
            'minuta' => $validatedData['minuta'],
            'estado' => 'finalizada', // Marca como finalizada al guardar la minuta
            'completado_en' => now(), // Registra el momento de finalización
        ]);
        
        // 4. REGISTRO DE ACTIVIDAD
        RegistroActividad::create([
            'usuario_id' => auth()->id(),
            'accion' => 'minuta_guardada',
            'sujeto_type' => Reunion::class,
            'sujeto_id' => $reunion->id,
        ]);

        return back()->with('success', 'Minuta guardada y reunión marcada como finalizada.');
    }

    /**
     * Permite a un asistente confirmar o rechazar su participación.
     * RUTA: POST /reuniones/{reunion}/asistencia (Personalizada, no RESTful)
     */
    public function confirmarAsistencia(Request $request, Reunion $reunion)
    {
        $userId = auth()->id();

        // 1. VALIDACIÓN
        $validatedData = $request->validate([
            'estado' => ['required', 'string', Rule::in(['aceptado', 'rechazado', 'tentativo'])],
        ]);

        // 2. VERIFICAR QUE EL USUARIO SEA ASISTENTE
        if (!$reunion->asistentes()->where('usuario_id', $userId)->exists()) {
            return back()->with('error', 'No fuiste invitado a esta reunión.');
        }

        // 3. ACTUALIZAR ESTADO EN TABLA PIVOTE
        $reunion->asistentes()->updateExistingPivot($userId, [
            'estado_invitacion' => $validatedData['estado'],
            'fecha_respuesta' => now(),
            'unido_en' => ($validatedData['estado'] === 'aceptado') ? now() : null, // Opcional: registrar unión si acepta
        ]);
        
        // 4. REGISTRO DE ACTIVIDAD (Opcional, pero útil)
        RegistroActividad::create([
            'usuario_id' => $userId,
            'accion' => 'asistencia_' . $validatedData['estado'],
            'sujeto_type' => Reunion::class,
            'sujeto_id' => $reunion->id,
        ]);

        return back()->with('success', 'Tu respuesta de asistencia ha sido registrada como ' . $validatedData['estado'] . '.');
    }
}