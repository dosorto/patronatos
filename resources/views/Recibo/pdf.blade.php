<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo - {{ $recibo->nombre ?? 'Recibo' }}</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #fff;
            color: #111827;
            font-size: 13px;
        }

        .page {
            width: 100%;
        }

        .recibo-container {
            width: 100%;
            text-align: center;
        }

        .recibo {
            width: 180mm;
            min-height: 120mm;
            margin: 0 auto;
            border: 2px dashed #333;
            background: #fff;
            padding: 8mm;
            page-break-inside: avoid;
        }

        .header {
            width: 100%;
            display: table;
            table-layout: fixed;
            margin-bottom: 6mm;
            padding-bottom: 5mm;
            border-bottom: 1.5px solid #d1d5db;
        }

        .header-col {
            display: table-cell;
            vertical-align: top;
        }

        .header-left   { width: 30%; text-align: left; }
        .header-center { width: 35%; text-align: center; }
        .header-right  { width: 35%; text-align: right; }

        .logo-wrap {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            overflow: hidden;
            display: inline-block;
            vertical-align: middle;
            background: #d1d5db;
        }

        .logo-wrap img {
            width: 44px;
            height: 44px;
            object-fit: cover;
            display: block;
        }

        .logo-placeholder {
            width: 44px;
            height: 44px;
            background: #d1d5db;
            border-radius: 50%;
            display: inline-block;
            vertical-align: middle;
        }

        .org-name {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            color: #111827;
            display: block;
            margin-top: 5px;
        }

        .titulo-recibo-h1 {
            font-size: 22px;
            font-weight: 900;
            text-transform: uppercase;
            color: #111827;
        }

        .titulo-recibo-h2 {
            font-size: 19px;
            font-weight: 900;
            color: #dc2626;
        }

        .titulo-recibo-h2.donacion {
            color: #d97706;
        }

        .titulo-recibo-h2.pago {
            color: #2563eb;
        }

        .titulo-recibo-p {
            font-size: 10px;
            color: #4b5563;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .field-label {
            font-size: 9px;
            color: #4b5563;
            font-weight: bold;
            text-transform: uppercase;
        }

        .field-value {
            border-bottom: 1.5px solid #111827;
            font-size: 15px;
            font-weight: 900;
            text-align: right;
            margin-bottom: 4px;
        }

        .content {
            margin-bottom: 6mm;
            padding-bottom: 5mm;
            border-bottom: 1.5px solid #d1d5db;
        }

        .row-two {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .col-half {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 6px;
        }

        .col-half:last-child {
            padding-right: 0;
            padding-left: 6px;
        }

        .section-label {
            font-size: 11px;
            font-weight: 900;
            color: #111827;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .recibido-de-box {
            border-bottom: 1px solid #111827;
            padding-bottom: 6px;
        }

        .persona-nombre {
            font-size: 20px;
            font-weight: 900;
            color: #111827;
            line-height: 1.2;
        }

        .persona-sub {
            font-size: 12px;
            color: #374151;
            font-weight: 600;
            margin-top: 2px;
        }

        .monto-box {
            border: 2px solid #111827;
            padding: 10px;
            text-align: center;
        }

        .monto-valor {
            font-size: 28px;
            font-weight: 900;
            color: #111827;
        }

        .concepto-item {
            display: table;
            width: 100%;
            border-bottom: 1px solid #d1d5db;
            padding: 5px 0;
        }

        .concepto-texto {
            display: table-cell;
            width: 75%;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            color: #111827;
        }

        .concepto-monto {
            display: table-cell;
            width: 25%;
            text-align: right;
            font-size: 13px;
            font-weight: 900;
            color: #111827;
        }

        .firmas {
            display: table;
            width: 100%;
            margin-top: 8mm;
        }

        .firma {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }

        .firma-espacio {
            height: 14mm;
        }

        .firma-line {
            border-top: 1.5px solid #111827;
            padding-top: 3px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            color: #111827;
            letter-spacing: 1px;
        }

        .footer {
            margin-top: 5mm;
            border-top: 1px solid #d1d5db;
            padding-top: 3px;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
        }

        .badge-donacion {
            display: inline-block;
            background: #fef3c7;
            border: 1px solid #d97706;
            color: #92400e;
            font-size: 9px;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 20px;
            text-transform: uppercase;
            margin-top: 3px;
        }

        .badge-pago {
            display: inline-block;
            background: #dbeafe;
            border: 1px solid #2563eb;
            color: #1d4ed8;
            font-size: 9px;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 20px;
            text-transform: uppercase;
            margin-top: 3px;
        }
    </style>
</head>

@php
    $nombreOrg = $organization?->name ?? '';
    $omitidas  = ['DE', 'LA', 'EL', 'DEL', 'LOS', 'LAS', 'Y'];
    $siglas    = '';

    foreach (explode(' ', $nombreOrg) as $p) {
        $p = trim($p);
        if ($p !== '' && !in_array(strtoupper($p), $omitidas)) {
            $siglas .= strtoupper(substr($p, 0, 1));
        }
    }

    if ($siglas === '') {
        $siglas = 'ORG';
    }

    $logoB64 = null;
    if ($organization && $organization->logo) {
        $logoValue = ltrim($organization->logo, '/');
        if (!str_starts_with($logoValue, 'logos/')) {
            $logoValue = 'logos/' . $logoValue;
        }

        $fullPath = storage_path('app/public/' . $logoValue);
        if (file_exists($fullPath)) {
            $mime    = mime_content_type($fullPath);
            $logoB64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath));
        }
    }

    $esPago = !is_null($recibo->pago_id);
    $esCobro = !is_null($recibo->cobro_id);
    $esDonacion = false;

    $cooperanteDonacion = null;
    $nombreRecibidoDe = '';
    $subtituloRecibidoDe = '';
    $conceptos = collect();
    $tituloRecibo = 'RECIBO';
    $tipoTexto = 'INGRESO';
    $claseTitulo = '';
    $esPagoRecibo = false;

    if ($esCobro && $recibo->cobro) {
        $esDonacion = is_null($recibo->cobro->miembro_id) || $recibo->cobro->tipo_cobro === 'donacion';

        if ($esDonacion) {
            $primerDetalle = $recibo->cobro->detallesCobros->first();
            $cooperanteDonacion = $primerDetalle?->cooperante;

            $nombreRecibidoDe = $cooperanteDonacion?->nombre ?? 'Cooperante';
            $subtituloRecibidoDe = $cooperanteDonacion?->tipo_cooperante ?? 'Donación registrada';
            $tituloRecibo = 'RECIBO DE DONACIÓN';
            $tipoTexto = 'DONACIÓN';
            $claseTitulo = 'donacion';
        } else {
            $nombreRecibidoDe = trim(($recibo->cobro->miembro->persona->nombre ?? '') . ' ' . ($recibo->cobro->miembro->persona->apellido ?? ''));
            $subtituloRecibidoDe = 'DNI: ' . ($recibo->cobro->miembro->persona->dni ?? '');
        }

        $conceptos = $recibo->cobro->detallesCobros ?? collect();
    }

    if ($esPago && $recibo->pago) {
        $esPagoRecibo = true;
        $tituloRecibo = 'RECIBO DE PAGO';
        $tipoTexto = 'EGRESO';
        $claseTitulo = 'pago';

        $nombreRecibidoDe = $recibo->pago->nombre_persona ?: 'Pago registrado';
        $subtituloRecibidoDe = $recibo->pago->descripcion ?: 'Egreso generado en sistema';

        $conceptos = $recibo->pago->detalles ?? collect();
    }
