<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (añade la clave foránea a 'tareas').
     */
    public function up(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            // Añade la restricción de clave foránea
            $table->foreign('hito_id')
                  ->references('id')
                  ->on('hitos')
                  ->onDelete('set null'); // Si se elimina el hito, el campo queda nulo
        });
    }

    /**
     * Revierte las migraciones (elimina la clave foránea).
     */
    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            // Debe eliminar la clave foránea antes de intentar eliminar la columna (si fuera el caso)
            $table->dropForeign(['hito_id']);
        });
    }
};