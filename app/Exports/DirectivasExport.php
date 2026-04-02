<?php

namespace App\Exports;

use App\Models\Directiva;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DirectivasExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Trae todas las directivas con sus relaciones necesarias
    *
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Directiva::with(['miembro.persona', 'organization'])->latest()->get();
    }

    /**
    * Define los encabezados del Excel
    */
    public function headings(): array
    {
        return [
            'ID',
            'Nombre del Miembro',
            'DNI',
            'Cargo',
            'Fecha de Asignación',
        ];
    }

    /**
    * Mapea cada fila para el Excel
    */
    public function map($directiva): array
    {
        return [
            $directiva->id,
            ($directiva->miembro->persona->nombre ?? '') . ' ' . ($directiva->miembro->persona->apellido ?? ''),
            $directiva->miembro->persona->dni ?? 'N/A',
            $directiva->cargo,
            $directiva->created_at?->format('d/m/Y H:i'),
        ];
    }
}
