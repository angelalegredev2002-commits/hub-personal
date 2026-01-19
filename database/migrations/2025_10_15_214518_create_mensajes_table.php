<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla 'mensajes').
     */
    public function up(): void
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id();
            
            // RELACIONES CLAVE
            $table->foreignId('conversacion_id')
                  ->constrained('conversaciones')
                  ->onDelete('cascade');
                  
            $table->foreignId('usuario_emisor_id')
                  ->constrained('usuarios')
                  ->onDelete('restrict');

            // CONTENIDO
            $table->text('contenido');
            $table->string('tipo_mensaje', 50)->default('texto');
            
            // LECTURA Y AUDITORÍA
            $table->timestamp('leido_en')->nullable();
            $table->timestamp('editado_en')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};