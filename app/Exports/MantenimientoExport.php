<?php

namespace App\Exports;

use App\Models\Mantenimiento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MantenimientoExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Mantenimiento::with('activo')
            ->where('organization_id', session('tenant_organization_id'))
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Activo',
            'Tipo de Mantenimiento',
            'Descripción',
            'Prioridad',
            'Fecha de Registro',
            'Estado',
            'Costo Estimado',
        ];
    }

    public function map($mantenimiento): array
    {
        return [
            $mantenimiento->id,
            $mantenimiento->activo ? $mantenimiento->activo->nombre : 'Mantenimiento General',
            $mantenimiento->tipo_mantenimiento,
            $mantenimiento->descripcion,
            $mantenimiento->prioridad,
            $mantenimiento->fecha_registro ? $mantenimiento->fecha_registro->format('d/m/Y') : 'N/A',
            $mantenimiento->estado,
            'L ' . number_format($mantenimiento->costo_estimado, 2),
        ];
    }
}
