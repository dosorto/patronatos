<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Municipio;
use App\Models\Departamento;

class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Traemos algunos departamentos
        $franciscoMorazan = Departamento::firstWhere('nombre', 'Francisco Morazán');
        $cortes = Departamento::firstWhere('nombre', 'Cortés');
        $sanSalvador = Departamento::firstWhere('nombre', 'San Salvador');

        // Municipios de ejemplo por departamento
        $municipios = [
            $franciscoMorazan->id => [
                'Tegucigalpa',
                'Valle de Ángeles',
                'La Ceiba' // solo ejemplo
            ],
            $cortes->id => [
                'San Pedro Sula',
                'Choloma',
                'Omoa'
            ],
            $sanSalvador->id => [
                'San Salvador',
                'Soyapango',
                'Mejicanos'
            ],
        ];

        foreach ($municipios as $departamentoId => $munis) {
            foreach ($munis as $nombre) {
                Municipio::updateOrCreate(
                    ['nombre' => $nombre, 'departamento_id' => $departamentoId],
                    ['nombre' => $nombre, 'departamento_id' => $departamentoId]
                );
            }
        }
    }
}
