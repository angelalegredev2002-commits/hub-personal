<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla 'proyectos').
     */
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            
            // RELACIÓN CON EL CREADOR
            $table->foreignId('usuario_creador_id')
                  ->constrained('usuarios')
                  ->onDelete('restrict'); // No eliminamos proyectos si el usuario se va
                  
            // DATOS BASE
            $table->string('nombre', 255);
            $table->text('descripcion');
            $table->string('estado', 50)->default('activo'); // ej. 'activo', 'finalizado', 'cancelado'
            $table->string('prioridad', 20)->default('media'); // ej. 'alta', 'media', 'baja'
            
            // GESTIÓN DE TIEMPO Y PRESUPUESTO
            $table->date('fecha_inicio_estimada')->nullable();
            $table->date('fecha_limite_estimada')->nullable();
            $table->decimal('presupuesto_estimado', 10, 2)->default(0.00); // Para gestión financiera
            $table->integer('progreso_porcentaje')->default(0)->max(100); // 0-100%
            
            // CONFIGURACIÓN DE ACCESO
            $table->boolean('es_privado')->default(false); // Si solo los miembros pueden verlo
            
            $table->timestamps(); // creado_en, actualizado_en
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};