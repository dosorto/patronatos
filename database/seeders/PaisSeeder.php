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
            ['nombre' => 'El Salvador', 'iso' => 'SV'],
            ['nombre' => 'Guatemala', 'iso' => 'GT'],
            ['nombre' => 'Nicaragua', 'iso' => 'NI'],
            ['nombre' => 'Costa Rica', 'iso' => 'CR'],
            ['nombre' => 'Panamá', 'iso' => 'PA'],
        ];

        foreach ($paises as $pais) {
            Pais::updateOrCreate(
                ['nombre' => $pais['nombre']], // evita duplicados
                $pais
            );
        }
    }
}
