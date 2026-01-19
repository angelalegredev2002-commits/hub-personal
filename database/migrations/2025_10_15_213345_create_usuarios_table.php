<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea la tabla 'usuarios').
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            // 1. CLAVES E IDENTIDAD BASE
            $table->id();
            $table->string('nombre', 150);
            $table->string('email', 255)->unique();
            $table->string('clave', 255); // Campo de Contraseña
            
            // 2. CONFIGURACIÓN Y ROLES
            $table->string('zona_horaria', 60);
            $table->string('idioma_preferido', 5)->default('es');
            
            // ⬅️ INTEGRACIÓN DE ROLES (RBAC)
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('restrict');
            
            // 3. CONTACTO Y DATOS PERSONALES EXPANDIDOS
            $table->string('identificacion_dni', 30)->unique()->nullable(); // DNI/Cédula/ID Fiscal
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ['masculino', 'femenino', 'otro'])->nullable();
            
            $table->string('telefono_principal', 50)->nullable();
            $table->string('numero_celular', 50)->nullable();
            
            $table->string('foto_perfil_ruta', 255)->nullable();
            $table->string('enlace_linkedin', 255)->nullable(); // ⬅️ Nuevo para profesional
            
            // 4. DATOS ORGANIZACIONALES (RRHH)
            $table->string('titulo_profesional', 150)->nullable();
            $table->string('departamento', 100)->nullable();
            $table->string('posicion_laboral', 100)->nullable(); // ⬅️ Nuevo: Cargo específico
            $table->date('fecha_contratacion')->nullable(); // ⬅️ Nuevo: Fecha de ingreso
            $table->boolean('es_supervisor')->default(false); // ⬅️ Nuevo: Para estructura jerárquica
            
            // 5. DATOS BANCARIOS Y FINANCIEROS (Opcionales y sensibles)
            $table->string('banco_nombre', 100)->nullable(); // ⬅️ Nuevo
            $table->string('numero_cuenta', 100)->nullable(); // ⬅️ Nuevo: Evitar usar números enteros por si tienen formatos especiales
            $table->string('codigo_swift', 50)->nullable(); // ⬅️ Nuevo
            
            // 6. UBICACIÓN (Direcciones)
            $table->string('direccion_calle', 255)->nullable();
            $table->string('direccion_linea_2', 255)->nullable(); // ⬅️ Nuevo: Para apartamento, suite, etc.
            $table->string('direccion_ciudad', 100)->nullable();
            $table->string('direccion_estado_provincia', 100)->nullable();
            $table->string('direccion_pais', 100)->nullable();
            $table->string('codigo_postal', 20)->nullable();

            // 7. ESTADO DE LA CUENTA (Activo / Inactivo / Suspendido)
            $table->enum('estado_cuenta', ['activo', 'inactivo', 'suspendido', 'pendiente'])->default('pendiente'); // ⬅️ Nuevo
            $table->string('razon_estado', 255)->nullable(); // ⬅️ Nuevo: Por qué está inactivo/suspendido
            
            // 8. TIEMPOS Y AUDITORÍA
            $table->timestamp('ultimo_login_en')->nullable();
            $table->timestamp('email_verificado_en')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
