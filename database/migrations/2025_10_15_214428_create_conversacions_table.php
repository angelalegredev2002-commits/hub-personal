<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla 'conversaciones').
     */
    public function up(): void
    {
        Schema::create('conversaciones', function (Blueprint $table) {
            $table->id();
            
            // TIPO DE CONVERSACIÓN
            $table->boolean('es_grupo')->default(false);

            // NOMBRE, AVATAR (Para chats grupales)
            $table->string('nombre', 255)->nullable();
            $table->string('imagen_url', 512)->nullable(); // URL de la imagen del grupo
            
            // CREADOR (Opcional, pero muy útil para moderación/gestión de grupos)
            $table->foreignId('creador_id')
                  ->nullable()
                  ->constrained('usuarios') // Asumiendo que la tabla de usuarios se llama 'usuarios'
                  ->onDelete('set null'); 
            
            // RELACIÓN CON OTROS MÓDULOS
            $table->foreignId('proyecto_id')
                  ->nullable()
                  ->constrained('proyectos')
                  ->onDelete('set null'); 
                  
            $table->unsignedBigInteger('reunion_id')->nullable(); 

            // GESTIÓN DE LA VISTA Y RENDIMIENTO
            $table->unsignedBigInteger('ultimo_mensaje_id')->nullable(); 
            
            $table->timestamp('ultima_actividad_en')->useCurrent();

            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('conversaciones');
    }
};
