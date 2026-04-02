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

        // Departamentos de ejemplo por país
        $departamentos = [
            $honduras->id => [
                'Atlántida',
                'Choluteca',
                'Colón',
                'Comayagua',
                'Copán',
                'Cortés',
                'El Paraíso',
                'Francisco Morazán',
                'Gracias a Dios',
                'Intibucá',
                'Islas de la Bahía',
                'La Paz',
                'Lempira',
                'Ocotepeque',
                'Olancho',
                'Santa Bárbara',
                'Valle',
                'Yoro'
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