<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla 'reuniones').
     */
    public function up(): void
    {
        Schema::create('reuniones', function (Blueprint $table) {
            $table->id();
            
            // RELACIONES CLAVE
            $table->foreignId('usuario_creador_id')
                  ->constrained('usuarios')
                  ->onDelete('restrict');
                  
            $table->foreignId('proyecto_id')
                  ->nullable()
                  ->constrained('proyectos')
                  ->onDelete('set null');
                  
            // DATOS BASE
            $table->string('titulo', 255);
            $table->text('agenda')->nullable();
            $table->string('estado', 50)->default('programada'); // ej. 'programada', 'finalizada', 'cancelada'
            
            // TIEMPO Y VÍNCULOS
            $table->timestamp('fecha_hora_inicio');
            $table->integer('duracion_minutos');
            
            // UBICACIÓN (Física y Virtual)
            $table->string('tipo_ubicacion', 50)->default('virtual'); // 'virtual', 'fisica', 'hibrida'
            $table->string('enlace_videollamada', 255)->nullable();
            $table->string('servicio_video', 50)->nullable(); // ej. 'jitsi', 'zoom', 'google_meet'
            $table->string('contraseña_acceso', 50)->nullable(); // PIN o clave
            
            // UBICACIÓN FÍSICA
            $table->string('lugar_fisico', 255)->nullable();
            $table->string('direccion_completa', 255)->nullable();
            $table->decimal('latitud', 10, 6)->nullable();
            $table->decimal('longitud', 10, 6)->nullable();
            
            // DOCUMENTACIÓN Y AUDITORÍA
            $table->longText('minuta')->nullable(); // Campo CRUCIAL para los acuerdos
            $table->string('grabacion_url', 255)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('reuniones');
    }
};