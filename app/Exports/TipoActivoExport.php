<?php

namespace App\Exports;

use App\Models\TipoActivo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TipoActivoExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function query()
    {
        return TipoActivo::query()->latest('id');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Descripción',
            'Fecha de Registro',
        ];
    }

    public function map($tipoActivo): array
    {
        return [
            $tipoActivo->id,
            $tipoActivo->nombre,
            $tipoActivo->descripcion ?? '-',
            $tipoActivo->created_at->format('d/m/Y H:i'),
        ];
    }
}