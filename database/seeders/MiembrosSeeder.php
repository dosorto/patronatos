<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Miembros;
use App\Models\Persona;
use Faker\Factory as Faker;

class MiembrosSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_HN');

        // Obtener personas existentes
        $personas = Persona::where('estado', true)->limit(2)->get();

        if ($personas->count() < 2) {
            $this->command->warn('⚠️ No hay suficientes personas activas para crear miembros');
            return;
        }

        // Crear 2 miembros con personas existentes
        $miembros = [
            [
                'persona_id' => $personas[0]->id,
                'direccion' => 'Barrio El Centro, Calle Principal #123',
                'estado' => 1,
            ],
            [
                'persona_id' => $personas[1]->id,
                'direccion' => 'Colonia Las Flores, Avenida Secundaria #456',
                'estado' => 1,
            ],
        ];

        foreach ($miembros as $miembro) {
            Miembros::create($miembro);
        }

        $this->command->info('✅ 2 miembros creados exitosamente');
    }
}