<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Moras</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ef4444; padding-bottom: 10px; }
        .org-name { font-size: 24px; font-weight: bold; color: #b91c1c; }
        .report-title { font-size: 18px; color: #666; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #fef2f2; color: #b91c1c; text-align: left; padding: 10px; font-size: 11px; border-bottom: 1px solid #fee2e2; }
        td { padding: 10px; font-size: 11px; border-bottom: 1px solid #fef2f2; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: right; font-size: 14px; font-weight: bold; }
        .total-box { background-color: #fef2f2; padding: 10px; border-radius: 5px; display: inline-block; border: 1px solid #fee2e2; color: #b91c1c; }
    </style>
</head>
<body>
    <div class="header">
        <div class="org-name">{{ $organization->name ?? 'Sistema de Gestión' }}</div>
        <div class="report-title">Reporte de Moras Pendientes</div>
        <div class="dates">Fecha de emisión: {{ now()->format('d/m/Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Miembro</th>
                <th>DNI</th>
                <th class="text-right">Monto Pendiente</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $item)
                <tr>
                    <td>{{ $item->miembro->persona->nombre_completo ?? 'N/A' }}</td>
                    <td>{{ $item->miembro->persona->dni ?? 'N/A' }}</td>
                    <td class="text-right">L. {{ number_format($item->monto, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="total-box">
            TOTAL PENDIENTE: L. {{ number_format($summary['total_pendiente'], 2) }}
        </div>
    </div>
</body>
</html>
