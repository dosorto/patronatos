<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use App\Models\Proyecto;

Schedule::call(function () {
    Proyecto::where('estado', 'Planificado')
        ->whereNotNull('fecha_inicio')
        ->whereDate('fecha_inicio', '<=', now()->toDateString())
        ->update(['estado' => 'En Ejecución']);
})->daily();
