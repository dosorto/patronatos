<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organizacion;
use App\Models\Municipio;

class OrganizacionSeeder extends Seeder
{
    public function run(): void
    {
        // Traemos algunos municipios existentes
        $tegucigalpa = Municipio::firstWhere('nombre', 'Tegucigalpa');
        $valleDeAngeles = Municipio::firstWhere('nombre', 'Valle de Ángeles');
        $sanPedroSula = Municipio::firstWhere('nombre', 'San Pedro Sula');
        $choloma = Municipio::firstWhere('nombre', 'Choloma');
        $sanSalvador = Municipio::firstWhere('nombre', 'San Salvador');

        $organizaciones = [
            [
                'id_tipo_organizacion' => 1,
                'id_municipio' => $tegucigalpa->id,
                'id_departamento' => $tegucigalpa->departamento_id,
                'direccion' => 'Avenida Central #123',
                'nombre' => 'Fundación Esperanza',
                'rtn' => '08011990012345',
                'telefono' => '22334455',
                'fecha_creacion' => '2015-03-15',
                'estado' => true,
            ],
            [
                'id_tipo_organizacion' => 2,
                'id_municipio' => $valleDeAngeles->id,
                'id_departamento' => $valleDeAngeles->departamento_id,
                'direccion' => 'Calle Principal #456',
                'nombre' => 'Asociación Valle Verde',
                'rtn' => '08011995067890',
                'telefono' => '22335566',
                'fecha_creacion' => '2018-07-20',
                'estado' => true,
            ],
            [
                'id_tipo_organizacion' => 1,
                'id_municipio' => $sanPedroSula->id,
                'id_departamento' => $sanPedroSula->departamento_id,
                'direccion' => 'Boulevard del Norte #789',
                'nombre' => 'ONG Manos Unidas',
                'rtn' => '08011992011234',
                'telefono' => '22336677',
                'fecha_creacion' => '2012-01-10',
                'estado' => true,
            ],
            [
                'id_tipo_organizacion' => 2,
                'id_municipio' => $choloma->id,
                'id_departamento' => $choloma->departamento_id,
                'direccion' => 'Zona Industrial #321',
                'nombre' => 'Corporación Choloma',
                'rtn' => '08011988098765',
                'telefono' => '22337788',
                'fecha_creacion' => '2020-05-05',
                'estado' => true,
            ],
            [
                'id_tipo_organizacion' => 1,
                'id_municipio' => $sanSalvador->id,
                'id_departamento' => $sanSalvador->departamento_id,
                'direccion' => 'Colonia Centro #101',
                'nombre' => 'Fundación Capital',
                'rtn' => '08011993044567',
                'telefono' => '22338899',
                'fecha_creacion' => '2017-09-25',
                'estado' => true,
            ],
        ];

        foreach ($organizaciones as $org) {
            Organizacion::updateOrCreate(
                ['nombre' => $org['nombre']], // Evita duplicados
                $org
            );
        }
    }
}