@endphp

<body>
<div class="page">
    <div class="recibo-container">
        <div class="recibo">

            {{-- HEADER --}}
            <div class="header">

                {{-- Izquierda --}}
                <div class="header-col header-left">
                    @if($logoB64)
                        <div class="logo-wrap">
                            <img src="{{ $logoB64 }}" alt="Logo">
                        </div>
                    @else
                        <div class="logo-placeholder"></div>
                    @endif
                    <span class="org-name">{{ $organization?->name ?? 'ORGANIZACIÓN' }}</span>
                </div>

                {{-- Centro --}}
                <div class="header-col header-center">
                    <div class="titulo-recibo-h1">
                        {{ $tituloRecibo }}
                    </div>
                    <div class="titulo-recibo-h2 {{ $claseTitulo }}">
                        Nº {{ str_pad($recibo->correlativo, 6, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="titulo-recibo-p">
                        {{ $tipoTexto }}
                    </div>
                </div>

                {{-- Derecha --}}
                <div class="header-col header-right">
                    <div class="field-label">Fecha</div>
                    <div class="field-value">{{ $recibo->fecha_emision->format('d/m/Y') }}</div>

                    <div class="field-label">Número</div>
                    <div class="field-value">{{ $siglas }}-{{ $recibo->anio }}</div>

                    <div class="field-label">Importe</div>
                    <div class="field-value">L. {{ number_format($recibo->monto, 2) }}</div>
                </div>
            </div>

            {{-- CONTENT --}}
            <div class="content">

                <div class="row-two">

                    {{-- Recibí de / Pagado a --}}
                    <div class="col-half">
                        <div class="section-label">
                            @if($esPagoRecibo)
                                Pagado a:
                            @else
                                Recibí de:
                            @endif
                        </div>

                        <div class="recibido-de-box">
                            <div class="persona-nombre">
                                {{ $nombreRecibidoDe }}
                            </div>

                            @if($subtituloRecibidoDe)
                                <div class="persona-sub">{{ $subtituloRecibidoDe }}</div>
                            @endif

                            @if($esDonacion)
                                <div><span class="badge-donacion">Donación</span></div>
                            @endif

                            @if($esPagoRecibo)
                                <div><span class="badge-pago">Pago</span></div>
                            @endif
                        </div>
                    </div>

                    {{-- La suma de --}}
                    <div class="col-half">
                        <div class="section-label">La suma de:</div>
                        <div class="monto-box">
                            <div class="monto-valor">L. {{ number_format($recibo->monto, 2) }}</div>
                        </div>
                    </div>
                </div>

                {{-- Conceptos --}}
                <div style="margin-top:8px;">
                    <div class="section-label">Por Concepto:</div>
                    @foreach($conceptos as $detalle)
                        <div class="concepto-item">
                            <div class="concepto-texto">{{ $detalle->concepto }}</div>
                            <div class="concepto-monto">L. {{ number_format($detalle->monto, 2) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- FIRMAS --}}
            <div class="firmas">
                <div class="firma">
                    <div class="firma-espacio"></div>
                    <div class="firma-line">Recibí Conforme</div>
                </div>
                <div class="firma">
                    <div class="firma-espacio"></div>
                    <div class="firma-line">Entregué Conforme</div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="footer">
                Generado: {{ now()->format('d/m/Y H:i') }} |
                Usuario: {{ $recibo->user->name ?? auth()->user()->name }} |
                Correlativo: {{ $recibo->anio }}-{{ str_pad($recibo->correlativo, 6, '0', STR_PAD_LEFT) }}
            </div>

        </div>
    </div>
</div>
</body>
</html>