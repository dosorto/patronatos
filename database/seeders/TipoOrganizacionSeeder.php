<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoOrganizacionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipo_organizacion')->insert([
            ['nombre' => 'Junta de Agua',  'descripcion' => 'Organización encargada del suministro de agua'],
            ['nombre' => 'Patronato',      'descripcion' => 'Junta directiva de una comunidad'],
        ]);
    }
}