<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MantenimientoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            ['name' => 'mantenimiento.view',   'display_name' => 'Ver Mantenimientos'],
            ['name' => 'mantenimiento.create', 'display_name' => 'Crear Mantenimientos'],
            ['name' => 'mantenimiento.edit',   'display_name' => 'Editar Mantenimientos'],
            ['name' => 'mantenimiento.delete', 'display_name' => 'Eliminar Mantenimientos'],
            ['name' => 'mantenimiento.export', 'display_name' => 'Exportar Mantenimientos (Excel)'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::updateOrCreate(
                ['name' => $permissionData['name']],
                ['display_name' => $permissionData['display_name']]
            );
        }

        $rootRole = Role::where('name', 'root')->first();
        if ($rootRole) {
            $rootRole->syncPermissions(Permission::all());
        }

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminPermissions = Permission::whereIn('name', [
                'mantenimiento.view', 'mantenimiento.create', 'mantenimiento.edit', 'mantenimiento.delete', 'mantenimiento.export'
            ])->get();
            $adminRole->givePermissionTo($adminPermissions);
        }
    }
}
