<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla 'transacciones').
     */
    public function up(): void
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            
            // RELACIONES CLAVE
            $table->foreignId('usuario_id')
                  ->constrained('usuarios')
                  ->onDelete('restrict'); // Quién registró el movimiento
                  
            $table->foreignId('proyecto_id')
                  ->nullable() // Puede ser un gasto/ingreso personal
                  ->constrained('proyectos')
                  ->onDelete('set null'); // Si se elimina el proyecto, el gasto queda como personal

            // DETALLES FINANCIEROS
            $table->decimal('monto', 10, 2); // Valor monetario (ej. 500.25)
            $table->string('tipo', 20)->default('gasto'); // 'ingreso', 'gasto', 'transferencia'
            $table->string('categoria', 50)->nullable(); // ej. 'Alimentación', 'Software', 'Salario'
            $table->string('descripcion', 255); // Concepto o razón del movimiento
            
            // TIEMPO
            $table->date('fecha_transaccion'); // Día en que ocurrió el movimiento
            
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};