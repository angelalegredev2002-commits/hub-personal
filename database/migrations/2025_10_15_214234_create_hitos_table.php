<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla 'hitos').
     */
    public function up(): void
    {
        Schema::create('hitos', function (Blueprint $table) {
            $table->id();
            
            // RELACIONES CLAVE
            $table->foreignId('proyecto_id')
                  ->constrained('proyectos')
                  ->onDelete('cascade'); // Si se elimina el proyecto, se eliminan sus hitos
                  
            $table->foreignId('usuario_responsable_id')
                  ->nullable() // Puede ser que la responsabilidad sea del equipo
                  ->constrained('usuarios')
                  ->onDelete('set null');
                  
            // DATOS DEL HITO
            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->date('fecha_meta'); // Fecha límite para alcanzar el hito
            $table->string('estado', 50)->default('pendiente'); // ej. 'pendiente', 'logrado', 'fallido'
            $table->integer('progreso_porcentaje')->default(0)->max(100); // Avance del hito

            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('hitos');
    }
};