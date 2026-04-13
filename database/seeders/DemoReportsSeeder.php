<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Persona;
use App\Models\Miembros;
use App\Models\Cobro;
use App\Models\Pago;
use App\Models\Mantenimiento;
use App\Models\Activo;
use App\Models\TipoActivo;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DemoReportsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_ES');
        
        // 1. Asegurar Organización
        $org = Organization::updateOrCreate(
            ['slug' => 'patronato-loma-linda'],
            [
                'name' => 'Patronato Loma Linda',
                'email' => 'administracion@lomalinda.org',
                'phone' => '2233-4455',
                'id_departamento' => 8, // Francisco Morazán
                'id_municipio' => 1,    // Distrito Central
                'estado' => 'Activo',
                'db_host' => '127.0.0.1',
                'db_port' => '3306',
                'db_database' => env('DB_DATABASE', 'db_base_project'),
                'db_username' => env('DB_USERNAME', 'root'),
                'db_password' => env('DB_PASSWORD', ''),
            ]
        );

        // 2. Crear Usuario Administrador para esta Organización
        $adminEmail = 'admin@lomalinda.org';
        $adminUser = \App\Models\User::where('email', $adminEmail)->first();
        if (!$adminUser) {
            $adminUser = \App\Models\User::create([
                'name' => 'Admin Loma Linda',
                'email' => $adminEmail,
                'password' => \Hash::make('password'),
                'organization_id' => $org->id,
            ]);
            $adminUser->assignRole('admin');
        }

        // 2. Crear Tipo de Activo si no existe
        $tipoActivo = TipoActivo::first();
        if (!$tipoActivo) {
            $tipoActivo = TipoActivo::create([
                'nombre' => 'Maquinaria y Equipo',
                'descripcion' => 'Equipo de mantenimiento'
            ]);
        }

        // 3. Crear Personas y Miembros
        for ($i = 0; $i < 15; $i++) {
            $persona = Persona::create([
                'dni' => $faker->unique()->numerify('0801-####-#####'),
                'nombre' => $faker->firstName,
                'apellido' => $faker->lastName,
                'fecha_nacimiento' => $faker->date('Y-m-d', '-18 years'),
                'sexo' => $faker->randomElement(['M', 'F']),
                'telefono' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'estado' => 'Habilitado'
            ]);

            Miembros::create([
                'organization_id' => $org->id,
                'persona_id' => $persona->id,
                'direccion' => $faker->address,
                'estado' => 'Activo'
            ]);
        }

        $miembros = Miembros::where('organization_id', $org->id)->get();

        // 4. Crear Activo para Mantenimientos
        $activo = Activo::create([
            'organization_id' => $org->id,
            'tipo_activo_id' => $tipoActivo->id,
            'nombre' => 'Tanque de Agua Comunal',
            'descripcion' => 'Deposito principal de agua para la comunidad',
            'estado' => true,
        ]);

        // 5. Generar datos para los últimos 6 meses
        for ($m = 0; $m < 6; $m++) {
            $baseDate = Carbon::now()->subMonths($m);
            
            // Cobros (Ingresos) - 15-20 por mes
            for ($j = 0; $j < rand(15, 20); $j++) {
                Cobro::create([
                    'organization_id' => $org->id,
                    'miembro_id' => $miembros->random()->id,
                    'fecha_cobro' => $baseDate->copy()->startOfMonth()->addDays(rand(0, 27)),
                    'tipo_cobro' => $faker->randomElement(['Aportación Mensual', 'Cuota de Vigilancia', 'Mantenimiento']),
                    'total' => $faker->randomFloat(2, 150, 600)
                ]);
            }

            // Pagos (Egresos) - 4-6 por mes
            for ($j = 0; $j < rand(4, 6); $j++) {
                Pago::create([
                    'organization_id' => $org->id,
                    'nombre_persona' => $faker->company,
                    'descripcion' => $faker->randomElement(['Pago de Energía Eléctrica', 'Reparación de Bomba', 'Servicio de Internet', 'Sueldos Administrativos', 'Compra de Pintura']),
                    'fecha_pago' => $baseDate->copy()->startOfMonth()->addDays(rand(0, 27)),
                    'tipo_pago' => 'Gasto de Operación',
                    'total' => $faker->randomFloat(2, 800, 3500)
                ]);
            }

            // Mantenimientos - 2 por mes
            for ($j = 0; $j < 2; $j++) {
                Mantenimiento::create([
                    'organization_id' => $org->id,
                    'activo_id' => $activo->id,
                    'tipo_mantenimiento' => $faker->randomElement(['Preventivo', 'Correctivo']),
                    'descripcion' => $faker->randomElement(['Limpieza de filtros', 'Revisión técnica trimestral', 'Cambio de válvulas', 'Pintura exterior']),
                    'prioridad' => $faker->randomElement(['Alta', 'Media', 'Baja']),
                    'fecha_registro' => $baseDate->copy()->startOfMonth()->addDays(rand(0, 27)),
                    'costo_estimado' => $faker->randomFloat(2, 400, 2500),
                    'estado' => 'Terminado'
                ]);
            }
        }
    }
}
