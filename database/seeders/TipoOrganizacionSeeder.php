<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoOrganizacionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipo_organizacion')->insert([
            ['nombre' => 'ONG',         'descripcion' => 'Organización No Gubernamental'],
            ['nombre' => 'Fundación',   'descripcion' => 'Entidad sin fines de lucro'],
            ['nombre' => 'Asociación',  'descripcion' => 'Grupo de personas con fin común'],
            ['nombre' => 'Cooperativa', 'descripcion' => 'Organización de ayuda mutua'],
            ['nombre' => 'Empresa',     'descripcion' => 'Entidad con fines comerciales'],
        ]);
    }
}