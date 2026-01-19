<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Necesario para la desactivación

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Desactivar la verificación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            RoleSeeder::class,      // Primero, crea los roles
            // PermissionSeeder::class, // (Opcional, si tienes uno)
            TestUsersSeeder::class, // Segundo, crea los usuarios y pivotes
        ]);
        
        // 2. Reactivar la verificación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}