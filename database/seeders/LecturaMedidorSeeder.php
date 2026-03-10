<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LecturaMedidores;
use App\Models\Medidores;
use Faker\Factory as Faker;

class LecturaMedidorSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_HN');
        $medidores = Medidores::all();

        if ($medidores->isEmpty()) {
            $this->command->warn('⚠️ No hay medidores disponibles');
            return;
        }

        // Crear 3 lecturas por cada medidor
        foreach ($medidores as $medidor) {
            $lecturaAnterior = 0;

            for ($i = 0; $i < 3; $i++) {
                $lecturaActual = $lecturaAnterior + $faker->numberBetween(10, 100);
                $consumo = $lecturaActual - $lecturaAnterior;

                LecturaMedidores::create([
                    'medidor_id' => $medidor->id,
                    'fecha_lectura' => now()->subMonths(3 - $i)->toDateString(),
                    'lectura_anterior' => $lecturaAnterior,
                    'lectura_actual' => $lecturaActual,
                    'consumo' => $consumo,
                ]);

                $lecturaAnterior = $lecturaActual;
            }
        }

        $this->command->info('✅ Lecturas de medidores creadas exitosamente');
    }
}