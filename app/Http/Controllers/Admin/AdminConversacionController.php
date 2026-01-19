<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversacion;
use App\Models\Mensaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importamos la Facade DB para transacciones
use Illuminate\Support\Facades\Log; // Importamos la Facade Log para errores

class AdminConversacionController extends Controller
{
    /**
     * Muestra la lista de todas las conversaciones (CRUD index).
     */
    public function index()
    {
        // Obtener todas las conversaciones con los participantes y el último mensaje.
        $conversaciones = Conversacion::with([
            'participantes:id,nombre', 
            'ultimoMensaje'
        ])
        ->orderByDesc('updated_at')
        ->paginate(15);

        return view('admin.chat.index', compact('conversaciones'));
    }

    /**
     * Muestra una conversación específica y sus mensajes (CRUD show).
     */
    public function show(Conversacion $conversacion)
    {
        // Cargar los mensajes de la conversación, incluyendo el emisor.
        $mensajes = Mensaje::where('conversacion_id', $conversacion->id)
            ->with('emisor:id,nombre')
            ->orderBy('created_at', 'asc')
            ->get();
            
        // Forzar la carga de participantes.
        $conversacion->load('participantes:id,nombre');

        return view('admin.chat.show', compact('conversacion', 'mensajes'));
    }

    /**
     * Elimina una conversación completa (CRUD destroy).
     * ⚠️ Usa transacciones para asegurar la integridad de los datos.
     */
    public function destroy(Conversacion $conversacion)
    {
        try {
            DB::beginTransaction();

            // 1. Desvincular participantes (eliminar registros de la tabla pivot conversacion_usuario)
            $conversacion->participantes()->detach();
            
            // 2. Eliminar mensajes asociados (aunque el CASCADE debería manejarlo, es más robusto)
            Mensaje::where('conversacion_id', $conversacion->id)->delete();
            
            // 3. Eliminar la conversación
            $conversacion->delete();

            DB::commit();

            return redirect()->route('admin.conversaciones.index')
                ->with('success', 'Conversación y todos sus datos relacionados (mensajes, participantes) eliminados correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Registra el error.
            Log::error("Error al eliminar conversación {$conversacion->id}: " . $e->getMessage()); 
            
            return redirect()->route('admin.conversaciones.index')
                ->with('error', 'Error al intentar eliminar la conversación. Consulta el log de errores.');
        }
    }

    /**
     * Elimina un mensaje específico (Ruta personalizada).
     * ⚠️ Incluye lógica para actualizar el 'ultimo_mensaje_id' de la conversación.
     */
    public function destroyMessage(Mensaje $mensaje)
    {
        $conversacion = $mensaje->conversacion;
        $idMensajeEliminado = $mensaje->id;

        try {
            $mensaje->delete();
            
            // Si el mensaje eliminado era el último mensaje registrado en la conversación, actualizamos.
            if ($conversacion->ultimo_mensaje_id === $idMensajeEliminado) {
                // Buscamos el nuevo último mensaje
                $nuevoUltimoMensaje = Mensaje::where('conversacion_id', $conversacion->id)
                    ->latest('created_at')
                    ->first();
                    
                // Actualizamos la conversación (null si no quedan mensajes)
                $conversacion->update([
                    'ultimo_mensaje_id' => $nuevoUltimoMensaje ? $nuevoUltimoMensaje->id : null,
                    // Forzamos la actualización del timestamp para mover la conversación al inicio/final del listado
                    'updated_at' => now(), 
                ]);
            }

            return back()->with('success', 'Mensaje eliminado correctamente de la conversación.');
        } catch (\Exception $e) {
             Log::error("Error al eliminar mensaje {$idMensajeEliminado}: " . $e->getMessage());
             return back()->with('error', 'Error al intentar eliminar el mensaje.');
        }
    }

    // Los métodos create, store, edit y update no son necesarios y se omiten.
}
