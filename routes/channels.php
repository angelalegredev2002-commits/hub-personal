<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversacion;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Aquí registras todos los canales de difusión que admite tu aplicación.
|
*/

// Autoriza al usuario a escuchar el canal de una conversación específica
Broadcast::channel('chat.{conversacionId}', function ($user, $conversacionId) {
    // Busca la conversación y verifica si el usuario autenticado participa en ella.
    $conversacion = Conversacion::find($conversacionId);

    if (!$conversacion) {
        return false;
    }
    
    // Asume que la relación 'participantes' funciona correctamente con la tabla pivote
    return $conversacion->participantes()->where('usuario_id', $user->id)->exists();
});
