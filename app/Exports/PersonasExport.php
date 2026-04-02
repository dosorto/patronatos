<?php

namespace App\Exports;

use App\Models\Persona;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PersonasExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function query()
    {
        return Persona::query()->latest('id');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'DNI',
            'Nombre',
            'Apellido',
            'Fecha de Nacimiento',
            'Sexo',
            'Teléfono',
            'Email',
            'Estado',
            'Fecha de Ingreso',
            'Fecha de Registro',
        ];
    }

    /**
    * @var Persona $persona
    */
    public function map($persona): array
    {
        return [
            $persona->id,
            $persona->formatted_dni,
            $persona->nombre,
            $persona->apellido,
            $persona->fecha_nacimiento->format('d/m/Y'),
            $persona->sexo,
            $persona->telefono,
            $persona->email,
            $persona->estado,
            $persona->fecha_ingreso ? $persona->fecha_ingreso->format('d/m/Y') : 'N/A',
            $persona->created_at->format('d/m/Y H:i'),
        ];
    }
}
