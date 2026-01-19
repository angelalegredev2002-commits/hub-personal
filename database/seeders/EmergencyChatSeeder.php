<?php

namespace Database\Seeders;

use App\Models\Conversacion;
use App\Models\Usuario; // Asegúrese de que este sea el namespace correcto de su modelo de usuario
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmergencyChatSeeder extends Seeder
{
    /**
     * Crea un usuario de prueba (si no existe) y liga al usuario actual.
     */
    public function run(): void
    {
        // ==========================================================
        // 🚨 CRÍTICO: REEMPLACE '3' CON SU ID DE USUARIO REAL 🚨
        // Este es el ID del usuario con el que usted está logueado.
        // ==========================================================
        $ID_USUARIO_ACTUAL = 0; // <--- ¡CAMBIE ESTO!
        
        $usuarioActual = Usuario::find($ID_USUARIO_ACTUAL); 
        
        if (!$usuarioActual) {
            $this->command->error("ERROR: No se encontró el Usuario con ID {$ID_USUARIO_ACTUAL}. ¡Revise la tabla 'usuarios'!");
            return;
        }

        // 2. Crear un segundo usuario de prueba (el "bot" de chat)
        $usuarioDePrueba = Usuario::firstOrCreate(
            ['email' => 'asistente@chat.dev'],
            [
                'nombre' => 'Asistente de Chat (Prueba)',
                'clave' => Hash::make('password'),
                'telefono_principal' => '555-1234',
                // Asegúrese de que todos los campos 'fillable' requeridos estén aquí:
                'zona_horaria' => 'America/Lima', 
                'idioma_preferido' => 'es',
                'es_administrador' => false,
                'es_empleado' => false,
                // Si la migración exige más campos no nulos, añádalos aquí.
            ]
        );

        // 3. Crear una nueva conversación
        $conversacion = Conversacion::create([
            'tipo' => 'privada', 
            'ultima_actividad_en' => now()
        ]);

        // 4. LIGAR AMBOS USUARIOS A LA CONVERSACIÓN (Tabla pivote: conversacion_usuario)
        // Usamos attach() en la relación 'participantes()' o 'conversaciones()' definida en Usuario.php
        $conversacion->participantes()->attach([
            $usuarioActual->id => ['ultimo_visto_en' => now()],
            $usuarioDePrueba->id => ['ultimo_visto_en' => now()],
        ]);

        // 5. Crear mensaje inicial
        $mensaje = $conversacion->mensajes()->create([
            'conversacion_id' => $conversacion->id,
            'usuario_emisor_id' => $usuarioDePrueba->id,
            'contenido' => '¡Hola! Este mensaje confirma que tu controlador y tu base de datos están conectados correctamente. ¡El chat ya no debería estar vacío!',
            'tipo_mensaje' => 'texto',
        ]);
        
        // 6. Actualizar el último mensaje en la conversación
        $conversacion->update(['ultimo_mensaje_id' => $mensaje->id]);

        $this->command->info("Conversación de emergencia creada y ligada al Usuario ID: {$usuarioActual->id}.");
    }
}
