<?php

namespace App\Exports;

use App\Models\Pago;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PagoExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $orgId;

    public function __construct($orgId = null)
    {
        $this->orgId = $orgId ?? session('tenant_organization_id');
    }

    public function collection()
    {
        return Pago::with([
                'persona',
                'empleado.persona',
                'detalles',
                'recibo',
            ])
            ->where('organization_id', $this->orgId)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Pago',
            'Beneficiario',
            'Tipo de Pago',
            'Fecha de Pago',
            'Conceptos',
            'Total',
            'Recibo',
            'Descripción',
        ];
    }

    public function map($pago): array
    {
        $beneficiario = $pago->nombre_persona;

        if (!$beneficiario && $pago->empleado && $pago->empleado->persona) {
            $beneficiario = trim(
                ($pago->empleado->persona->nombre ?? '') . ' ' .
                ($pago->empleado->persona->apellido ?? '')
            );
        }

        if (!$beneficiario) {
            $beneficiario = 'Varios beneficiarios';
        }

        $conceptos = $pago->detalles
            ->map(function ($d) {
                return $d->concepto . ' (L. ' . number_format($d->monto, 2) . ')';
            })
            ->implode(' | ');

        $tipoVisual = 'Otro Pago';

        if ($pago->detalles->contains(fn($d) => $d->tipo_detalle === 'salario')) {
            $tipoVisual = 'Salario';
        } elseif ($pago->detalles->contains(fn($d) => $d->tipo_detalle === 'mantenimiento')) {
            $tipoVisual = 'Mantenimiento';
        }

        return [
            $pago->id,
            $beneficiario,
            $tipoVisual,
            optional($pago->fecha_pago)->format('d/m/Y'),
            $conceptos ?: 'Sin conceptos',
            'L. ' . number_format($pago->total ?? 0, 2),
            $pago->recibo?->nombre ?? 'Sin recibo',
            $pago->descripcion ?? 'Sin descripción',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '2563EB'],
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],
            ],
        ];
    }
}