<?php

use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\PersonaController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\PaisController;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')
        ->middleware('verified')
        ->name('dashboard');

    Route::view('profile', 'profile')->name('profile');

    Route::middleware('permission:users.view')
        ->get('/users', fn () => view('users.index'))
        ->name('users.index');

    Route::middleware('permission:users.create')
        ->get('/users/create', fn () => view('users.create'))
        ->name('users.create');

    Route::middleware('permission:users.edit')
        ->get('/users/{user}/edit', fn (User $user) => view('users.edit', compact('user')))
        ->name('users.edit');

    Route::middleware('permission:roles.view')
        ->get('/roles', fn () => view('roles.index'))
        ->name('roles.index');

    Route::middleware('permission:roles.create')
        ->get('/roles/create', fn () => view('roles.create'))
        ->name('roles.create');

    Route::middleware('permission:roles.edit')
        ->get('/roles/{role}/edit', fn (Role $role) => view('roles.edit', compact('role')))
        ->name('roles.edit');

    Route::middleware('permission:roles.view')
        ->get('/settings', fn () => view('settings.index'))
        ->name('settings.index');

    Route::middleware('permission:audit.view')
        ->get('/audit', fn () => view('admin.audit'))
        ->name('audit.index');


    // Personas CRUD
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

    // Estudiantes
    Route::middleware('permission:estudiantes.view')
        ->get('/estudiantes', [EstudianteController::class, 'index'])
        ->name('estudiantes.index');

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

        Route::get('/pais/export', [PaisController::class, 'exportExcel'])
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
});



require __DIR__.'/auth.php';
