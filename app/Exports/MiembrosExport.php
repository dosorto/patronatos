<?php

namespace App\Exports;

use App\Models\Miembros;
use App\Models\Organizacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MiembrosExport implements FromCollection, WithHeadings, WithMapping
{
    protected $organizacion;

    public function __construct()
    {
        $this->organizacion = Organizacion::with([
            'municipio',
        ])->first();
    }

    public function collection()
    {
        return Miembros::with(['persona'])->whereHas('persona')->get();
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Apellido',
            'Municipio',
            'Dirección',
            'Estado',
            'Fecha de Registro',
        ];
    }

    public function map($miembro): array
    {
        return [
            $miembro->persona->nombre ?? 'N/A',
            $miembro->persona->apellido ?? 'N/A',
            $this->organizacion?->municipio?->nombre ?? 'N/A',
            $miembro->direccion ?? 'N/A',
            $miembro->estado ?? 'N/A',
            $miembro->created_at?->format('d/m/Y') ?? 'N/A',
        ];
    }
}