<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversacion;
use App\Models\Usuario;
use Illuminate\Validation\Rule;

class ParticipanteController extends Controller
{
    /**
     * Agrega uno o más usuarios a una conversación grupal existente.
     * * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conversacion  $conversacion
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request, Conversacion $conversacion)
    {
        // 1. Verificar si la conversación es grupal (si la regla de negocio lo requiere)
        if ($conversacion->tipo !== 'grupo') {
            return response()->json(['error' => 'Solo se pueden agregar participantes a conversaciones grupales.'], 403);
        }
        
        // 2. Validar los nuevos IDs y asegurar que no son ya participantes
        $currentParticipantIds = $conversacion->participantes()->pluck('usuario_id')->toArray();

        $validated = $request->validate([
            'usuario_ids' => 'required|array|min:1',
            'usuario_ids.*' => [
                'integer',
                'exists:usuarios,id',
                // Regla para asegurar que el ID no esté ya en la conversación
                Rule::notIn($currentParticipantIds),
            ],
        ]);

        // 3. Adjuntar los nuevos participantes
        $nuevosParticipantes = $validated['usuario_ids'];
        
        // El método attach adjunta los IDs con los valores por defecto del pivote
        $conversacion->participantes()->attach($nuevosParticipantes);
        
        // 4. (Opcional) Registrar un mensaje de sistema ("X usuarios fueron añadidos")
        // ...
        
        // 5. Actualizar actividad de la conversación
        $conversacion->update(['ultima_actividad_en' => now()]);

        return response()->json([
            'success' => true, 
            'message' => 'Participantes añadidos exitosamente.',
            'nuevos_participantes' => Usuario::find($nuevosParticipantes)->pluck('nombre')
        ]);
    }

    /**
     * Elimina a un participante de una conversación.
     * * @param  \App\Models\Conversacion  $conversacion
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function remove(Conversacion $conversacion, Usuario $usuario)
    {
        // 1. Verificar permisos (ej. solo el admin del grupo o el propio usuario puede salir)
        // if (!auth()->user()->canManageConversation($conversacion) && auth()->id() !== $usuario->id) {
        //     return response()->json(['error' => 'No tiene permisos para eliminar a este usuario.'], 403);
        // }

        // 2. Desvincular de la tabla pivote
        $conversacion->participantes()->detach($usuario->id);
        
        // 3. Actualizar actividad de la conversación
        $conversacion->update(['ultima_actividad_en' => now()]);

        return response()->json([
            'success' => true, 
            'message' => 'El participante ha sido eliminado de la conversación.'
        ]);
    }
    
    /**
     * Actualiza la configuración personal del usuario en la conversación (ej. silenciar).
     * * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conversacion  $conversacion
     * @return \Illuminate\Http\Response
     */
    public function updatePivot(Request $request, Conversacion $conversacion)
    {
        $userId = auth()->id();

        $validated = $request->validate([
            'silenciada' => 'required|boolean',
        ]);
        
        // 1. Actualiza la fila en la tabla pivote usando updateExistingPivot
        $conversacion->participantes()->updateExistingPivot($userId, [
            'silenciada' => $validated['silenciada'],
        ]);

        $accion = $validated['silenciada'] ? 'silenciada' : 'activada';

        return response()->json([
            'success' => true, 
            'message' => "Notificaciones de la conversación {$accion}."
        ]);
    }
}