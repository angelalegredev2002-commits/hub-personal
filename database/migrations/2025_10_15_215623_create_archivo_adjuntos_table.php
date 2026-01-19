<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla 'archivos_adjuntos').
     */
    public function up(): void
    {
        Schema::create('archivos_adjuntos', function (Blueprint $table) {
            $table->id();
            
            // RELACIÓN DE PROPIEDAD
            $table->foreignId('usuario_subidor_id')
                  ->constrained('usuarios')
                  ->onDelete('restrict');

            // DATOS DEL ARCHIVO
            $table->string('nombre', 255); // Nombre original del archivo
            $table->string('nombre_servidor', 255)->unique(); // Nombre con el que se guarda en el disco (ej. hash)
            $table->string('ruta_almacenamiento', 255); // Ubicación física (ej. 'proyectos/1/docs/')
            $table->string('tipo_mime', 100); // Tipo MIME (ej. 'application/pdf', 'image/jpeg')
            $table->bigInteger('tamaño_bytes'); // Tamaño del archivo en bytes

            // SEGURIDAD Y AUDITORÍA (Crucial contra archivos maliciosos)
            $table->string('hash_sha256', 64)->nullable(); // Huella digital para verificar la integridad
            $table->boolean('es_publico')->default(false); // Acceso sin login (solo si está en S3/Cloudfront)
            $table->boolean('escaneo_virus_ok')->default(false); // Bandera para indicar que pasó el escaneo antivirus
            
            // RELACIÓN POLIMÓRFICA (Se adjunta a Tareas, Proyectos, Mensajes, etc.)
            $table->morphs('relacionable'); // Crea 'relacionable_id' (BIGINT) y 'relacionable_type' (STRING)

            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('archivos_adjuntos');
    }
};