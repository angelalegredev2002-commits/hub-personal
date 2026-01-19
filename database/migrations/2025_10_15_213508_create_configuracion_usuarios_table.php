<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla 'configuracion_usuario').
     */
    public function up(): void
    {
        Schema::create('configuracion_usuario', function (Blueprint $table) {
            $table->id();
            
            // CLAVE FORÁNEA (CRUCIAL: Debe ser única para la relación 1:1)
            $table->foreignId('usuario_id')
                  ->unique()
                  ->constrained('usuarios') // Restringe a la tabla 'usuarios'
                  ->onDelete('cascade'); // Si se elimina el usuario, se elimina la configuración
                  
            // CAMPOS DE PREFERENCIAS DETALLADAS
            $table->boolean('notificaciones_email')->default(true);
            $table->boolean('notificaciones_chat')->default(true); // Se incluye del molde
            $table->string('orden_tareas_defecto', 50)->nullable();
            
            // CONFIGURACIÓN AVANZADA
            $table->string('tema_ui', 20)->default('sistema'); // (ej. 'claro', 'oscuro', 'sistema')
            $table->json('dashboard_layout_json')->nullable(); // Guarda la estructura del dashboard personalizado

            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_usuario');
    }
};