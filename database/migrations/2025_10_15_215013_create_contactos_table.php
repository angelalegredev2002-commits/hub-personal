<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla 'contactos').
     */
    public function up(): void
    {
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            
            // RELACIÓN DE PROPIEDAD
            // Indica qué usuario del Hub es el dueño de este contacto externo (privacidad)
            $table->foreignId('usuario_propietario_id')
                  ->constrained('usuarios')
                  ->onDelete('cascade'); 

            // DATOS BASE
            $table->string('nombre_completo', 255);
            $table->string('email', 255)->nullable();
            
            // DETALLES DE LA ORGANIZACIÓN
            $table->string('organizacion', 255)->nullable();
            $table->string('cargo_puesto', 100)->nullable(); // Puesto que ocupa el contacto
            $table->string('sitio_web', 255)->nullable();

            // TELÉFONOS
            $table->string('telefono_principal', 50)->nullable();
            $table->string('segundo_telefono', 50)->nullable();
            
            // UBICACIÓN Y RELACIÓN
            $table->string('direccion_empresa', 255)->nullable(); // Dirección física de su organización
            $table->string('relacion', 50)->nullable(); // ej. 'Cliente', 'Proveedor', 'Socio'
            $table->boolean('es_proveedor')->default(false);
            $table->boolean('es_cliente')->default(false);
            
            // AUDITORÍA Y NOTAS
            $table->timestamp('fecha_ultima_interaccion')->nullable(); // Para seguimiento
            $table->text('notas_privadas')->nullable(); // Notas internas sobre el contacto

            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};