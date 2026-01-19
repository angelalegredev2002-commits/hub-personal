<?php

namespace App\Events;

use App\Models\Mensaje;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MensajeEnviado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * El mensaje que se acaba de guardar.
     */
    public $mensaje;

    /**
     * El constructor del evento.
     */
    public function __construct(Mensaje $mensaje)
    {
        // Cargamos las relaciones necesarias para el frontend
        $this->mensaje = $mensaje->load('emisor');
    }

    /**
     * Define en qué canal se debe transmitir el evento.
     * Solo los usuarios en esta conversación deben recibirlo.
     */
    public function broadcastOn(): Channel
    {
        // Canal privado único para esta conversación
        return new Channel('chat.' . $this->mensaje->conversacion_id);
    }

    /**
     * Nombre del evento en el frontend.
     */
    public function broadcastAs(): string
    {
        return 'nuevo-mensaje';
    }
}
