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
        // Lista de países de ejemplo
        $paises = [
            ['nombre' => 'Honduras'],
            ['nombre' => 'El Salvador'],
            ['nombre' => 'Guatemala'],
            ['nombre' => 'Nicaragua'],
            ['nombre' => 'Costa Rica'],
            ['nombre' => 'Panamá'],
        ];

        foreach ($paises as $pais) {
            Pais::updateOrCreate(
                ['nombre' => $pais['nombre']], // evita duplicados
                $pais
            );
        }
    }
}
