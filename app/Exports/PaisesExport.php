<?php

namespace App\Exports;

use App\Models\Pais;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaisesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Pais::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'ISO',
            'Fecha de Creación',
        ];
    }

    public function map($pais): array
    {
        return [
            $pais->id,
            $pais->nombre,
            $pais->iso,
            $pais->created_at?->format('d/m/Y H:i'),
        ];
    }
}