<?php

namespace App\Exports;

use App\Models\Activo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeSheet;

class ActivosExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * Trae todos los activos con sus relaciones necesarias
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Activo::with(['tipoActivo'])->get();
    }

    /**
     * Define los encabezados del Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Descripción',
            'Tipo de Activo',
            'Ubicación',
            'Fecha de Adquisición',
            'Valor Estimado',
            'Estado',
        ];
    }

    /**
     * Mapea cada fila para el Excel
     */
    public function map($activo): array
    {
        return [
            $activo->id,
            $activo->nombre ?? 'N/A',
            $activo->descripcion ?? 'N/A',
            $activo->tipoActivo->nombre ?? 'N/A',
            $activo->ubicacion ?? 'N/A',
            $activo->fecha_adquisicion?->format('d/m/Y') ?? 'N/A',
            $activo->valor_estimado ? number_format($activo->valor_estimado, 2) : '0.00',
            $activo->estado ? 'Activo' : 'Inactivo',
        ];
    }

    /**
     * Eventos para manejar encabezados adicionales o título
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                // Aquí podrías modificar propiedades generales del Excel si quieres
                // Por ahora no necesitamos nada
            },
            BeforeSheet::class => function (BeforeSheet $event) {
                // Opcional: podrías agregar un título en la primera fila
                // $event->sheet->appendRow(['Activos de ' . auth()->user()->organization->name]);
                // Pero si no quieres columna extra, lo dejamos comentado
            },
        ];
    }
}