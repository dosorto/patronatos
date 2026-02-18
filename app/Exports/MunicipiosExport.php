<?php

namespace App\Exports;

use App\Models\Municipio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MunicipiosExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Municipio::with('departamento')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Departamento',
            'Fecha de Creación',
        ];
    }

    public function map($municipio): array
    {
        return [
            $municipio->id,
            $municipio->nombre,
            $municipio->departamento->nombre ?? 'N/A',
            $municipio->created_at->format('d/m/Y H:i'),
        ];
    }
}