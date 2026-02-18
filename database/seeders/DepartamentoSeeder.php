<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;
use App\Models\Pais;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Traemos todos los países
        $honduras = Pais::firstWhere('nombre', 'Honduras');
        $elsalvador = Pais::firstWhere('nombre', 'El Salvador');
        $guatemala = Pais::firstWhere('nombre', 'Guatemala');

        // Departamentos de ejemplo por país
        $departamentos = [
            $honduras->id => [
                'Francisco Morazán',
                'Cortés',
                'Atlántida',
                'Olancho',
                'Choluteca'
            ],
            $elsalvador->id => [
                'San Salvador',
                'La Libertad',
                'Santa Ana',
                'San Miguel'
            ],
            $guatemala->id => [
                'Guatemala',
                'Sacatepéquez',
                'Escuintla',
                'Quetzaltenango'
            ]
        ];

        foreach ($departamentos as $paisId => $deptos) {
            foreach ($deptos as $nombre) {
                Departamento::updateOrCreate(
                    ['nombre' => $nombre, 'pais_id' => $paisId],
                    ['nombre' => $nombre, 'pais_id' => $paisId]
                );
            }
        }
    }
}
