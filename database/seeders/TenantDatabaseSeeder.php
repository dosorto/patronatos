<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Aquí llamas todos los seeders que quieres que se ejecuten en el tenant
        $this->call([
            TipoActivoSeeder::class,
            PersonaSeeder::class,   
        ]);
    }
}