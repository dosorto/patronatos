<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Miembros</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #8b5cf6; padding-bottom: 10px; }
        .org-name { font-size: 24px; font-weight: bold; color: #5b21b6; }
        .report-title { font-size: 18px; color: #666; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f5f3ff; color: #5b21b6; text-align: left; padding: 10px; font-size: 11px; border-bottom: 1px solid #ddd6fe; }
        td { padding: 10px; font-size: 11px; border-bottom: 1px solid #f5f3ff; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="org-name">{{ $organization->name ?? 'Sistema de Gestión' }}</div>
        <div class="report-title">Listado Puntual de Miembros</div>
        <div class="dates">Fecha de emisión: {{ now()->format('d/m/Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre Completo</th>
                <th>Teléfono</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $item)
                <tr>
                    <td>{{ $item->persona->dni }}</td>
                    <td>{{ $item->persona->nombre_completo }}</td>
                    <td>{{ $item->persona->telefono }}</td>
                    <td class="text-center">{{ $item->estado }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
