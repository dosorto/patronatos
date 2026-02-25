<?php

namespace App\Exports;

use App\Models\Empleado;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmpleadosExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Trae todos los miembros con sus relaciones necesarias
    *
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Empleado::with(['persona', 'organizacion'])->get();
    }

    /**
    * Define los encabezados del Excel
    */
    public function headings(): array
    {
        return [
            'ID',
            'Nombre Empleado',
            'Apellido Empleado',
            'DNI',
            'Organización',
            'Cargo',
            'Salario'
        ];
    }

    /**
    * Mapea cada fila para el Excel
    */
    public function map($empleado): array
    {
        return [
            $empleado->id,
            $empleado->persona->nombre ?? 'N/A',
            $empleado->persona->apellido ?? 'N/A',
            $empleado->persona->dni ?? 'N/A',
            $empleado->organizacion->nombre ?? 'N/A',
            $empleado->cargo ?? 'N/A',
            // Solo colocarle el formato de moneda si el sueldo_mensual no es null
            $empleado->sueldo_mensual ? 'L. ' . number_format($empleado->sueldo_mensual, 2) : 'N/A',
            $empleado->created_at?->format('d/m/Y H:i'),
        ];
    }
}