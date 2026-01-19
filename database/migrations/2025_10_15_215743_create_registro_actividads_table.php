<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla 'registro_actividad').
     */
    public function up(): void
    {
        Schema::create('registro_actividad', function (Blueprint $table) {
            $table->id();
            
            // 1. QUIÉN Y QUÉ SE HIZO
            $table->foreignId('usuario_id')
                  ->nullable() // Puede ser una acción del sistema (ej. tarea recurrente)
                  ->constrained('usuarios')
                  ->onDelete('set null');
                  
            $table->string('accion', 255); // ej. 'tarea_creada', 'proyecto_archivado'

            // 2. EL ELEMENTO AFECTADO (Polimórfica)
            $table->morphs('sujeto'); // Crea 'sujeto_id' (BIGINT) y 'sujeto_type' (STRING)
            
            // 3. AUDITORÍA AVANZADA (Seguridad)
            $table->string('ip_origen', 45)->nullable(); // IP desde donde se realizó la acción
            $table->string('navegador_cliente', 255)->nullable(); // Navegador y S.O. (User-Agent)
            $table->string('url_origen', 255)->nullable(); // URL exacta donde ocurrió la acción
            $table->json('datos_anteriores')->nullable(); // Almacena el valor antes del cambio (ej. estado='pendiente' a 'hecho')

            $table->timestamps(); // Registra el momento exacto del evento
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_actividad');
    }
};