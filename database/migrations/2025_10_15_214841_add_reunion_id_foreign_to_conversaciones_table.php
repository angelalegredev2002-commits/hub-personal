<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (añade la clave foránea a 'conversaciones').
     */
    public function up(): void
    {
        Schema::table('conversaciones', function (Blueprint $table) {
            // Asegura que el campo exista como unsignedBigInteger (ya lo creamos así)
            // $table->unsignedBigInteger('reunion_id')->nullable()->after('proyecto_id'); 
            
            // Añade la restricción de clave foránea
            $table->foreign('reunion_id')
                  ->references('id')
                  ->on('reuniones')
                  ->onDelete('set null');
        });
    }

    /**
     * Revierte las migraciones (elimina la clave foránea).
     */
    public function down(): void
    {
        Schema::table('conversaciones', function (Blueprint $table) {
            $table->dropForeign(['reunion_id']);
        });
    }
};