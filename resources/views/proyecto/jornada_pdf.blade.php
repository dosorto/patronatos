<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Planilla de Jornada - {{ $jornada->numero_jornada }}</title>
<style>
    @page {
        margin: 60px 40px 50px 40px;
    }
    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 10.5pt;
        color: #000000;
        line-height: 1.2;
        text-align: left;
    }
    header {
        position: fixed;
        top: -45px;
        left: 0px;
        right: 0px;
        height: 50px;
        text-align: center;
        border-bottom: 0.5px solid #eee;
    }
    footer {
        position: fixed;
        bottom: -35px;
        left: 0px;
        right: 0px;
        height: 30px;
        font-size: 8.5pt;
        color: #555555;
    }
    .page-number { text-align: right; }
    .page-number:before { content: "Página " counter(page); }
    h1 {
        font-size: 16pt;
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
        margin-top: 5px;
        margin-bottom: 10px;
    }
    h2 {
        font-size: 12pt;
        font-weight: bold;
        border-bottom: 1px solid #000;
        padding-bottom: 2px;
        margin-top: 15px;
        margin-bottom: 8px;
        text-transform: uppercase;
        text-align: left;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }
    th, td {
        vertical-align: middle;
        padding: 3px 5px;
        text-align: left;
    }
    .doc-table th, .doc-table td {
        border: 1px solid #000;
        padding: 4px;
    }
    .doc-table th {
        background-color: #f3f4f6;
        font-weight: bold;
        text-align: center;
        font-size: 10.5pt;
    }
    .text-right  { text-align: right; }
    .text-center { text-align: center; }
    .bold        { font-weight: bold; }
    .info-table td {
        width: 50%;
        padding-bottom: 8px;
        vertical-align: top;
    }
    .info-label {
        font-weight: bold;
        margin-bottom: 1px;
        font-size: 10pt;
        color: #333;
    }
    .info-value {
        border-bottom: 0.5px dotted #ccc;
        padding-bottom: 1px;
        min-height: 14px;
        font-size: 12pt;
    }
</style>
</head>
<body>

    <header>
        <div style="font-size: 11pt; font-weight: bold; text-transform: uppercase;">
            {{ $proyecto->organizacion->name ?? 'Sistema de Patronatos' }}
        </div>
        <div style="font-size: 9pt;">Planilla Oficial de Asistencia - Jornada de Trabajo</div>
    </header>

    <footer>
        <table style="width: 100%; margin: 0; padding: 0; border: none;">
            <tr>
                <td style="border: none; padding: 0; font-size: 8pt; color: #555;">
                    Generado el {{ now()->format('d/m/Y H:i:s') }} | ID: {{ $jornada->id }}
                </td>
                <td style="border: none; padding: 0; text-align: right; font-size: 8pt; color: #555;" class="page-number"></td>
            </tr>
        </table>
    </footer>

    <main>
        <h1>Jornada #{{ $jornada->numero_jornada }} - {{ $proyecto->nombre_proyecto }}</h1>

        <h2>Información de la Jornada</h2>
        <table class="info-table">
            <tr>
                <td>
                    <div class="info-label">Fecha:</div>
                    <div class="info-value">{{ $jornada->fecha?->format('d/m/Y') ?? 'N/A' }}</div>
                </td>
                <td>
                    <div class="info-label">Hora de Inicio:</div>
                    <div class="info-value">{{ $jornada->hora_inicio ? \Carbon\Carbon::parse($jornada->hora_inicio)->format('H:i') : 'N/A' }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="info-label">Descripción:</div>
                    <div class="info-value">{{ $jornada->descripcion ?? 'Sin descripción detallada' }}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="info-label">Estado:</div>
                    <div class="info-value">{{ ucfirst($jornada->estado ?? 'programada') }}</div>
                </td>
                <td>
                    <div class="info-label">Total Convocados:</div>
                    <div class="info-value">{{ $jornada->asistencias->count() }} miembros</div>
                </td>
            </tr>
        </table>

        <h2>Lista de Asistencia</h2>
        <table class="doc-table">
            <thead>
                <tr>
                    <th style="width: 4%;">#</th>
                    <th style="width: 35%;">Nombre del Miembro</th>
                    <th style="width: 9%;">Asistió</th>
                    <th style="width: 9%;">Sustituto</th>
                    <th style="width: 20%;">Nombre Sustituto</th>
                    <th style="width: 23%;">Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jornada->asistencias as $idx => $asist)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td style="font-size: 9pt;">{{ $asist->miembro?->persona?->nombre_completo ?? 'N/A' }}</td>
                        <td class="text-center">
                            @if($instrumentoAplicado)
                                {{ $asist->asistio ? 'SÍ' : 'NO' }}
                            @endif
                        </td>
                        <td class="text-center">
                            @if($instrumentoAplicado)
                                {{ $asist->mando_sustituto ? 'SÍ' : 'NO' }}
                            @endif
                        </td>
                        <td style="font-size: 8.5pt;">{{ $asist->nombre_sustituto ?: '' }}</td>
                        <td style="font-size: 8.5pt;">{{ $asist->observaciones ?: '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 50px;">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 45%; border-top: 1px solid #000; text-align: center;">
                        Firma Responsable
                    </td>
                    <td style="width: 10%; border: none;"></td>
                    <td style="width: 45%; border-top: 1px solid #000; text-align: center;">
                        Sello de la Organización
                    </td>
                </tr>
            </table>
        </div>
    </main>
</body>
</html>
