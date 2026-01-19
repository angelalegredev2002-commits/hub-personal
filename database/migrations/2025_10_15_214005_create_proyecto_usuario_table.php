<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla 'proyecto_usuario').
     */
    public function up(): void
    {
        Schema::create('proyecto_usuario', function (Blueprint $table) {
            
            // 1. CLAVES FORÁNEAS (Las claves primarias de la tabla compuesta)
            $table->foreignId('proyecto_id')
                  ->constrained('proyectos')
                  ->onDelete('cascade'); // Si se elimina el proyecto, se elimina la relación
                  
            $table->foreignId('usuario_id')
                  ->constrained('usuarios')
                  ->onDelete('cascade'); // Si se elimina el usuario, se elimina la relación
                  
            // Establece que la combinación de proyecto_id y usuario_id es la clave primaria (no puede haber duplicados)
            $table->primary(['proyecto_id', 'usuario_id']); 

            // 2. DETALLES DE LA RELACIÓN
            $table->string('rol', 50)->default('contribuidor'); // ej. 'lider', 'contribuidor', 'observador'
            $table->timestamp('fecha_ingreso'); // Momento en que el usuario fue añadido
            $table->boolean('activo')->default(true); // Para suspender la participación sin borrar el historial
            
            $table->timestamps(); // Registra cuándo se creó o modificó el registro
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('proyecto_usuario');
    }
};