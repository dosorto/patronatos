<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
<<<<<<< Updated upstream
=======
use App\Models\Persona;
use Faker\Factory as Faker;
>>>>>>> Stashed changes

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< Updated upstream
        \App\Models\Persona::factory()->count(500)->create();
=======
        $personas = [
            [
                'dni' => '0801199001234',
                'nombre' => 'Jorge',
                'apellido' => 'Munguia',
                'fecha_nacimiento' => '1990-01-08',
                'sexo' => 'M',
                'telefono' => '99991234',
                'email' => 'jorge@example.com',
                'estado' => true,
                'fecha_ingreso' => '2020-06-15',
            ],
            [
                'dni' => '0801199505678',
                'nombre' => 'Ana',
                'apellido' => 'Lopez',
                'fecha_nacimiento' => '1995-05-12',
                'sexo' => 'F',
                'telefono' => '99995678',
                'email' => 'ana@example.com',
                'estado' => true,
                'fecha_ingreso' => '2021-02-20',
            ],
            [
                'dni' => '0801198804321',
                'nombre' => 'Carlos',
                'apellido' => 'Perez',
                'fecha_nacimiento' => '1988-11-23',
                'sexo' => 'M',
                'telefono' => '99994321',
                'email' => 'carlos@example.com',
                'estado' => false,
                'fecha_ingreso' => '2019-09-10',
            ],
            [
                'dni' => '0801199208765',
                'nombre' => 'María',
                'apellido' => 'Gomez',
                'fecha_nacimiento' => '1992-07-04',
                'sexo' => 'F',
                'telefono' => '99998765',
                'email' => 'maria@example.com',
                'estado' => true,
                'fecha_ingreso' => '2022-01-05',
            ],
        ];

        foreach ($personas as $persona) {
            Persona::create($persona);
        }

        $faker = Faker::create('es_HN');

        for ($i = 0; $i < 40; $i++) {
            Persona::create([
                'dni' => $faker->unique()->numerify('080119##0####'),
                'nombre' => $faker->firstName,
                'apellido' => $faker->lastName,
                'fecha_nacimiento' => $faker->date('Y-m-d', '2000-01-01'),
                'sexo' => $faker->randomElement(['M', 'F']),
                'telefono' => $faker->numerify('9#######'),
                'email' => $faker->unique()->safeEmail,
                'estado' => $faker->boolean(80),
                'fecha_ingreso' => $faker->date('Y-m-d', 'now'),
            ]);
        }
>>>>>>> Stashed changes
    }
}
