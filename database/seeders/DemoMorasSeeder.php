<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Miembros;
use App\Models\Mora;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DemoMorasSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_ES');
        $org = Organization::first();
        
        if (!$org) {
            return; // Debe existir la organización (creada por DemoReportsSeeder)
        }

        $miembros = Miembros::where('organization_id', $org->id)->get();

        if ($miembros->isEmpty()) {
            return;
        }

        // Crear 10 moras para diferentes miembros
        foreach ($miembros->random(min(10, $miembros->count())) as $miembro) {
            $monto = $faker->randomFloat(2, 50, 450);
            
            Mora::create([
                'organization_id' => $org->id,
                'miembro_id' => $miembro->id,
                'periodo' => $faker->randomElement(['2024-01', '2024-02', '2024-03', '2023-12']),
                'monto_original' => $monto,
                'monto_pendiente' => $monto,
                'estado' => 'Pendiente',
                'mes_referencia' => Carbon::now()->subMonths(rand(1, 4))->startOfMonth(),
            ]);
        }
    }
}
