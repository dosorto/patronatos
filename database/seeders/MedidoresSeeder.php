<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medidores;
use App\Models\Miembros;
use App\Models\Servicio;

class MedidoresSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener miembros y servicios con medidor
        $miembros = Miembros::all();
        $serviciosConMedidor = Servicio::where('tiene_medidor', true)->get();

        if ($miembros->isEmpty() || $serviciosConMedidor->isEmpty()) {
            $this->command->warn('⚠️ No hay miembros o servicios con medidor disponibles');
            return;
        }

        // Crear medidores para cada miembro y cada servicio con medidor
        foreach ($miembros as $miembro) {
            foreach ($serviciosConMedidor as $servicio) {
                Medidores::create([
                    'numero_medidor' => 'MED-' . $miembro->id . '-' . $servicio->id . '-' . now()->timestamp,
                    'fecha_instalacion' => now()->subMonths(3)->toDateString(),
                    'estado' => 1,
                    'unidad_medida' => $servicio->unidad_medida,
                    'precio_unidad_medida' => $servicio->precio_por_unidad_de_medida,
                    'miembro_id' => $miembro->id,
                    'servicio_id' => $servicio->id,
                ]);
            }
        }

        $this->command->info('✅ Medidores creados exitosamente');
    }
}