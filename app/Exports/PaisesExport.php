<?php

namespace App\Exports;

use App\Models\Pais;
use Maatwebsite\Excel\Concerns\FromCollection;

class PaisesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Pais::all();
    }
}
