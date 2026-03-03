<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Miembros;
use App\Models\Persona;
use App\Models\Organization;
use App\Models\Municipio;
use Faker\Factory as Faker;

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
        $fundacionEsperanza = Organization::firstWhere('name', 'Fundación Esperanza');
        $asociacionValleVerde = Organization::firstWhere('name', 'Asociación Valle Verde');
        $ongManosUnidas = Organization::firstWhere('name', 'ONG Manos Unidas');
        $corporacionCholoma = Organization::firstWhere('name', 'Corporación Choloma');
        $fundacionCapital = Organization::firstWhere('name', 'Fundación Capital');

        // Traemos municipios
        $tegucigalpa = Municipio::firstWhere('nombre', 'Tegucigalpa');
        $valleDeAngeles = Municipio::firstWhere('nombre', 'Valle de Ángeles');
        $sanPedroSula = Municipio::firstWhere('nombre', 'San Pedro Sula');
        $choloma = Municipio::firstWhere('nombre', 'Choloma');
        $sanSalvador = Municipio::firstWhere('nombre', 'San Salvador');

        // Crear miembros registro por registro
        Miembros::create([
            'persona_id' => $jorge->id,
            'organization_id' => $fundacionEsperanza->id,
            'municipio_id' => $tegucigalpa->id,
            'direccion' => 'Avenida Central #123',
            'estado' => true,
        ]);

        Miembros::create([
            'persona_id' => $ana->id,
            'organization_id' => $asociacionValleVerde->id,
            'municipio_id' => $valleDeAngeles->id,
            'direccion' => 'Calle Principal #456',
            'estado' => true,
        ]);

        Miembros::create([
            'persona_id' => $carlos->id,
            'organization_id' => $ongManosUnidas->id,
            'municipio_id' => $sanPedroSula->id,
            'direccion' => 'Boulevard del Norte #789',
            'estado' => false, // por ejemplo, inactivo
        ]);

        Miembros::create([
            'persona_id' => $maria->id,
            'organization_id' => $fundacionCapital->id,
            'municipio_id' => $sanSalvador->id,
            'direccion' => 'Colonia Centro #101',
            'estado' => true,
        ]);

        $faker = Faker::create('es_HN');
        
        // Obtener personas que no sean miembros aún
        $personasSinMiembro = Persona::doesntHave('miembros')->inRandomOrder()->take(30)->get();
        $organizations = Organization::all();
        $municipios = Municipio::all();
        
        if ($organizations->isNotEmpty() && $municipios->isNotEmpty()) {
            foreach ($personasSinMiembro as $persona) {
                Miembros::create([
                    'persona_id' => $persona->id,
                    'organization_id' => $organizations->random()->id,
                    'municipio_id' => $municipios->random()->id,
                    'direccion' => $faker->address,
                    'estado' => $faker->boolean(90),
                ]);
            }
        }
    }
}