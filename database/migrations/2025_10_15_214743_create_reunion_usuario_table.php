<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla 'reunion_usuario').
     */
    public function up(): void
    {
        Schema::create('reunion_usuario', function (Blueprint $table) {
            
            // 1. CLAVES FORÁNEAS (Las claves primarias de la tabla compuesta M:N)
            $table->foreignId('reunion_id')
                  ->constrained('reuniones')
                  ->onDelete('cascade'); // Si se elimina la reunión, se eliminan los registros de asistencia
                  
            $table->foreignId('usuario_id')
                  ->constrained('usuarios')
                  ->onDelete('cascade'); // Si se elimina el usuario, se eliminan sus registros de asistencia
                  
            // Establece la clave primaria compuesta
            $table->primary(['reunion_id', 'usuario_id']); 

            // 2. DETALLES DE LA RELACIÓN
            $table->string('estado_invitacion', 50)->default('invitado'); // ej. 'invitado', 'aceptado', 'rechazado'
            $table->timestamp('fecha_respuesta')->nullable(); // Cuándo respondió el usuario
            $table->timestamp('unido_en')->nullable(); // Hora real en que el usuario se unió a la llamada/reunión (para auditoría)
            
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('reunion_usuario');
    }
};