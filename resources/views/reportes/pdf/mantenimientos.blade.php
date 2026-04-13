<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Mantenimientos</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #10b981; padding-bottom: 10px; }
        .org-name { font-size: 24px; font-weight: bold; color: #065f46; }
        .report-title { font-size: 18px; color: #666; margin-top: 5px; }
        .dates { font-size: 12px; color: #888; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #ecfdf5; color: #065f46; text-align: left; padding: 10px; font-size: 10px; border-bottom: 1px solid #d1fae5; }
        td { padding: 10px; font-size: 10px; border-bottom: 1px solid #f0fdf4; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: right; font-size: 14px; font-weight: bold; }
        .total-box { background-color: #ecfdf5; padding: 10px; border-radius: 5px; display: inline-block; border: 1px solid #d1fae5; }
        .badge { padding: 2px 5px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .badge-alta { background-color: #fee2e2; color: #991b1b; }
        .badge-media { background-color: #fef3c7; color: #92400e; }
        .badge-baja { background-color: #d1fae5; color: #065f46; }
    </style>
</head>
<body>
    <div class="header">
        <div class="org-name">{{ $organization->name ?? 'Sistema de Gestión' }}</div>
        <div class="report-title">Reporte de Mantenimientos</div>
        <div class="dates">Período: {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Activo</th>
                <th>Tipo</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th class="text-right">Costo Est.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $item)
                <tr>
                    <td>{{ $item->fecha_registro->format('d/m/Y') }}</td>
                    <td>{{ $item->activo->nombre ?? 'N/A' }}</td>
                    <td>{{ $item->tipo_mantenimiento }}</td>
                    <td>
                        <span class="badge {{ strtolower($item->prioridad) == 'alta' ? 'badge-alta' : (strtolower($item->prioridad) == 'media' ? 'badge-media' : 'badge-baja') }}">
                            {{ $item->prioridad }}
                        </span>
                    </td>
                    <td>{{ $item->estado }}</td>
                    <td class="text-right">L. {{ number_format($item->costo_estimado, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="total-box">
            COSTO ESTIMADO TOTAL: L. {{ number_format($summary['total_estimado'], 2) }}
        </div>
    </div>
</body>
</html>
