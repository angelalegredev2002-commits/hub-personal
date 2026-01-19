<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla 'tareas').
     */
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            
            // RELACIONES CLAVE
            $table->foreignId('proyecto_id')
                  ->nullable() // Puede ser una tarea personal (sin proyecto)
                  ->constrained('proyectos')
                  ->onDelete('cascade'); // Si se elimina el proyecto, se eliminan sus tareas
                  
            // IMPORTANTE: El hito será definido en una migración futura, por eso es nullable
            // y no usamos 'constrained' por ahora, lo definiremos con 'references' más adelante si es necesario.
            $table->unsignedBigInteger('hito_id')->nullable();
            
            $table->foreignId('usuario_creador_id')
                  ->constrained('usuarios')
                  ->onDelete('restrict'); 

            $table->foreignId('usuario_asignado_id')
                  ->nullable() // Puede ser una tarea auto-asignada o sin asignar
                  ->constrained('usuarios')
                  ->onDelete('set null'); // Si se elimina el asignado, la tarea queda sin asignar

            // DATOS BASE Y GESTIÓN
            $table->string('titulo', 255);
            $table->text('detalle')->nullable();
            $table->string('prioridad', 20)->default('media'); // ej. 'alta', 'media', 'baja'
            $table->string('estado', 50)->default('por_hacer'); // ej. 'por_hacer', 'en_progreso', 'hecho'
            
            // GESTIÓN DE TIEMPO Y VENCIMIENTO
            $table->integer('tiempo_estimado_minutos')->nullable(); // Para Time Tracking
            $table->timestamp('fecha_vencimiento')->nullable();
            $table->timestamp('completado_en')->nullable(); // Registro exacto de finalización
            
            // RECURRENCIA AVANZADA
            $table->boolean('es_recurrente')->default(false);
            $table->string('regla_recurrencia', 255)->nullable(); // ej. 'semanal, lunes, miercoles'
            
            $table->timestamps();
            
            // CLAVE FORÁNEA (Hito): Se recomienda definirla aquí si la tabla 'hitos' es creada antes,
            // pero para evitar errores de orden, la mantenemos como unsignedBigInteger por ahora.
            // Si quieres que esto sea más riguroso, deberías crear 'hitos' antes. Por simplicidad,
            // la dejaremos así y definiremos la FK si es necesario después de crear 'hitos'.
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};