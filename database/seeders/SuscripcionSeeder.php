<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Miembros;
use App\Models\Servicio;
use App\Models\Suscripcion;
use Carbon\Carbon;

class SuscripcionSeeder extends Seeder
{
    public function run(): void
    {
        // Desactiva las restricciones de clave foránea
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Limpiamos la tabla por si acaso
        DB::table('suscripciones')->truncate();
        
        // Reactiva las restricciones
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Tomar algunos miembros y servicios existentes para inyectar datos
        $miembros = Miembros::take(3)->get();
        // Servicios sin medidor de preferencia
        $servicios = Servicio::where('tiene_medidor', false)->take(2)->get();

        if ($miembros->isEmpty() || $servicios->isEmpty()) {
            $this->command->info('No hay suficientes miembros o servicios sin medidor para poblar las suscripciones.');
            return;
        }

        // Suscripción 1: Está al día (ultimo_mes_pagado = mes actual)
        Suscripcion::create([
            'miembro_id' => $miembros[0]->id,
            'servicio_id' => $servicios[0]->id,
            'fecha_inicio' => Carbon::now()->subMonths(6)->startOfMonth(),
            'ultimo_mes_pagado' => Carbon::now()->startOfMonth(), // Al día
            'estado' => true,
        ]);

        // Suscripción 2: Debe 2 meses (ultimo_mes_pagado = hace 2 meses)
        if($miembros->count() > 1 && $servicios->count() > 1) {
            Suscripcion::create([
                'miembro_id' => $miembros[1]->id,
                'servicio_id' => $servicios[1]->id,
                'fecha_inicio' => Carbon::now()->subMonths(10)->startOfMonth(),
                'ultimo_mes_pagado' => Carbon::now()->subMonths(2)->startOfMonth(), // Debe meses
                'estado' => true,
            ]);
            
            // Suscripción 3: A otro servicio por el mismo miembro (Múltiples suscripciones)
            Suscripcion::create([
                'miembro_id' => $miembros[1]->id,
                'servicio_id' => $servicios[0]->id,
                'fecha_inicio' => Carbon::now()->subMonths(5)->startOfMonth(),
                'ultimo_mes_pagado' => Carbon::now()->subMonth()->startOfMonth(), // Debe 1 mes
                'estado' => true,
            ]);
        }

        $this->command->info('Suscripciones de prueba creadas correctamente.');
    }
}