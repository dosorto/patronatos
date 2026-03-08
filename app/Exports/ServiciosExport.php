<?php

namespace App\Exports;

use App\Models\Servicio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ServiciosExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Trae todos los servicios con sus relaciones necesarias
     */
    public function collection()
    {
        return Servicio::with('proyecto')->latest()->get();
    }

    /**
     * Define los encabezados del Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Descripción',
            'Precio',
            'Estado',
            'Tiene Medidor',
            'Unidad de Medida',
            'Precio por Unidad',
            'Es Aportación',
            'Proyecto',
            'Fecha de Creación',
        ];
    }

    /**
     * Mapea cada fila para el Excel
     */
    public function map($servicio): array
    {
        return [
            $servicio->id,
            $servicio->nombre,
            $servicio->descripcion ?? 'N/A',
            number_format($servicio->precio, 2),
            ucfirst($servicio->estado ?? 'N/A'),
            $servicio->tiene_medidor ? 'Sí' : 'No',
            $servicio->unidad_medida ?? 'N/A',
            $servicio->precio_por_unidad_de_medida
                ? number_format($servicio->precio_por_unidad_de_medida, 2)
                : 'N/A',
            $servicio->es_aportacion ? 'Sí' : 'No',
            $servicio->proyecto->nombre ?? 'N/A',
            $servicio->created_at?->format('d/m/Y H:i'),
        ];
    }
}