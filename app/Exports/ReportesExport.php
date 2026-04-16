<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $results;
    protected $reportType;

    public function __construct($results, $reportType)
    {
        $this->results = $results;
        $this->reportType = $reportType;
    }

    public function collection()
    {
        return $this->results;
    }

    public function headings(): array
    {
        switch ($this->reportType) {
            case 'ingresos':
                return ['Fecha', 'Miembro', 'Identidad (DNI)', 'Concepto', 'Número de Recibo', 'Monto'];
            case 'egresos':
                return ['Fecha', 'Concepto', 'Proveedor', 'Monto'];
            case 'mantenimientos':
                return ['Fecha Reg.', 'Activo', 'Tipo', 'Prioridad', 'Costo Est.'];
            case 'miembros':
                return ['DNI', 'Nombre Completo', 'Teléfono', 'Estado'];
            case 'moras':
                return ['Miembro', 'DNI', 'Monto Pendiente'];
            default:
                return [];
        }
    }

    public function map($item): array
    {
        switch ($this->reportType) {
            case 'ingresos':
                // Concatenar conceptos de detalles
                $conceptosArr = [];
                if ($item->detallesCobros) {
                    foreach ($item->detallesCobros as $d) {
                        $c = ($d->servicio ? $d->servicio->nombre : '') . ($d->concepto ? ' (' . $d->concepto . ')' : '');
                        if ($c) $conceptosArr[] = $c;
                    }
                }
                
                // Concatenar aportaciones si existen
                if ($item->aportaciones) {
                    foreach ($item->aportaciones as $a) {
                        if ($a->proyecto) {
                            $conceptosArr[] = "Aporte: " . $a->proyecto->nombre;
                        }
                    }
                }

                $conceptos = implode(', ', $conceptosArr) ?: $item->tipo_cobro;
                
                // Concatenar números de recibo
                $recibos = $item->recibos ? $item->recibos->map(function($r) {
                    return $r->correlativo ?: "#" . $r->id;
                })->implode(', ') : 'N/A';

                return [
                    $item->fecha_cobro ? $item->fecha_cobro->format('d/m/Y') : 'N/A',
                    $item->miembro->persona->nombre_completo ?? 'N/A',
                    $item->miembro->persona->dni ?? 'N/A',
                    $conceptos,
                    $recibos,
                    $item->total
                ];

            case 'egresos':
                return [
                    $item->fecha_pago ? $item->fecha_pago->format('d/m/Y') : 'N/A',
                    $item->descripcion,
                    $item->proveedor,
                    $item->total
                ];

            case 'mantenimientos':
                return [
                    $item->fecha_registro ? $item->fecha_registro->format('d/m/Y') : 'N/A',
                    $item->activo->nombre ?? 'N/A',
                    $item->tipo_mantenimiento,
                    $item->prioridad,
                    $item->costo_estimado
                ];

            case 'miembros':
                return [
                    $item->persona->dni ?? 'N/A',
                    $item->persona->nombre_completo ?? 'N/A',
                    $item->persona->telefono ?? 'N/A',
                    $item->estado
                ];

            case 'moras':
                return [
                    $item->miembro->persona->nombre_completo ?? 'N/A',
                    $item->miembro->persona->dni ?? 'N/A',
                    $item->monto_pendiente
                ];

            default:
                return [];
        }
    }
}
