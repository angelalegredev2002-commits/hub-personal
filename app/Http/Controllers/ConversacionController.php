<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversacion;
use App\Models\Usuario;
use App\Models\Mensaje;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Log;

class ConversacionController extends Controller
{
    /**
     * Muestra la bandeja de entrada del chat (ruta /chat)
     */
    public function index()
    {
        // El frontend (chat.index) manejará la lógica de seleccionar un chat ID 
        // si se pasa por la URL (ej. /chat?chatId=123)
        return view('chat.index');
    }

    /**
     * API: Obtiene la lista de chats activos para el usuario autenticado.
     */
    public function getChats(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado.'], 401);
            }

            // Cargar conversaciones del usuario, incluyendo participantes y el último mensaje
            $chats = Conversacion::whereHas('participantes', function ($query) use ($user) {
                $query->where('usuario_id', $user->id);
            })
            // RELACIÓN CORREGIDA: Traer solo el otro participante para chats 1:1, y todos para grupos.
            ->with(['participantes' => function ($query) use ($user) {
                // Para chats individuales (asumimos que serán la mayoría en el frontend)
                // cargamos todos los participantes, y el frontend se encarga de determinar el nombre.
                $query->select('usuarios.id', 'nombre'); 
            }, 'ultimoMensaje.emisor' => function($query) {
                 // Optimización: solo ID y nombre del emisor del último mensaje
                 $query->select('id', 'nombre'); 
            }])
            ->orderByDesc('updated_at') 
            ->get();
            
