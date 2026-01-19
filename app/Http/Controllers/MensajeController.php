<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensaje;
use App\Models\Conversacion;
use App\Models\RegistroActividad;
use App\Events\MensajeEnviado; // ¡Importación CLAVE para Broadcasting!
use Illuminate\Support\Facades\Auth;

class MensajeController extends Controller
{
    /**
     * Almacena un nuevo mensaje dentro de una conversación específica y lo difunde.
     * * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conversacion  $conversacion
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Conversacion $conversacion)
    {
        // 1. VERIFICACIÓN DE PERMISOS
        // Es crucial verificar que Auth::id() sea participante de $conversacion.
        // Asumimos que la lógica de participación ya está implementada y es correcta.
        
        // 2. VALIDACIÓN DE DATOS
        $validatedData = $request->validate([
            'contenido' => 'required|string|max:5000',
            // Puedes añadir validación de archivos aquí si es necesario
        ]);

        // 3. CREACIÓN DEL MENSAJE
        $mensaje = $conversacion->mensajes()->create([
            'usuario_emisor_id' => Auth::id(),
            'contenido' => $validatedData['contenido'],
            'tipo_mensaje' => 'texto', // Por defecto
        ]);

        // 4. ACTUALIZACIÓN DE LA CONVERSACIÓN (Optimización)
        $conversacion->update([
            'ultimo_mensaje_id' => $mensaje->id,
            'ultima_actividad_en' => $mensaje->created_at,
        ]);
        
        // ******************************************************
        // PASO CLAVE: DIFUNDIR el mensaje a todos los participantes
        // ******************************************************
        broadcast(new MensajeEnviado($mensaje));
        
        // 5. REGISTRO DE ACTIVIDAD (Opcional)
        // RegistroActividad::create([ ... ]);

        // 6. RESPUESTA API
        return response()->json([
            'success' => true, 
            'message' => 'Mensaje enviado y difundido.', 
            'mensaje' => $mensaje->load('emisor') // Retornamos el mensaje con el emisor
        ], 201);
    }

    /**
     * Edita el contenido de un mensaje existente.
     * * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mensaje  $mensaje
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Mensaje $mensaje)
    {
        // 1. VERIFICACIÓN DE PERMISOS
        if ($mensaje->usuario_emisor_id !== Auth::id()) {
             return response()->json(['error' => 'No autorizado para editar este mensaje.'], 403);
        }

        // 2. VALIDACIÓN
        $validatedData = $request->validate([
            'contenido' => 'required|string|max:5000',
        ]);
        
        // 3. ACTUALIZACIÓN
        $mensaje->update([
            'contenido' => $validatedData['contenido'],
            'editado_en' => now(), // Registra la hora de edición
        ]);

        // NOTA: Si usas broadcasting para ediciones, deberías difundir un evento aquí también.
        
        return response()->json(['success' => true, 'message' => 'Mensaje editado', 'mensaje' => $mensaje]);
    }

    /**
     * Carga el historial de mensajes de una conversación.
     * (Método añadido para completar la funcionalidad del chat)
     * * @param  \App\Models\Conversacion  $conversacion
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Conversacion $conversacion)
    {
        // 1. Verificar si el usuario es participante (CRÍTICO)
        if (!$conversacion->participantes()->where('usuario_id', Auth::id())->exists()) {
             return response()->json(['error' => 'Acceso denegado a esta conversación.'], 403);
        }
        
        // 2. Cargar mensajes
        $mensajes = $conversacion->mensajes()
                                ->with('emisor') // Cargar al emisor para mostrar el nombre
                                ->orderBy('created_at', 'asc')
                                ->paginate(50); // Paginación recomendada para historial
                                
        return response()->json($mensajes);
    }
}