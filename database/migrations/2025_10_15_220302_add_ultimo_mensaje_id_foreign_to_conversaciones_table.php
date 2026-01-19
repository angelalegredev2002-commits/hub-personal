<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (añade la clave foránea 'ultimo_mensaje_id').
     */
    public function up(): void
    {
        Schema::table('conversaciones', function (Blueprint $table) {
            // Añade la restricción de clave foránea a la tabla 'mensajes'
            $table->foreign('ultimo_mensaje_id')
                  ->references('id')
                  ->on('mensajes')
                  ->onDelete('set null'); // Si se elimina el último mensaje, se resetea a nulo
        });
    }

    /**
     * Revierte las migraciones (elimina la clave foránea).
     */
    public function down(): void
    {
        Schema::table('conversaciones', function (Blueprint $table) {
            $table->dropForeign(['ultimo_mensaje_id']);
        });
    }
};