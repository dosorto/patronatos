<?php

use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\TipoActivoController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\MiembroController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\OrganizacionController;


Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {

    Route::view('dashboard', 'dashboard')
        ->middleware('verified')
        ->name('dashboard');

    Route::view('profile', 'profile')->name('profile');

    // ── Usuarios ──────────────────────────────────────────
    Route::middleware('permission:users.view')
        ->get('/users', fn () => view('users.index'))
        ->name('users.index');

    Route::middleware('permission:users.create')
        ->get('/users/create', fn () => view('users.create'))
        ->name('users.create');

    Route::middleware('permission:users.edit')
        ->get('/users/{user}/edit', fn (User $user) => view('users.edit', compact('user')))
        ->name('users.edit');

    // ── Roles ─────────────────────────────────────────────
    Route::middleware('permission:roles.view')
        ->get('/roles', fn () => view('roles.index'))
        ->name('roles.index');

    Route::middleware('permission:roles.create')
        ->get('/roles/create', fn () => view('roles.create'))
        ->name('roles.create');

    Route::middleware('permission:roles.edit')
        ->get('/roles/{role}/edit', fn (Role $role) => view('roles.edit', compact('role')))
        ->name('roles.edit');

    // ── Settings / Audit ──────────────────────────────────
    Route::middleware('permission:roles.view')
        ->get('/settings', fn () => view('settings.index'))
        ->name('settings.index');

    Route::middleware('permission:audit.view')
        ->get('/audit', fn () => view('admin.audit'))
        ->name('audit.index');

    // ── Personas ──────────────────────────────────────────
    Route::middleware('permission:personas.view')
        ->get('/personas', [PersonaController::class, 'index'])
        ->name('personas.index');

    Route::middleware('permission:personas.create')
        ->get('/personas/create', [PersonaController::class, 'create'])
        ->name('personas.create');

    Route::middleware('permission:personas.create')
        ->post('/personas', [PersonaController::class, 'store'])
        ->name('personas.store');

    Route::middleware('permission:personas.view')
        ->get('/personas/{persona}', [PersonaController::class, 'show'])
        ->name('personas.show');

    Route::middleware('permission:personas.edit')
        ->get('/personas/{persona}/edit', [PersonaController::class, 'edit'])
        ->name('personas.edit');

    Route::middleware('permission:personas.edit')
        ->put('/personas/{persona}', [PersonaController::class, 'update'])
        ->name('personas.update');

    Route::middleware('permission:personas.delete')
        ->delete('/personas/{persona}', [PersonaController::class, 'destroy'])
        ->name('personas.destroy');

    // ── Estudiantes ───────────────────────────────────────
    Route::middleware('permission:estudiantes.view')
        ->get('/estudiantes', [EstudianteController::class, 'index'])
        ->name('estudiantes.index');

    // ── Tipo Activo ───────────────────────────────────────
    Route::middleware('permission:tipoactivo.view')
        ->get('/tipoactivo/export', [TipoActivoController::class, 'exportExcel'])
        ->name('tipoactivo.export');

    Route::middleware('permission:tipoactivo.view')
        ->get('/tipoactivo', [TipoActivoController::class, 'index'])
        ->name('tipoactivo.index');

    Route::middleware('permission:tipoactivo.create')
        ->get('/tipoactivo/create', [TipoActivoController::class, 'create'])
        ->name('tipoactivo.create');

    Route::middleware('permission:tipoactivo.create')
        ->post('/tipoactivo', [TipoActivoController::class, 'store'])
        ->name('tipoactivo.store');

    Route::middleware('permission:tipoactivo.view')
        ->get('/tipoactivo/{tipoactivo}', [TipoActivoController::class, 'show'])
        ->name('tipoactivo.show');

    Route::middleware('permission:tipoactivo.edit')
        ->get('/tipoactivo/{tipoactivo}/edit', [TipoActivoController::class, 'edit'])
        ->name('tipoactivo.edit');

    Route::middleware('permission:tipoactivo.edit')
        ->put('/tipoactivo/{tipoactivo}', [TipoActivoController::class, 'update'])
        ->name('tipoactivo.update');

    Route::middleware('permission:tipoactivo.delete')
        ->delete('/tipoactivo/{tipoactivo}', [TipoActivoController::class, 'destroy'])
        ->name('tipoactivo.destroy');

    // ── País ──────────────────────────────────────────────
    Route::middleware('permission:pais.view')
        ->get('/pais/export', [PaisController::class, 'exportExcel'])
        ->name('pais.export');

    Route::middleware('permission:pais.view')
        ->get('/pais', [PaisController::class, 'index'])
        ->name('pais.index');

    Route::middleware('permission:pais.create')
        ->get('/pais/create', [PaisController::class, 'create'])
        ->name('pais.create');

    Route::middleware('permission:pais.create')
        ->post('/pais', [PaisController::class, 'store'])
        ->name('pais.store');

    Route::middleware('permission:pais.view')
        ->get('/pais/{pais}', [PaisController::class, 'show'])
        ->name('pais.show');

    Route::middleware('permission:pais.edit')
        ->get('/pais/{pais}/edit', [PaisController::class, 'edit'])
        ->name('pais.edit');

    Route::middleware('permission:pais.edit')
        ->put('/pais/{pais}', [PaisController::class, 'update'])
        ->name('pais.update');

    Route::middleware('permission:pais.delete')
        ->delete('/pais/{pais}', [PaisController::class, 'destroy'])
        ->name('pais.destroy');

    // Organizacion CRUD
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/organizacion', [OrganizacionController::class, 'index'])
            ->name('organizacion.index')
            ->middleware('permission:organizacion.view');

        Route::get('/organizacion/create', [OrganizacionController::class, 'create'])
            ->name('organizacion.create')
            ->middleware('permission:organizacion.create');

        Route::post('/organizacion', [OrganizacionController::class, 'store'])
            ->name('organizacion.store')
            ->middleware('permission:organizacion.create');

        Route::get('/organizacion/{organizacion}', [OrganizacionController::class, 'show'])
            ->name('organizacion.show')
            ->middleware('permission:organizacion.view');

        Route::get('/organizacion/{organizacion}/edit', [OrganizacionController::class, 'edit'])
            ->name('organizacion.edit')
            ->middleware('permission:organizacion.edit');

        Route::put('/organizacion/{organizacion}', [OrganizacionController::class, 'update'])
            ->name('organizacion.update')
            ->middleware('permission:organizacion.edit');

        Route::delete('/organizacion/{organizacion}', [OrganizacionController::class, 'destroy'])
            ->name('organizacion.destroy')
            ->middleware('permission:organizacion.delete');
    });
        
    // Paises CRUD
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/pais', [PaisController::class, 'index'])
            ->name('pais.index')
            ->middleware('permission:pais.view');
        
        Route::get('/pais/create', [PaisController::class, 'create'])
            ->name('pais.create')
            ->middleware('permission:pais.create');
        
        Route::post('/pais', [PaisController::class, 'store'])
            ->name('pais.store')
            ->middleware('permission:pais.create');
        
        Route::get('/pais/{pais}', [PaisController::class, 'show'])
            ->name('pais.show')
            ->middleware('permission:pais.view');
        
        Route::get('/pais/{pais}/edit', [PaisController::class, 'edit'])
            ->name('pais.edit')
            ->middleware('permission:pais.edit');
        
        Route::put('/pais/{pais}', [PaisController::class, 'update'])
            ->name('pais.update')
            ->middleware('permission:pais.edit');
        
        Route::delete('/pais/{pais}', [PaisController::class, 'destroy'])
            ->name('pais.destroy')
            ->middleware('permission:pais.delete');

        Route::get('/pais/export/excel', [App\Http\Controllers\PaisController::class, 'exportExcel'])
            ->name('pais.export')
            ->middleware('permission:pais.export'); 
    });
    
    // Departamento CRUD
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/departamento', [App\Http\Controllers\DepartamentoController::class, 'index'])
            ->name('departamento.index')
            ->middleware('permission:departamento.view');
        
        Route::get('/departamento/create', [App\Http\Controllers\DepartamentoController::class, 'create'])
            ->name('departamento.create')
            ->middleware('permission:departamento.create');
        
        Route::post('/departamento', [App\Http\Controllers\DepartamentoController::class, 'store'])
            ->name('departamento.store')
            ->middleware('permission:departamento.create');
        
        Route::get('/departamento/{departamento}', [App\Http\Controllers\DepartamentoController::class, 'show'])
            ->name('departamento.show')
            ->middleware('permission:departamento.view');
        
        Route::get('/departamento/{departamento}/edit', [App\Http\Controllers\DepartamentoController::class, 'edit'])
            ->name('departamento.edit')
            ->middleware('permission:departamento.edit');
        
        Route::put('/departamento/{departamento}', [App\Http\Controllers\DepartamentoController::class, 'update'])
            ->name('departamento.update')
            ->middleware('permission:departamento.edit');
        
        Route::delete('/departamento/{departamento}', [App\Http\Controllers\DepartamentoController::class, 'destroy'])
            ->name('departamento.destroy')
            ->middleware('permission:departamento.delete');

        Route::get('/departamento/export/excel', [App\Http\Controllers\DepartamentoController::class, 'exportExcel'])
            ->name('departamento.export')
            ->middleware('permission:departamento.export');

        
    });

    // Municipio CRUD
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/municipio', [MunicipioController::class, 'index'])
            ->name('municipio.index')
            ->middleware('permission:municipio.view');
        Route::get('/municipio/create', [MunicipioController::class, 'create'])
            ->name('municipio.create')
            ->middleware('permission:municipio.create');
        Route::post('/municipio', [MunicipioController::class, 'store'])
            ->name('municipio.store')
            ->middleware('permission:municipio.create');
        Route::get('/municipio/{municipio}', [MunicipioController::class, 'show'])
            ->name('municipio.show')
            ->middleware('permission:municipio.view');
        Route::get('/municipio/{municipio}/edit', [MunicipioController::class, 'edit'])
            ->name('municipio.edit')
            ->middleware('permission:municipio.edit');
        Route::put('/municipio/{municipio}', [MunicipioController::class, 'update'])
            ->name('municipio.update')
            ->middleware('permission:municipio.edit');
        Route::delete('/municipio/{municipio}', [MunicipioController::class, 'destroy'])
            ->name('municipio.destroy')
            ->middleware('permission:municipio.delete');
        Route::get('/municipio/export/excel', [MunicipioController::class, 'exportExcel'])
            ->name('municipio.export')
            ->middleware('permission:municipio.export');
        Route::get('municipio/departamentos/{pais}', [MunicipioController::class, 'getDepartamentos'])
            ->name('municipio.departamentos');
    
    });

    // Miembros CRUD
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/miembro', [MiembroController::class, 'index'])
            ->name('miembro.index')
            ->middleware('permission:miembro.view');
        Route::get('/miembro/create', [MiembroController::class, 'create'])
            ->name('miembro.create')
            ->middleware('permission:miembro.create');
        Route::post('/miembro', [MiembroController::class, 'store'])
            ->name('miembro.store')
            ->middleware('permission:miembro.create');
        Route::get('/miembro/{miembro}', [MiembroController::class, 'show'])
            ->name('miembro.show')
            ->middleware('permission:miembro.view');
        Route::get('/miembro/{miembro}/edit', [MiembroController::class, 'edit'])
            ->name('miembro.edit')
            ->middleware('permission:miembro.edit');
        Route::put('/miembro/{miembro}', [MiembroController::class, 'update'])
            ->name('miembro.update')
            ->middleware('permission:miembro.edit');
        Route::delete('/miembro/{miembro}', [MiembroController::class, 'destroy'])
            ->name('miembro.destroy')
            ->middleware('permission:miembro.delete');
        Route::get('/miembro/export/excel', [MiembroController::class, 'exportExcel'])
            ->name('miembro.export')
            ->middleware('permission:miembro.export');
        // Rutas para filtro de pais, departamento y municipio
        Route::get('/departamentos-por-pais/{pais}', [MiembroController::class, 'getDepartamentos'])->name('departamentos.por.pais');
        Route::get('/municipios-por-departamento/{departamento}', [MiembroController::class, 'getMunicipios'])->name('municipios.por.departamento');
            
    });


    // Empleado CRUD
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/empleado', [App\Http\Controllers\EmpleadoController::class, 'index'])
            ->name('empleado.index')
            ->middleware('permission:empleado.view');
        Route::get('/empleado/create', [App\Http\Controllers\EmpleadoController::class, 'create'])
            ->name('empleado.create')
            ->middleware('permission:empleado.create');
        Route::post('/empleado', [App\Http\Controllers\EmpleadoController::class, 'store'])
            ->name('empleado.store')
            ->middleware('permission:empleado.create');
        Route::get('/empleado/{empleado}', [App\Http\Controllers\EmpleadoController::class, 'show'])
            ->name('empleado.show')
            ->middleware('permission:empleado.view');
        Route::get('/empleado/{empleado}/edit', [App\Http\Controllers\EmpleadoController::class, 'edit'])
            ->name('empleado.edit')
            ->middleware('permission:empleado.edit');
        Route::put('/empleado/{empleado}', [App\Http\Controllers\EmpleadoController::class, 'update'])
            ->name('empleado.update')
            ->middleware('permission:empleado.edit');
        Route::delete('/empleado/{empleado}', [App\Http\Controllers\EmpleadoController::class, 'destroy'])
            ->name('empleado.destroy')
            ->middleware('permission:empleado.delete');
        Route::get('/empleado/export/excel', [App\Http\Controllers\EmpleadoController::class, 'exportExcel'])
            ->name('empleado.export')
            ->middleware('permission:empleado.export');
    });
    
});




require __DIR__.'/auth.php';