            return response()->json($chats);

        } catch (Exception $e) {
            Log::error('Error al obtener chats para el usuario ' . Auth::id() . ': ' . $e->getMessage());
            return response()->json([
                'error' => 'Error interno del servidor al cargar chats.', 
                'details' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
    
    /**
     * API: Obtiene la lista de usuarios con los que el usuario autenticado puede iniciar un chat.
     */
    public function getAvailableUsers(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado.'], 401);
            }

            // Usuarios disponibles: todos los usuarios excepto el actual.
            $availableUsers = Usuario::where('id', '!=', $user->id)
                ->select('id', 'nombre') 
                ->get();
                
            return response()->json($availableUsers);

        } catch (Exception $e) {
            Log::error('Error al obtener usuarios disponibles para el usuario ' . Auth::id() . ': ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor al cargar usuarios.', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Crea o devuelve una conversación privada entre el usuario autenticado y otro usuario.
     * Método llamado por la ruta POST /api/chat/iniciar/{userId}
     */
    public function createOrGetChat($userId)
    {
        // Reutilizamos la lógica interna
        $result = $this->createOrGetChatInternal(Auth::id(), $userId);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status'] ?? 500);
        }

        return response()->json($result);
    }
    
    /**
     * 🟢 NUEVO MÉTODO AUXILIAR: Lógica central de creación/obtención de chat 1:1.
     * @param int $user1Id El ID del primer usuario (normalmente Auth::id())
     * @param int $user2Id El ID del segundo usuario
     * @return array Resultado con 'success' o 'error'.
     */
    protected function createOrGetChatInternal($user1Id, $user2Id)
    {
        try {
            $user1 = Usuario::find($user1Id);
            $user2 = Usuario::findOrFail($user2Id);
            $action = 'found';

            // 1. Buscamos conversación privada entre ambos
            $conversacion = Conversacion::where('es_grupo', false)
                ->whereHas('participantes', fn ($query) => $query->where('usuario_id', $user1->id))
                ->whereHas('participantes', fn ($query) => $query->where('usuario_id', $user2->id))
                ->has('participantes', '=', 2) 
                ->first();

            // 2. Si no existe, crearla
            if (!$conversacion) {
                $conversacion = Conversacion::create([
                    'es_grupo' => false,
                    'nombre' => null, 
                    'creador_id' => $user1->id, 
                ]);
                $conversacion->participantes()->attach([$user1->id, $user2->id]);
                $action = 'created';
            }
            
            // Forzamos la actualización del timestamp
            $conversacion->touch(); 
            
            Log::info("Chat {$action} successfully between U{$user1->id} and U{$user2->id}. Chat ID: {$conversacion->id}");

            return ['success' => true, 'action' => $action, 'conversacion_id' => $conversacion->id];
        } catch (ModelNotFoundException $e) {
            return ['error' => 'Usuario destino no encontrado.', 'status' => 404];
        } catch (Exception $e) {
            Log::error('Error en createOrGetChatInternal: ' . $e->getMessage() . ' en línea ' . $e->getLine());
            return ['error' => 'Error interno del servidor al crear/obtener el chat.', 'status' => 500];
        }
    }
    
    /**
     * 🟢 NUEVO MÉTODO WEB: Inicia un chat con el Súper Administrador.
     * Método llamado por la ruta GET /chat/contactar-admin
     */
    public function contactAdmin(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('chat.panel')->with('error', 'Debes iniciar sesión para contactar a un administrador.');
        }

        try {
            // 1. Encontrar al Súper Administrador (asumiendo que 'super_admin' es el rol)
            $superAdmin = Usuario::where('rol', 'super_admin')->first(); 
            
            if (!$superAdmin) {
                return redirect()->route('chat.panel')->with('error', 'No se encontró un Súper Administrador para contactar. Intenta más tarde.');
            }

            // 2. Crear o recuperar el chat usando la lógica interna
            $result = $this->createOrGetChatInternal($user->id, $superAdmin->id);

            if (isset($result['conversacion_id'])) {
                // 3. Redirigir al panel de chat, pasando el ID de la conversación en la URL
                return redirect()->route('chat.panel', ['chatId' => $result['conversacion_id']])
                    ->with('success', 'Has iniciado un chat con el Súper Administrador. Puedes escribir tu solicitud.');
            }

            // Manejo de errores internos
            return redirect()->route('chat.panel')->with('error', $result['error'] ?? 'Error desconocido al iniciar el chat con el administrador.');
            
        } catch (Exception $e) {
            Log::error('Error al contactar administrador (U' . $user->id . '): ' . $e->getMessage());
            return redirect()->route('chat.panel')->with('error', 'Error inesperado. No se pudo iniciar el chat.');
        }
    }

    /**
     * API: Muestra los mensajes de una conversación específica.
     * Método llamado por la ruta GET /api/chats/{conversacion}
     */
    public function show(Conversacion $conversacion)
    {
         try {
            $user = Auth::user();
            
            // Verificar que el usuario sea participante
            if (!$conversacion->participantes->contains($user)) {
                return response()->json(['error' => 'Acceso denegado a esta conversación.'], 403);
            }

            // Cargar los mensajes y el emisor de cada mensaje
            $messages = Mensaje::where('conversacion_id', $conversacion->id)
                ->with(['emisor' => function ($query) {
                    $query->select('id', 'nombre'); // Optimización
                }])
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json($messages);
            
        } catch (Exception $e) {
            Log::error('Error en show de conversación ' . $conversacion->id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor al cargar mensajes.', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Almacena un nuevo mensaje en la conversación.
     * Método llamado por la ruta POST /api/chats/{conversacion}/mensajes
     */
    public function storeMessage(Request $request, Conversacion $conversacion)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                 return response()->json(['error' => 'Usuario no autenticado.'], 401);
            }

            // 1. Validar la solicitud
            $request->validate([
                'contenido' => 'required|string|max:1000',
            ]);

            // 2. Verificar que el usuario sea participante
            if (!$conversacion->participantes->contains($user)) {
                return response()->json(['error' => 'No puedes enviar mensajes a un chat del que no eres miembro.'], 403);
            }

            // 3. Crear el mensaje
            $mensaje = Mensaje::create([
                'conversacion_id' => $conversacion->id,
                'usuario_emisor_id' => $user->id,
                'contenido' => $request->input('contenido'),
            ]);

            // 4. Actualizar el campo ultimo_mensaje_id de la conversación
            $conversacion->update(['ultimo_mensaje_id' => $mensaje->id]);
            
            // 5. Forzar la actualización del timestamp de la conversación para que aparezca arriba en la lista
            $conversacion->touch(); 

            // Devolver el mensaje creado
            return response()->json(['success' => true, 'mensaje' => $mensaje], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Error de validación.', 'details' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Error al enviar mensaje a la conversación ' . ($conversacion->id ?? 'n/a') . ' por U' . Auth::id() . ': ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor al enviar mensaje.', 'details' => $e->getMessage()], 500);
        }
    }
}