<?php

namespace App\Exports;

use App\Models\Departamento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DepartamentosExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Departamento::with('pais')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'País',
            'Fecha de Creación',
        ];
    }

    public function map($departamento): array
    {
        return [
            $departamento->id,
            $departamento->nombre,
            $departamento->pais->nombre ?? 'N/A',
            $departamento->created_at->format('d/m/Y H:i'),
        ];
    }
}

