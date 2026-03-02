<?php

namespace App\Exports;

use App\Models\Empleado;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Organizacion;

class EmpleadosExport implements FromCollection, WithHeadings, WithMapping
{

    // Si necesitas la organización para mostrar el nombre en el Excel, puedes cargarla aquí
    protected $organizacion;

    public function __construct()
    {
        $this->organizacion = Organizacion::first();
    }

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
            // Que el dni tenga un formato de texto para evitar problemas con números largos en Excel, y si no tiene dni mostrar 'N/A'
            $empleado->persona->dni ? (string)$empleado->persona->dni : 'N/A',
             // Si la organización no tiene nombre, mostrar 'N/A'
            $this->organizacion?->nombre ?? 'N/A',
            $empleado->cargo ?? 'N/A',
            // Solo colocarle el formato de moneda si el sueldo_mensual no es null
            $empleado->sueldo_mensual ? 'L. ' . number_format($empleado->sueldo_mensual, 2) : 'N/A',
            $empleado->created_at?->format('d/m/Y H:i'),
        ];
    }
}