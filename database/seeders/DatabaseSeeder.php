<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            TipoActivoSeeder::class,   
            PaisSeeder::class,
            DepartamentoSeeder::class,
            MunicipioSeeder::class,
            TipoOrganizacionSeeder::class,
        ]);

        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Root Admin',
                'password' => \Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole('root');
    }

    
}
