<?php

use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\TipoActivoController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

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
        ->get('/tipoactivo', [TipoActivoController::class, 'index'])
        ->name('tipoactivo.index');

    Route::middleware('permission:tipoactivo.create')
        ->get('/tipoactivo/create', [TipoActivoController::class, 'create'])
        ->name('tipoactivo.create');

    Route::middleware('permission:tipoactivo.create')
        ->post('/tipoactivo', [TipoActivoController::class, 'store'])
        ->name('tipoactivo.store');
    
    // 👇 PRIMERO esta ruta
    Route::middleware('permission:tipoactivo.view')
        ->get('/tipoactivo/export', [TipoActivoController::class, 'exportExcel'])
        ->name('tipoactivo.export');


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

    Route::middleware('permission:pais.view')
        ->get('/pais/export', [PaisController::class, 'exportExcel'])
        ->name('pais.export');

}); // ← único cierre del grupo auth

require __DIR__.'/auth.php';