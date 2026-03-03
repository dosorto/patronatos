<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pais;

class PaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lista de países con ISO
        $paises = [
            ['nombre' => 'Honduras', 'iso' => 'HN'],
        ];

        foreach ($paises as $pais) {
            Pais::updateOrCreate(
                ['nombre' => $pais['nombre']], // evita duplicados
                $pais
            );
        }
    }
}
