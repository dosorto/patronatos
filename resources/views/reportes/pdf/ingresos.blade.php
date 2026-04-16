<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Ingresos</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; }
        .org-name { font-size: 24px; font-weight: bold; color: #1e40af; }
        .report-title { font-size: 18px; color: #666; margin-top: 5px; }
        .dates { font-size: 12px; color: #888; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8fafc; color: #475569; text-align: left; padding: 10px; font-size: 12px; border-bottom: 1px solid #e2e8f0; }
        td { padding: 10px; font-size: 11px; border-bottom: 1px solid #f1f5f9; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: right; font-size: 14px; font-weight: bold; }
        .total-box { background-color: #eff6ff; padding: 10px; border-radius: 5px; display: inline-block; }
    </style>
</head>
<body>
    <div class="header">
        <div class="org-name">{{ $organization->name ?? 'Sistema de Gestión' }}</div>
        <div class="report-title">Reporte Detallado de Ingresos (Cobros)</div>
        <div class="dates">Período: {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Miembro</th>
                <th>Concepto</th>
                <th class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $item)
                <tr>
                    <td>{{ $item->fecha_cobro->format('d/m/Y') }}</td>
                    <td>{{ $item->miembro->persona->nombre_completo ?? 'N/A' }}</td>
                    <td>
                        @php
                            $conceptsList = [];
                            if($item->detallesCobros) {
                                foreach($item->detallesCobros as $d) {
                                    $conceptsList[] = ($d->servicio->nombre ?? '') . ($d->concepto ? ' (' . $d->concepto . ')' : '');
                                }
                            }
                            if($item->aportaciones) {
                                foreach($item->aportaciones as $a) {
                                    if($a->proyecto) $conceptsList[] = "Aporte: " . $a->proyecto->nombre;
                                }
                            }
                            echo implode(', ', array_filter($conceptsList)) ?: $item->tipo_cobro;
                        @endphp
                    </td>
                    <td class="text-right">L. {{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="total-box">
            TOTAL INGRESOS: L. {{ number_format($summary['total'], 2) }}
        </div>
    </div>
</body>
</html>
