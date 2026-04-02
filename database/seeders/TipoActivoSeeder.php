<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoActivoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Mueble',     'descripcion' => 'Activos de mobiliario'],
            ['nombre' => 'Inmueble',   'descripcion' => 'Propiedades y terrenos'],
            ['nombre' => 'Equipo',     'descripcion' => 'Equipos tecnológicos'],
            ['nombre' => 'Vehículo',   'descripcion' => 'Vehículos y transporte'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_activos')->updateOrInsert(
                ['nombre' => $tipo['nombre']],
                $tipo
            );
        }
    }
}
