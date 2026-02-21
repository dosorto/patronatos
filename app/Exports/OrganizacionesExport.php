<?php

namespace App\Exports;

use App\Models\Organizacion;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrganizacionesExport implements FromCollection
{
    public function collection()
    {
        return Organizacion::all();
    }
}