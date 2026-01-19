<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_usuario', function (Blueprint $table) {
            // Claves primarias compuestas
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            // Usamos 'usuario_id' para vincular a tu tabla 'usuarios'
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade'); 

            $table->primary(['role_id', 'usuario_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_usuario');
    }
};