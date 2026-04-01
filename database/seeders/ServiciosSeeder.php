<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;
use Illuminate\Support\Facades\DB;

class ServiciosSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            // Servicios con precio fijo (sin medidor)
            [
                'nombre' => 'Agua',
                'descripcion' => 'Servicio de agua potable mensual',
                'precio' => 150.00,
                'estado' => 1,
                'tiene_medidor' => false,
                'unidad_medida' => null,
                'precio_por_unidad_de_medida' => null,
                'es_aportacion' => false,
                'proyecto_id' => null,
            ],
            [
                'nombre' => 'Seguridad',
                'descripcion' => 'Servicio de seguridad y vigilancia',
                'precio' => 200.00,
                'estado' => 1,
                'tiene_medidor' => false,
                'unidad_medida' => null,
                'precio_por_unidad_de_medida' => null,
                'es_aportacion' => false,
                'proyecto_id' => null,
            ],
            // Servicios con medidor
            [
                'nombre' => 'Energía',
                'descripcion' => 'Servicio de energía eléctrica',
                'precio' => null,
                'estado' => 1,
                'tiene_medidor' => true,
                'unidad_medida' => 'kWh',
                'precio_por_unidad_de_medida' => 5.50,
                'es_aportacion' => false,
                'proyecto_id' => null,
            ],
            [
                'nombre' => 'Limpieza',
                'descripcion' => 'Servicio de limpieza y mantenimiento',
                'precio' => null,
                'estado' => 1,
                'tiene_medidor' => true,
                'unidad_medida' => 'm²',
                'precio_por_unidad_de_medida' => 2.75,
                'es_aportacion' => false,
                'proyecto_id' => null,
            ],
            [
                'nombre' => 'Cobro Adicional',
                'descripcion' => 'Cobro por conceptos varios (daños, reparaciones, etc)',
                'precio' => null,
                'estado' => 1,
                'tiene_medidor' => false,
                'unidad_medida' => null,
                'precio_por_unidad_de_medida' => null,
                'es_aportacion' => false,
                'proyecto_id' => null,
            ],
        ];

        // Obtener el organization_id de la sesión
        $orgId = session('tenant_organization_id');

        foreach ($servicios as $servicio) {
            $servicio['organization_id'] = $orgId;
            Servicio::create($servicio);
        }

        $this->command->info('✅ 4 servicios creados exitosamente');
    }
}