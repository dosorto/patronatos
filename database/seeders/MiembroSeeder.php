<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Miembros;
use App\Models\Persona;
use App\Models\Organizacion;
use App\Models\Municipio;

class MiembroSeeder extends Seeder
{
    public function run(): void
    {
        // Traemos personas existentes
        $jorge = Persona::firstWhere('nombre', 'Jorge');
        $ana = Persona::firstWhere('nombre', 'Ana');
        $carlos = Persona::firstWhere('nombre', 'Carlos');
        $maria = Persona::firstWhere('nombre', 'María');

        // Traemos organizaciones
        $fundacionEsperanza = Organizacion::firstWhere('nombre', 'Fundación Esperanza');
        $asociacionValleVerde = Organizacion::firstWhere('nombre', 'Asociación Valle Verde');
        $ongManosUnidas = Organizacion::firstWhere('nombre', 'ONG Manos Unidas');
        $corporacionCholoma = Organizacion::firstWhere('nombre', 'Corporación Choloma');
        $fundacionCapital = Organizacion::firstWhere('nombre', 'Fundación Capital');

        // Traemos municipios
        $tegucigalpa = Municipio::firstWhere('nombre', 'Tegucigalpa');
        $valleDeAngeles = Municipio::firstWhere('nombre', 'Valle de Ángeles');
        $sanPedroSula = Municipio::firstWhere('nombre', 'San Pedro Sula');
        $choloma = Municipio::firstWhere('nombre', 'Choloma');
        $sanSalvador = Municipio::firstWhere('nombre', 'San Salvador');

        // Crear miembros registro por registro
        Miembros::create([
            'persona_id' => $jorge->id,
            'organizacion_id' => $fundacionEsperanza->id_organizacion,
            'municipio_id' => $tegucigalpa->id,
            'direccion' => 'Avenida Central #123',
            'estado' => true,
        ]);

        Miembros::create([
            'persona_id' => $ana->id,
            'organizacion_id' => $asociacionValleVerde->id_organizacion,
            'municipio_id' => $valleDeAngeles->id,
            'direccion' => 'Calle Principal #456',
            'estado' => true,
        ]);

        Miembros::create([
            'persona_id' => $carlos->id,
            'organizacion_id' => $ongManosUnidas->id_organizacion,
            'municipio_id' => $sanPedroSula->id,
            'direccion' => 'Boulevard del Norte #789',
            'estado' => false, // por ejemplo, inactivo
        ]);

        Miembros::create([
            'persona_id' => $maria->id,
            'organizacion_id' => $fundacionCapital->id_organizacion,
            'municipio_id' => $sanSalvador->id,
            'direccion' => 'Colonia Centro #101',
            'estado' => true,
        ]);
    }
}