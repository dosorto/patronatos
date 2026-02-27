<?php

namespace App\Exports;

use App\Models\Miembros;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MiembrosExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Trae todos los miembros con sus relaciones necesarias
    *
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Miembros::with(['persona', 'organizacion', 'municipio'])->get();
    }

    /**
    * Define los encabezados del Excel
    */
    public function headings(): array
    {
        return [
            'ID',
            'Nombre Persona',
            'Apellido Persona',
            'DNI',
            'Organización',
            'Municipio',
            'Dirección',
            'Estado',
            'Fecha de Creación',
        ];
    }

    /**
    * Mapea cada fila para el Excel
    */
    public function map($miembro): array
    {
        return [
            $miembro->id,
            $miembro->persona->nombre ?? 'N/A',
            $miembro->persona->apellido ?? 'N/A',
            $miembro->persona->dni ?? 'N/A',
            $miembro->organizacion->nombre ?? 'N/A',
            $miembro->municipio->nombre ?? 'N/A',
            $miembro->direccion,
            $miembro->estado ? 'Activo' : 'Inactivo',
            $miembro->created_at?->format('d/m/Y H:i'),
        ];
    }
}