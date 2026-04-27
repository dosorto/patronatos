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
use App\Http\Controllers\CooperanteController;
use App\Http\Controllers\ActivoController;
use App\Http\Controllers\ServicioController;
use App\Livewire\Servicio\ServicioIndex;
use App\Http\Controllers\CobroController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AportacionController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\TesoreriaController;
use App\Livewire\Mora\MoraIndex;


Route::view('/', 'welcome');
Route::view('/servicio-suspendido', 'servicio-suspendido')->name('servicio.suspendido');


Route::middleware(['auth'])->group(function () {

    Route::get('/configuracioninicial', function () {
        
        return view('configuracioninicial');
    })->name('configuracioninicial');

    Route::get('dashboard', function () {
        $orgId = session('tenant_organization_id');
        
        if (!$orgId) {
            return redirect('/');
        }

        $month = request('month', date('n'));
        $year  = request('year', date('Y'));
        
        $organization  = \App\Models\Organization::on('mysql')->find($orgId);
        $totalMiembros = \App\Models\Miembros::where('organization_id', $orgId)->count();
        $totalActivos  = \App\Models\Activo::where('organization_id', $orgId)->count();
        $totalProyectos = \App\Models\Proyecto::where('organization_id', $orgId)->count();
        $totalServicios = \App\Models\Servicio::where('organization_id', $orgId)->count();
        $totalDirectiva = \App\Models\Directiva::where('organization_id', $orgId)->count();
        
        // Configuraciones pendientes
        $configStatus = [
            'logo' => (bool)($organization && $organization->logo),
            'directiva' => $totalDirectiva > 0,
            'miembros' => $totalMiembros > 0,
            'servicios' => $totalServicios > 0,
        ];

        // Finanzas filtradas por mes
        $totalIngresos = \App\Models\Cobro::where('organization_id', $orgId)
            ->whereMonth('fecha_cobro', $month)
            ->whereYear('fecha_cobro', $year)
            ->sum('total');

        $totalEgresos  = \App\Models\Pago::where('organization_id', $orgId)
            ->whereMonth('fecha_pago', $month)
            ->whereYear('fecha_pago', $year)
            ->sum('total');

        $balance       = $totalIngresos - $totalEgresos;

        return view('dashboard', compact(
            'organization', 
            'totalMiembros', 
            'totalActivos', 
            'totalProyectos', 
            'totalServicios',
            'totalIngresos',
            'totalEgresos',
            'balance',
            'configStatus',
            'month',
            'year'
        ));
    })->middleware(['verified'])->name('dashboard');

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

    Route::get('/configuracion/organizacion/editar', [OrganizationController::class, 'edit'])->name('organization.edit');
    Route::put('/configuracion/organizacion/update', [OrganizationController::class, 'update'])->name('organization.update');
        

    Route::middleware('permission:audit.view')
        ->get('/audit', fn () => view('admin.audit'))
        ->name('audit.index');

    // ── Personas ──────────────────────────────────────────
    Route::get('/personas/buscar', [App\Http\Controllers\PersonaController::class, 'buscar'])->name('personas.buscar');
    Route::get('/personas/dni/{dni}', [App\Http\Controllers\PersonaController::class, 'findByDni'])->name('personas.findByDni');
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

    
    // Cooperante CRUD
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/cooperante', [App\Http\Controllers\CooperanteController::class, 'index'])
            ->name('cooperantes.index')
            ->middleware('permission:cooperantes.view');
        
        Route::get('/cooperante/create', [App\Http\Controllers\CooperanteController::class, 'create'])
            ->name('cooperantes.create')
            ->middleware('permission:cooperantes.create');
        
        Route::post('/cooperante/quick-store', [App\Http\Controllers\CooperanteController::class, 'quickStore'])
            ->name('cooperantes.quick-store')
            ->middleware('permission:cooperantes.create');

        Route::post('/cooperante', [App\Http\Controllers\CooperanteController::class, 'store'])
            ->name('cooperantes.store')
            ->middleware('permission:cooperantes.create');
        
        Route::get('/cooperante/{cooperante}', [App\Http\Controllers\CooperanteController::class, 'show'])
            ->name('cooperantes.show')
            ->middleware('permission:cooperantes.view');
        
        Route::get('/cooperante/{cooperante}/edit', [App\Http\Controllers\CooperanteController::class, 'edit'])
            ->name('cooperantes.edit')
            ->middleware('permission:cooperantes.edit');
        
        Route::put('/cooperante/{cooperante}', [App\Http\Controllers\CooperanteController::class, 'update'])
            ->name('cooperantes.update')
            ->middleware('permission:cooperantes.edit');
        
        Route::delete('/cooperante/{cooperante}', [App\Http\Controllers\CooperanteController::class, 'destroy'])
            ->name('cooperantes.destroy')
            ->middleware('permission:cooperantes.delete');

        Route::get('/cooperante/export/excel', [App\Http\Controllers\CooperanteController::class, 'exportExcel'])
            ->name('cooperantes.export')
            ->middleware('permission:cooperantes.export');
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
    Route::post('/miembros/crear-persona-miembro', [MiembroController::class, 'crearPersonaMiembro'])
        ->name('miembro.crearPersonaMiembro')
        ->middleware('auth');
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

    // Proyecto CRUD
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/proyecto', [App\Http\Controllers\ProyectoController::class, 'index'])
            ->name('proyecto.index')
            ->middleware('permission:proyecto.view');
        Route::get('/proyecto/create', [App\Http\Controllers\ProyectoController::class, 'create'])
            ->name('proyecto.create')
            ->middleware('permission:proyecto.create');
        Route::post('/proyecto', [App\Http\Controllers\ProyectoController::class, 'store'])
            ->name('proyecto.store')
            ->middleware('permission:proyecto.create');
        Route::get('/proyecto/{proyecto}', [App\Http\Controllers\ProyectoController::class, 'show'])
            ->name('proyecto.show')
            ->middleware('permission:proyecto.view');
        Route::get('/proyecto/{proyecto}/pdf', [App\Http\Controllers\ProyectoController::class, 'exportPdf'])
            ->name('proyecto.pdf')
            ->middleware('permission:proyecto.view');
        Route::get('/proyecto/{proyecto}/edit', [App\Http\Controllers\ProyectoController::class, 'edit'])
            ->name('proyecto.edit')
            ->middleware('permission:proyecto.edit');
        Route::put('/proyecto/{proyecto}', [App\Http\Controllers\ProyectoController::class, 'update'])
            ->name('proyecto.update')
            ->middleware('permission:proyecto.edit');
        Route::delete('/proyecto/{proyecto}', [App\Http\Controllers\ProyectoController::class, 'destroy'])
            ->name('proyecto.destroy')
            ->middleware('permission:proyecto.delete');
        Route::get('/proyecto/export/excel', [App\Http\Controllers\ProyectoController::class, 'exportExcel'])
            ->name('proyecto.export')
            ->middleware('permission:proyecto.export');
        Route::patch('/proyecto/{proyecto}/estado', [App\Http\Controllers\ProyectoController::class, 'cambiarEstado'])
            ->name('proyecto.estado')
            ->middleware('permission:proyecto.edit');


        // ── Aportaciones del Proyecto ──
        Route::post('/proyecto/{proyecto}/aportes/configurar', [App\Http\Controllers\ProyectoAporteController::class, 'configurar'])
            ->name('proyecto.aportes.configurar')
            ->middleware('permission:proyecto.aportes.manage');


        // ── Jornadas de Trabajo ──
        Route::post('/proyecto/{proyecto}/jornadas', [App\Http\Controllers\ProyectoJornadaController::class, 'store'])
            ->name('proyecto.jornadas.store')
            ->middleware('permission:proyecto.jornadas.manage');
        Route::get('/proyecto/{proyecto}/jornadas/{jornada}', [App\Http\Controllers\ProyectoJornadaController::class, 'show'])
            ->name('proyecto.jornadas.show')
            ->middleware('permission:proyecto.jornadas.manage');
        Route::post('/proyecto/{proyecto}/jornadas/{jornada}/lista', [App\Http\Controllers\ProyectoJornadaController::class, 'guardarLista'])
            ->name('proyecto.jornadas.lista')
            ->middleware('permission:proyecto.jornadas.manage');
        Route::patch('/proyecto/{proyecto}/jornadas/{jornada}/cerrar', [App\Http\Controllers\ProyectoJornadaController::class, 'cerrar'])
            ->name('proyecto.jornadas.cerrar')
            ->middleware('permission:proyecto.jornadas.manage');
        Route::get('/proyecto/{proyecto}/jornadas/{jornada}/pdf', [App\Http\Controllers\ProyectoJornadaController::class, 'exportPdf'])
            ->name('proyecto.jornadas.pdf')
            ->middleware('permission:proyecto.jornadas.manage');
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

    // Directiva CRUD
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/directiva', [App\Http\Controllers\DirectivaController::class, 'index'])
            ->name('directiva.index')
            ->middleware('permission:directiva.view');
        Route::get('/directiva/search', [App\Http\Controllers\DirectivaController::class, 'search'])
            ->name('directiva.search')
            ->middleware('permission:directiva.create');
        Route::get('/directiva/create', [App\Http\Controllers\DirectivaController::class, 'create'])
            ->name('directiva.create')
            ->middleware('permission:directiva.create');
        Route::post('/directiva/quick-member', [App\Http\Controllers\DirectivaController::class, 'storeQuickMember'])
            ->name('directiva.quick-member')
            ->middleware('permission:miembro.create');
        Route::post('/directiva/assign-cargo', [App\Http\Controllers\DirectivaController::class, 'assignCargo'])
            ->name('directiva.assign-cargo')
            ->middleware('permission:directiva.create');
        Route::post('/directiva', [App\Http\Controllers\DirectivaController::class, 'store'])
            ->name('directiva.store')
            ->middleware('permission:directiva.create');
        Route::get('/directiva/{directiva}', [App\Http\Controllers\DirectivaController::class, 'show'])
            ->name('directiva.show')
            ->middleware('permission:directiva.view');
        Route::get('/directiva/{directiva}/edit', [App\Http\Controllers\DirectivaController::class, 'edit'])
            ->name('directiva.edit')
            ->middleware('permission:directiva.edit');
        Route::put('/directiva/{directiva}', [App\Http\Controllers\DirectivaController::class, 'update'])
            ->name('directiva.update')
            ->middleware('permission:directiva.edit');
        Route::delete('/directiva/{directiva}', [App\Http\Controllers\DirectivaController::class, 'destroy'])
            ->name('directiva.destroy')
            ->middleware('permission:directiva.delete');
    });
    
    // Activo CRUD
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/activo', [ActivoController::class, 'index'])
            ->name('activo.index')
            ->middleware('permission:activo.view');
        Route::get('/activo/create', [ActivoController::class, 'create'])
            ->name('activo.create')
            ->middleware('permission:activo.create');
        Route::post('/activo', [ActivoController::class, 'store'])
            ->name('activo.store')
            ->middleware('permission:activo.create');
        Route::get('/activo/{activo}', [ActivoController::class, 'show'])
            ->name('activo.show')
            ->middleware('permission:activo.view');
        Route::get('/activo/{activo}/edit', [ActivoController::class, 'edit'])
            ->name('activo.edit')
            ->middleware('permission:activo.edit');
        Route::put('/activo/{activo}', [ActivoController::class, 'update'])
            ->name('activo.update')
            ->middleware('permission:activo.edit');
        Route::delete('/activo/{activo}', [ActivoController::class, 'destroy'])
            ->name('activo.destroy')
            ->middleware('permission:activo.delete');
        Route::get('/activo/export/excel', [ActivoController::class, 'exportExcel'])
            ->name('activo.export')
            ->middleware('permission:activo.export');
    });

    // Servicio CRUD
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/servicio', [App\Http\Controllers\ServicioController::class, 'index'])
            ->name('servicios.index')
            ->middleware('permission:servicios.view');

        Route::get('/servicio/create', [App\Http\Controllers\ServicioController::class, 'create'])
            ->name('servicios.create')
            ->middleware('permission:servicios.create');

        Route::post('/servicio', [App\Http\Controllers\ServicioController::class, 'store'])
            ->name('servicios.store')
            ->middleware('permission:servicios.create');

        Route::get('/servicio/{servicio}', [App\Http\Controllers\ServicioController::class, 'show'])
            ->name('servicios.show')
            ->middleware('permission:servicios.view');

        Route::get('/servicio/{servicio}/edit', [App\Http\Controllers\ServicioController::class, 'edit'])
            ->name('servicios.edit')
            ->middleware('permission:servicios.edit');

        Route::put('/servicio/{servicio}', [App\Http\Controllers\ServicioController::class, 'update'])
            ->name('servicios.update')
            ->middleware('permission:servicios.edit');

        Route::delete('/servicio/{servicio}', [App\Http\Controllers\ServicioController::class, 'destroy'])
            ->name('servicios.destroy')
            ->middleware('permission:servicios.delete');
    });

    // Cobro CRUD
    
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/cobro', [CobroController::class, 'index'])
            ->name('cobro.index')
            ->middleware('permission:cobro.view');
        Route::get('/cobro/create', [CobroController::class, 'create'])
            ->name('cobro.create')
            ->middleware('permission:cobro.create');
        Route::post('/cobro', [CobroController::class, 'store'])
            ->name('cobro.store')
            ->middleware('permission:cobro.create');
        Route::get('/cobro/{cobro}', [CobroController::class, 'show'])
            ->name('cobro.show')
            ->middleware('permission:cobro.view');
        Route::get('/cobro/{cobro}/edit', [CobroController::class, 'edit'])
            ->name('cobro.edit')
            ->middleware('permission:cobro.edit');
        Route::put('/cobro/{cobro}', [CobroController::class, 'update'])
            ->name('cobro.update')
            ->middleware('permission:cobro.edit');
        Route::delete('/cobro/{cobro}', [CobroController::class, 'destroy'])
            ->name('cobro.destroy')
            ->middleware('permission:cobro.delete');
        Route::get('/cobro/export/excel', [CobroController::class, 'exportExcel'])
            ->name('cobro.export')
            ->middleware('permission:cobro.export');
    });

    // Aportacion CRUD

    Route::group(['middleware' => ['auth']], function () {
        Route::get('/aportacion', [AportacionController::class, 'index'])
            ->name('aportacion.index')
            ->middleware('permission:aportacion.view');
        Route::get('/aportacion/create', [AportacionController::class, 'create'])
            ->name('aportacion.create')
            ->middleware('permission:aportacion.create');
        Route::post('/aportacion', [AportacionController::class, 'store'])
            ->name('aportacion.store')
            ->middleware('permission:aportacion.create');
        Route::get('/aportacion/{aportacion}', [AportacionController::class, 'show'])
            ->name('aportacion.show')
            ->middleware('permission:aportacion.view');
        Route::get('/aportacion/{aportacion}/edit', [AportacionController::class, 'edit'])
            ->name('aportacion.edit')
            ->middleware('permission:aportacion.edit');
        Route::put('/aportacion/{aportacion}', [AportacionController::class, 'update'])
            ->name('aportacion.update')
            ->middleware('permission:aportacion.edit');
        Route::delete('/aportacion/{aportacion}', [AportacionController::class, 'destroy'])
            ->name('aportacion.destroy')
            ->middleware('permission:aportacion.delete');
        Route::get('/aportacion/export/excel', [AportacionController::class, 'exportExcel'])
            ->name('aportacion.export')
            ->middleware('permission:aportacion.export');
    });

    // Mantenimiento CRUD
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/mantenimiento', [MantenimientoController::class, 'index'])
            ->name('mantenimiento.index')
            ->middleware('permission:mantenimiento.view');

        Route::get('/mantenimiento/export/excel', [MantenimientoController::class, 'exportExcel'])
            ->name('mantenimiento.export')
            ->middleware('permission:mantenimiento.export');

        Route::get('/mantenimiento/create', [MantenimientoController::class, 'create'])
            ->name('mantenimiento.create')
            ->middleware('permission:mantenimiento.create');

        Route::post('/mantenimiento', [MantenimientoController::class, 'store'])
            ->name('mantenimiento.store')
            ->middleware('permission:mantenimiento.create');

        Route::get('/mantenimiento/{mantenimiento}', [MantenimientoController::class, 'show'])
            ->name('mantenimiento.show')
            ->middleware('permission:mantenimiento.view');

        Route::get('/mantenimiento/{mantenimiento}/edit', [MantenimientoController::class, 'edit'])
            ->name('mantenimiento.edit')
            ->middleware('permission:mantenimiento.edit');

        Route::put('/mantenimiento/{mantenimiento}', [MantenimientoController::class, 'update'])
            ->name('mantenimiento.update')
            ->middleware('permission:mantenimiento.edit');

        Route::delete('/mantenimiento/{mantenimiento}', [MantenimientoController::class, 'destroy'])
            ->name('mantenimiento.destroy')
            ->middleware('permission:mantenimiento.delete');
    });

    Route::get('/recibo/{recibo}', [ReciboController::class, 'show'])->name('recibo.show')->middleware('auth');
    Route::get('/recibo/{id}/pdf', [ReciboController::class, 'exportPdf'])->name('recibo.pdf');


    // ── Logo de la organización ────────────────────────────
    Route::post('/organization/upload-logo', [OrganizationController::class, 'uploadLogo'])
        ->name('organization.upload-logo');

    // Pago CRUD
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/pago', [PagoController::class, 'index'])
            ->name('pago.index')
            ->middleware('permission:pago.view');

        Route::get('/pago/create', [PagoController::class, 'create'])
            ->name('pago.create')
            ->middleware('permission:pago.create');

        Route::post('/pago', [PagoController::class, 'store'])
            ->name('pago.store')
            ->middleware('permission:pago.create');

        Route::get('/pago/{pago}', [PagoController::class, 'show'])
            ->name('pago.show')
            ->middleware('permission:pago.view');

        Route::get('/pago/{pago}/edit', [PagoController::class, 'edit'])
            ->name('pago.edit')
            ->middleware('permission:pago.edit');

        Route::put('/pago/{pago}', [PagoController::class, 'update'])
            ->name('pago.update')
            ->middleware('permission:pago.edit');

        Route::delete('/pago/{pago}', [PagoController::class, 'destroy'])
            ->name('pago.destroy')
            ->middleware('permission:pago.delete');

        Route::get('/pago/export/excel', [PagoController::class, 'exportExcel'])
            ->name('pago.export')
            ->middleware('permission:pago.export');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('/tesoreria', [TesoreriaController::class, 'index'])
            ->name('tesoreria.index');
    });
 
    // Mora CRUD
    Route::middleware(['auth'])->get('/moras', fn () => view('mora.index'))->name('mora.index');

    // Reportes
    Route::middleware(['auth', 'permission:reportes.view'])->get('/reportes', fn () => view('reportes.index'))->name('reportes.index');

});

    // Rutas para validar que se hizo al menos un registro en la configuracion inicial
    Route::middleware('auth')->group(function () {
        Route::get('/wizard/count/miembros',   fn() => response()->json(['count' => \App\Models\Miembros::count()]));
        Route::get('/wizard/count/directiva',  fn() => response()->json(['count' => \App\Models\Directiva::count()]));
        Route::get('/wizard/count/activos',    fn() => response()->json(['count' => \App\Models\Activo::count()]));
        Route::get('/wizard/count/servicios',  fn() => response()->json(['count' => \App\Models\Servicio::count()]));

        // Guardar configuración de meses de mora desde el wizard
        Route::post('/wizard/config/meses-mora', [OrganizationController::class, 'updateMesesMora'])
            ->name('wizard.config.meses-mora');
    });

require __DIR__.'/auth.php';