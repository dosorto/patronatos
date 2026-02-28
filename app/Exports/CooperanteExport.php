<?php

namespace App\Exports;

use App\Models\Cooperante;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CooperanteExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Cooperante::with('organizacion')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Organización',
            'Nombre',
            'Tipo de Cooperante',
            'Teléfono',
            'Dirección',
            'Fecha de Creación',
        ];
    }

    public function map($cooperante): array
    {
        return [
            $cooperante->id_cooperante,
            $cooperante->organizacion->nombre ?? 'N/A',
            $cooperante->nombre,
            $cooperante->tipo_cooperante,
            $cooperante->telefono,
            $cooperante->direccion,
            $cooperante->created_at->format('d/m/Y H:i'),
        ];
    }
}