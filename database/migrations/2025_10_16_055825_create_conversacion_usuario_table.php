<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla pivote de participantes).
     */
    public function up(): void
    {
        Schema::create('conversacion_usuario', function (Blueprint $table) {
            
            // CLAVES PRIMARIAS COMPUESTAS
            $table->foreignId('conversacion_id')
                  ->constrained('conversaciones')
                  ->onDelete('cascade');
                  
            $table->foreignId('usuario_id')
                  ->constrained('usuarios')
                  ->onDelete('cascade');
                  
            // AUDITORÍA Y ESTADO DE LECTURA
            $table->timestamp('ultimo_visto_en')->nullable();
            $table->boolean('silenciada')->default(false);
            
            $table->timestamps();
            
            // Establecer ambas claves foráneas como clave primaria compuesta para asegurar unicidad
            $table->primary(['conversacion_id', 'usuario_id']);
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversacion_usuario');
    }
};