<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 🔹 Crear permisos
        $permissions = [
            ['name' => 'roles.view', 'display_name' => 'Ver Roles'],
            ['name' => 'roles.create', 'display_name' => 'Crear Roles'],
            ['name' => 'roles.edit', 'display_name' => 'Editar Roles'],
            ['name' => 'roles.delete', 'display_name' => 'Eliminar Roles'],

            ['name' => 'users.view', 'display_name' => 'Ver Usuarios'],
            ['name' => 'users.create', 'display_name' => 'Crear Usuarios'],
            ['name' => 'users.edit', 'display_name' => 'Editar Usuarios'],
            ['name' => 'users.delete', 'display_name' => 'Eliminar Usuarios'],

            ['name' => 'estudiantes.view', 'display_name' => 'Ver Estudiantes'],
            ['name' => 'estudiantes.create', 'display_name' => 'Crear Estudiantes'],
            ['name' => 'estudiantes.edit', 'display_name' => 'Editar Estudiantes'],
            ['name' => 'estudiantes.delete', 'display_name' => 'Eliminar Estudiantes'],
            ['name' => 'estudiantes.export', 'display_name' => 'Exportar Estudiantes (Excel)'],

            ['name' => 'tipoactivo.view', 'display_name' => 'Ver Tipos de Activo'],
            ['name' => 'tipoactivo.create', 'display_name' => 'Crear Tipos de Activo'],
            ['name' => 'tipoactivo.edit', 'display_name' => 'Editar Tipos de Activo'],
            ['name' => 'tipoactivo.delete', 'display_name' => 'Eliminar Tipos de Activo'],
            ['name' => 'tipoactivo.export', 'display_name' => 'Exportar Tipos de Activo (Excel)'],

            ['name' => 'personas.view', 'display_name' => 'Ver Personas'],
            ['name' => 'personas.create', 'display_name' => 'Crear Personas'],
            ['name' => 'personas.edit', 'display_name' => 'Editar Personas'],
            ['name' => 'personas.delete', 'display_name' => 'Eliminar Personas'],
            ['name' => 'personas.export', 'display_name' => 'Exportar Personas (Excel)'],
            ['name' => 'audit.view', 'display_name' => 'Ver Logs del Sistema'],
            ['name' => 'audit.export', 'display_name' => 'Exportar Logs del Sistema'],

            // Permisos para municipio
            ['name' => 'municipio.view', 'display_name' => 'Ver Municipios'],
            ['name' => 'municipio.create', 'display_name' => 'Crear Municipios'],
            ['name' => 'municipio.edit', 'display_name' => 'Editar Municipios'],
            ['name' => 'municipio.delete', 'display_name' => 'Eliminar Municipios'],
            ['name' => 'municipio.export', 'display_name' => 'Exportar Municipios (Excel)'],

            // Permisos para países
            ['name' => 'pais.view', 'display_name' => 'Ver Países'],
            ['name' => 'pais.create', 'display_name' => 'Crear Países'],
            ['name' => 'pais.edit', 'display_name' => 'Editar Países'],
            ['name' => 'pais.delete', 'display_name' => 'Eliminar Países'],
            ['name' => 'pais.export', 'display_name' => 'Exportar Países (Excel)'],

            ['name' => 'departamento.view', 'display_name' => 'Ver Departamentos'],
            ['name' => 'departamento.create', 'display_name' => 'Crear Departamentos'],
            ['name' => 'departamento.edit', 'display_name' => 'Editar Departamentos'],
            ['name' => 'departamento.delete', 'display_name' => 'Eliminar Departamentos'],
            ['name' => 'departamento.export', 'display_name' => 'Exportar Departamentos (Excel)'],

            // Permisos para organización
            ['name' => 'organization.view', 'display_name' => 'Ver Organizaciones'],
            ['name' => 'organization.create', 'display_name' => 'Crear Organizaciones'],
            ['name' => 'organization.edit', 'display_name' => 'Editar Organizaciones'],
            ['name' => 'organization.delete', 'display_name' => 'Eliminar Organizaciones'],
            ['name' => 'organization.export', 'display_name' => 'Exportar organizacion (Excel)'],

            // Permisos para miembros
            ['name' => 'miembro.view', 'display_name' => 'Ver Miembros'],
            ['name' => 'miembro.create', 'display_name' => 'Crear Miembros'],
            ['name' => 'miembro.edit', 'display_name' => 'Editar Miembros'],
            ['name' => 'miembro.delete', 'display_name' => 'Eliminar Miembros'],
            ['name' => 'miembro.export', 'display_name' => 'Exportar Miembros (Excel)'],

            // Permisos para empleados
            ['name' => 'empleado.view', 'display_name' => 'Ver Empleados'],
            ['name' => 'empleado.create', 'display_name' => 'Crear Empleados'],
            ['name' => 'empleado.edit', 'display_name' => 'Editar Empleados'],
            ['name' => 'empleado.delete', 'display_name' => 'Eliminar Empleados'],
            ['name' => 'empleado.export', 'display_name' => 'Exportar Empleados (Excel)'],

            // Permisos para directiva
            ['name' => 'directiva.view', 'display_name' => 'Ver Directiva'],
            ['name' => 'directiva.create', 'display_name' => 'Crear Directiva'],
            ['name' => 'directiva.edit', 'display_name' => 'Editar Directiva'],
            ['name' => 'directiva.delete', 'display_name' => 'Eliminar Directiva'],
            ['name' => 'directiva.export', 'display_name' => 'Exportar Directiva (Excel)'],

            ['name' => 'cooperantes.view', 'display_name' => 'Ver cooperantes'],
            ['name' => 'cooperantes.create', 'display_name' => 'Crear cooperantes'],
            ['name' => 'cooperantes.edit', 'display_name' => 'Editar cooperantes'],
            ['name' => 'cooperantes.delete', 'display_name' => 'Eliminar cooperantes'],
            ['name' => 'cooperantes.export', 'display_name' => 'Exportar cooperantes (Excel)'],

            ['name' => 'servicios.view',   'display_name' => 'Ver servicios'],
            ['name' => 'servicios.create', 'display_name' => 'Crear servicios'],
            ['name' => 'servicios.edit',   'display_name' => 'Editar servicios'],
            ['name' => 'servicios.delete', 'display_name' => 'Eliminar servicios'],
            ['name' => 'servicios.export', 'display_name' => 'Exportar servicios (Excel)'],


            // Permisos para activos
            ['name' => 'activo.view', 'display_name' => 'Ver Activos'],
            ['name' => 'activo.create', 'display_name' => 'Crear Activos'],
            ['name' => 'activo.edit', 'display_name' => 'Editar Activos'],
            ['name' => 'activo.delete', 'display_name' => 'Eliminar Activos'],
            ['name' => 'activo.export', 'display_name' => 'Exportar Activos (Excel)'],

            // Permisos para proyectos
            ['name' => 'proyecto.view', 'display_name' => 'Ver Proyectos'],
            ['name' => 'proyecto.create', 'display_name' => 'Crear Proyectos'],
            ['name' => 'proyecto.edit', 'display_name' => 'Editar Proyectos'],
            ['name' => 'proyecto.delete', 'display_name' => 'Eliminar Proyectos'],
            ['name' => 'proyecto.export', 'display_name' => 'Exportar Proyectos (Excel)'],
            ['name' => 'proyecto.aportes.manage', 'display_name' => 'Administrar Aportes del Proyecto'],
            ['name' => 'proyecto.jornadas.manage', 'display_name' => 'Administrar Jornadas del Proyecto'],

            // Permisos para cobros
            ['name' => 'cobro.view', 'display_name' => 'Ver Cobros'],
            ['name' => 'cobro.create', 'display_name' => 'Crear Cobros'],
            ['name' => 'cobro.edit', 'display_name' => 'Editar Cobros'],
            ['name' => 'cobro.delete', 'display_name' => 'Eliminar Cobros'],
            ['name' => 'cobro.export', 'display_name' => 'Exportar Cobros (Excel)'],

            // Permisos para aportaciones
            ['name' => 'aportacion.view',   'display_name' => 'Ver Aportaciones'],
            ['name' => 'aportacion.create', 'display_name' => 'Crear Aportaciones'],
            ['name' => 'aportacion.edit',   'display_name' => 'Editar Aportaciones'],
            ['name' => 'aportacion.delete', 'display_name' => 'Eliminar Aportaciones'],
            ['name' => 'aportacion.export', 'display_name' => 'Exportar Aportaciones (Excel)'],

            // Permisos para mantenimientos
            ['name' => 'mantenimiento.view',   'display_name' => 'Ver Mantenimientos'],
            ['name' => 'mantenimiento.create', 'display_name' => 'Crear Mantenimientos'],
            ['name' => 'mantenimiento.edit',   'display_name' => 'Editar Mantenimientos'],
            ['name' => 'mantenimiento.delete', 'display_name' => 'Eliminar Mantenimientos'],
            ['name' => 'mantenimiento.export', 'display_name' => 'Exportar Mantenimientos (Excel)'],

            // Permisos para pagos
            ['name' => 'pago.view',   'display_name' => 'Ver Pagos'],
            ['name' => 'pago.create', 'display_name' => 'Crear Pagos'],
            ['name' => 'pago.edit',   'display_name' => 'Editar Pagos'],
            ['name' => 'pago.delete', 'display_name' => 'Eliminar Pagos'],
            ['name' => 'pago.export', 'display_name' => 'Exportar Pagos (Excel)'],

            // Permisos para reportes
            ['name' => 'reportes.view', 'display_name' => 'Ver Reportes'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::updateOrCreate(
                ['name' => $permissionData['name']],
                ['display_name' => $permissionData['display_name']]
            );
        }
        // 🔹 Crear rol root
        $rootRole = Role::firstOrCreate(['name' => 'root']);

        // 🔹 Asignar TODOS los permisos al root
        $rootRole->syncPermissions(Permission::all());
        

        // 🔹 Asignar rol root al usuario ID = 1
        $user = User::find(1);

        if ($user) {
            $user->assignRole($rootRole);
        }

        // 🔹 Crear rol admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // 🔹 Seleccionar permisos específicos para admin
        $adminPermissions = Permission::whereIn('name', [

            // Configuración — necesario para acceder al módulo
            'roles.view',
            // Organización ✅ AGREGADO
            'organization.view', 'organization.edit',
            // Miembros
            'miembro.view', 'miembro.create', 'miembro.edit', 'miembro.delete', 'miembro.export',
            // Empleados
            'empleado.view', 'empleado.create', 'empleado.edit', 'empleado.delete', 'empleado.export',
            // Activos
            'activo.view', 'activo.create', 'activo.edit', 'activo.delete', 'activo.export',
            // Directiva
            'directiva.view', 'directiva.create', 'directiva.edit', 'directiva.delete', 'directiva.export',
            // Cooperante
            'cooperantes.view', 'cooperantes.create', 'cooperantes.edit', 'cooperantes.delete', 'cooperantes.export',
            // Proyectos
            'proyecto.view', 'proyecto.create', 'proyecto.edit', 'proyecto.delete', 'proyecto.export',
            'proyecto.aportes.manage', 'proyecto.jornadas.manage',
            // Proyectos
            'servicios.view', 'servicios.create', 'servicios.edit', 'servicios.delete', 'servicios.export',
            // Usuarios
            'users.view', 'users.create', 'users.edit', 'users.delete',
            // Cobros
            'cobro.view', 'cobro.create', 'cobro.edit', 'cobro.delete', 'cobro.export',
            'audit.view', 'audit.export',
            // Aportaciones
            'aportacion.view', 'aportacion.create', 'aportacion.edit', 'aportacion.delete', 'aportacion.export',
            // Mantenimientos
            'mantenimiento.view', 'mantenimiento.create', 'mantenimiento.edit', 'mantenimiento.delete', 'mantenimiento.export',
            // Pagos
            'pago.view', 'pago.create', 'pago.edit', 'pago.delete', 'pago.export',
            // Reportes
            'reportes.view',
        ])->get();

        // 🔹 Asignar estos permisos al rol admin
        $adminRole->syncPermissions($adminPermissions);

    }
}
