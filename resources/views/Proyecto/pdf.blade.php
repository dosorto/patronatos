<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Proyecto - {{ $proyecto->nombre_proyecto }}</title>
    <style>
        @page {
            margin: 100px 70px 80px 70px;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000000;
            line-height: 1.5;
            text-align: left;
        }
        header {
            position: fixed;
            top: -80px;
            left: 0px;
            right: 0px;
            height: 70px;
            text-align: center;
        }
        footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            height: 40px;
            font-size: 10pt;
            color: #555555;
        }
        .page-number { text-align: right; }
        .page-number:before { content: "Página " counter(page); }
        h1 {
            font-size: 18pt;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 13pt;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-top: 25px;
            margin-bottom: 15px;
            text-transform: uppercase;
            text-align: left;
        }
        h3 {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 10px;
            text-align: left;
        }
        p {
            margin-top: 5px;
            margin-bottom: 15px;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            vertical-align: top;
            padding: 5px;
            text-align: left;
        }
        .doc-table th, .doc-table td {
            border: 1px solid #000;
            padding: 6px;
        }
        .doc-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: center;
        }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .bold        { font-weight: bold; }
        .text-gray   { color: #555555; }
        .text-sm     { font-size: 10pt; }

        /* Info grid */
        .info-table td {
            width: 50%;
            padding-bottom: 15px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            margin-bottom: 2px;
        }
        .info-value {
            border-bottom: 1px dotted #999;
            padding-bottom: 2px;
            min-height: 16px;
        }

        /* Presupuesto header */
        .pres-header-table td {
            padding: 5px;
            background-color: #f3f4f6;
            border: 1px solid #000;
            vertical-align: middle;
        }
    </style>
</head>
<body>

    <header>
        <div style="font-size: 14pt; font-weight: bold; text-transform: uppercase;">
            {{ $proyecto->organizacion->name ?? 'Sistema de Patronatos' }}
        </div>
        <div style="font-size: 12pt;">Ficha Técnica de Proyecto Oficial</div>
        <div style="font-size: 10pt; margin-top: 5px; color: #555;">
            ID: {{ str_pad($proyecto->id, 4, '0', STR_PAD_LEFT) }} &nbsp;|&nbsp; Estado: {{ $proyecto->getRawOriginal('estado') == 1 ? 'Activo' : 'Inactivo' }}
        </div>
    </header>

    <footer>
        <table style="width: 100%; margin: 0; padding: 0; border: none;">
            <tr>
                <td style="border: none; padding: 0; font-size: 10pt; color: #555;">
                    Generado el {{ now()->format('d/m/Y H:i:s') }}
                </td>
                <td style="border: none; padding: 0; text-align: right; font-size: 10pt; color: #555;" class="page-number"></td>
            </tr>
        </table>
    </footer>

    <main>
        <h1>{{ $proyecto->nombre_proyecto }}</h1>

        @if($proyecto->tipo_proyecto)
            <p style="text-align: center; font-style: italic; margin-top: -15px; margin-bottom: 20px;">
                Tipo: {{ $proyecto->tipo_proyecto }}
            </p>
        @endif

        {{-- I. Información General --}}
        <h2>I. Información General</h2>
        <table class="info-table">
            <tr>
                <td>
                    <div class="info-label">Número de Acta:</div>
                    <div class="info-value">{{ $proyecto->numero_acta ?? 'N/A' }}</div>
                </td>
                <td>
                    <div class="info-label">Responsable del Proyecto:</div>
                    <div class="info-value">
                        {{ $proyecto->miembroResponsable?->miembro->persona->nombre_completo ?? 'N/A' }}
                        @if($proyecto->miembroResponsable?->cargo)
                            ({{ $proyecto->miembroResponsable->cargo }})
                        @endif
                        <br>
                        <span style="font-size: 10pt; color: #555;">Tel: {{ $proyecto->miembroResponsable?->miembro->persona->telefono ?? 'N/A' }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="info-label">Ubicación Geográfica:</div>
                    <div class="info-value">
                        {{ $proyecto->municipio->nombre ?? '' }}{{ $proyecto->municipio && $proyecto->departamento ? ', ' : '' }}{{ $proyecto->departamento->nombre ?? 'N/A' }}
                    </div>
                </td>
            </tr>
        </table>

        {{-- II. Fechas --}}
        <h2>II. Fechas del Proyecto</h2>
        <table class="doc-table">
            <thead>
                <tr>
                    <th style="width: 33%;">Aprobación Asamblea</th>
                    <th style="width: 33%;">Fecha de Inicio</th>
                    <th style="width: 34%;">Fecha de Finalización</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">{{ $proyecto->fecha_aprobacion_asamblea?->format('d/m/Y') ?? 'N/A' }}</td>
                    <td class="text-center">{{ $proyecto->fecha_inicio?->format('d/m/Y') ?? 'N/A' }}</td>
                    <td class="text-center">{{ $proyecto->fecha_fin?->format('d/m/Y') ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- III. Descripción y Justificación --}}
        @if($proyecto->descripcion || $proyecto->justificacion)
            <h2>III. Descripción y Justificación</h2>
            @if($proyecto->descripcion)
                <p style="text-align: justify;">
                    <strong>Descripción:</strong> {{ $proyecto->descripcion }}
                </p>
            @endif
            @if($proyecto->justificacion)
                <p style="text-align: justify;">
                    <strong>Justificación:</strong> {{ $proyecto->justificacion }}
                </p>
            @endif
        @endif

        {{-- IV. Beneficiarios --}}
        <h2>IV. Beneficiarios</h2>
        @if($proyecto->descripcion_beneficiarios)
            <p style="text-align: justify;">
                <strong>Descripción:</strong> {{ $proyecto->descripcion_beneficiarios }}
            </p>
        @endif
        <table class="doc-table" style="width: 80%; margin-left: 0;">
            <thead>
                <tr>
                    <th>Hombres</th>
                    <th>Mujeres</th>
                    <th>Niños</th>
                    <th>Familias</th>
                    <th>Total Personas</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">{{ $proyecto->benef_hombres ?? 0 }}</td>
                    <td class="text-center">{{ $proyecto->benef_mujeres ?? 0 }}</td>
                    <td class="text-center">{{ $proyecto->benef_ninos ?? 0 }}</td>
                    <td class="text-center">{{ $proyecto->benef_familias ?? 0 }}</td>
                    <td class="text-center bold">
                        {{ ($proyecto->benef_hombres ?? 0) + ($proyecto->benef_mujeres ?? 0) + ($proyecto->benef_ninos ?? 0) }}
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- V. Presupuestos --}}
        <h2 style="page-break-before: always;">V. Presupuesto del Proyecto</h2>

        @if(isset($proyecto->presupuestos) && $proyecto->presupuestos->count() > 0)
            @php
                $presupuesto = $proyecto->presupuestos->first();
                $totalComunidad = $presupuesto->detalles->where('es_donacion', false)->sum('total');
                $totalFinanciador = $presupuesto->detalles->where('es_donacion', true)->sum('total');
                $granTotal = $totalComunidad + $totalFinanciador;
            @endphp
            <div style="margin-bottom: 30px;">

                {{-- Encabezado del presupuesto --}}
                <table class="pres-header-table" style="margin-bottom: 8px;">
                    <tr>
                        <td style="width: 70%;">
                            <span style="font-weight: bold; font-size: 12pt;">
                                Desglose General (Año {{ $presupuesto->anio_presupuesto ?? now()->year }})
                            </span>
                        </td>
                        <td style="width: 30%; text-align: right; font-weight: bold;">
                            Total: L. {{ number_format($granTotal, 2) }}
                        </td>
                    </tr>
                </table>

                {{-- Montos --}}
                <table style="margin-bottom: 8px;">
                    <tr>
                        <td style="width: 50%;">
                            <strong>Aporte Comunidad:</strong> L. {{ number_format($totalComunidad, 2) }}
                        </td>
                        <td style="width: 50%;">
                            <strong>Aporte Financiadores:</strong> L. {{ number_format($totalFinanciador, 2) }}
                        </td>
                    </tr>
                </table>

                {{-- Detalles --}}
                @if($presupuesto->detalles && $presupuesto->detalles->count() > 0)
                    <table class="doc-table" style="margin-top: 15px;">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="text-align: left; width: 30%;">Descripción / Rubro</th>
                                <th style="width: 10%;">Cant.</th>
                                <th style="width: 10%;">Unidad</th>
                                <th style="width: 15%; text-align: right;">P. Unitario</th>
                                <th style="width: 15%; text-align: center;">Aporte / Cooperante</th>
                                <th style="width: 15%; text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($presupuesto->detalles as $idx => $detalle)
                                <tr>
                                    <td class="text-center">{{ $idx + 1 }}</td>
                                    <td>{{ $detalle->nombre ?? '-' }}</td>
                                    <td class="text-center">{{ $detalle->cantidad ?? '-' }}</td>
                                    <td class="text-center">{{ $detalle->unidad_medida ?? '-' }}</td>
                                    <td class="text-right">{{ $detalle->precio_unitario ? 'L. ' . number_format($detalle->precio_unitario, 2) : '-' }}</td>
                                    <td class="text-center text-sm" style="color: #555;">
                                        @if($detalle->es_donacion && $detalle->cooperante)
                                            {{ $detalle->cooperante->nombre }}
                                        @else
                                            Comunidad
                                        @endif
                                    </td>
                                    <td class="text-right bold">{{ $detalle->total ? 'L. ' . number_format($detalle->total, 2) : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        @else
            <p style="font-style: italic; color: #666; padding: 20px 0; text-align: center;">
                El proyecto aún no cuenta con un presupuesto detallado.
            </p>
        @endif

    </main>
</body>
</html>