<?php

namespace App\Exports;

use App\Models\Proyecto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProyectosExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Proyecto::with(['miembroResponsable.miembro.persona', 'departamento', 'municipio'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre del Proyecto',
            'Tipo de Proyecto',
            'Número de Acta',
            'Fecha Aprobación Asamblea',
            'Fecha Inicio',
            'Fecha Fin',
            'Estado',
            'Responsable',
            'Cargo Responsable',
            'Departamento',
            'Municipio',
            'Beneficiarios Hombres',
            'Beneficiarios Mujeres',
            'Beneficiarios Niños',
            'Beneficiarios Familias',
            'Fecha Registro',
        ];
    }

    public function map($proyecto): array
    {
        return [
            $proyecto->id,
            $proyecto->nombre_proyecto ?? 'N/A',
            $proyecto->tipo_proyecto ?? 'N/A',
            $proyecto->numero_acta ?? 'N/A',
            $proyecto->fecha_aprobacion_asamblea?->format('d/m/Y') ?? 'N/A',
            $proyecto->fecha_inicio?->format('d/m/Y') ?? 'N/A',
            $proyecto->fecha_fin?->format('d/m/Y') ?? 'N/A',
            $proyecto->getRawOriginal('estado') == 1 ? 'Activo' : 'Inactivo',
            $proyecto->miembroResponsable?->miembro->persona->nombre_completo ?? 'N/A',
            $proyecto->miembroResponsable?->cargo ?? 'N/A',
            $proyecto->departamento?->nombre ?? 'N/A',
            $proyecto->municipio?->nombre ?? 'N/A',
            $proyecto->benef_hombres ?? 0,
            $proyecto->benef_mujeres ?? 0,
            $proyecto->benef_ninos ?? 0,
            $proyecto->benef_familias ?? 0,
            $proyecto->created_at?->format('d/m/Y H:i'),
        ];
    }
}