<?php

namespace App\Exports;

use App\Models\Cobro;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CobroExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $orgId;

    public function __construct($orgId = null)
    {
        $this->orgId = $orgId ?? session('tenant_organization_id');
    }

    public function collection()
    {
        return Cobro::with(['miembro.persona', 'detallesCobros', 'recibos'])
            ->where('organization_id', $this->orgId)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Cobro',
            'Miembro',
            'DNI',
            'Tipo de Cobro',
            'Fecha de Cobro',
            'Servicios',
            'Total',
            'Recibos',
            'Estado',
        ];
    }

    public function map($cobro): array
    {
        $servicios = $cobro->detallesCobros
            ->map(fn($d) => $d->concepto . ' (L. ' . number_format($d->monto, 2) . ')')
            ->implode(' | ');

        $recibos = $cobro->recibos
            ->pluck('nombre')
            ->implode(', ');

        return [
            $cobro->id,
            $cobro->miembro->persona->nombre . ' ' . $cobro->miembro->persona->apellido,
            $cobro->miembro->persona->dni,
            $cobro->tipo_cobro,
            $cobro->fecha_cobro->format('d/m/Y'),
            $servicios,
            'L. ' . number_format($cobro->total, 2),
            $recibos ?: 'Sin recibos',
            $cobro->estado ?? 'Activo',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2563EB']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
        ];
    }
}