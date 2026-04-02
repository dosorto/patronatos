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

        /* 🔥 CENTRADO CORRECTO PARA DOMPDF */
        .recibo-container {
            width: 100%;
            text-align: center;
        }

        .recibo {
            width: 180mm; /* ideal para portrait */
            min-height: 120mm; /* evita segunda página */
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

        .header-left { width: 30%; }
        .header-center { width: 35%; text-align: center; }
        .header-right { width: 35%; text-align: right; }

        .logo {
            width: 44px;
            height: 44px;
            background: #d1d5db;
            border-radius: 50%;
            display: inline-block;
        }

        .org-name {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            color: #111827;
        }

        .titulo-recibo h1 {
            font-size: 24px;
            font-weight: 900;
            text-transform: uppercase;
            color: #111827;
        }

        .titulo-recibo h2 {
            font-size: 19px;
            font-weight: 900;
            color: #dc2626;
        }

        .titulo-recibo p {
            font-size: 10px;
            color: #4b5563;
            font-weight: bold;
        }

        .field-label {
            font-size: 9px;
            color: #4b5563;
            font-weight: bold;
        }

        .field-value {
            border-bottom: 1.5px solid #111827;
            font-size: 14px;
            font-weight: 800;
            text-align: right;
        }

        .field-value.destacado {
            font-size: 15px;
            font-weight: 900;
        }

        .content {
            margin-bottom: 6mm;
            padding-bottom: 5mm;
            border-bottom: 1.5px solid #d1d5db;
        }

        .row-two {
            display: table;
            width: 100%;
        }

        .col-half {
            display: table-cell;
            width: 50%;
        }

        .section-label {
            font-size: 11px;
            font-weight: 900;
            color: #111827;
        }

        .persona-nombre {
            font-size: 18px;
            font-weight: 900;
        }

        .persona-dni {
            font-size: 12px;
            color: #4b5563;
        }

        .monto-box {
            border: 1.5px solid #111827;
            padding: 10px;
            text-align: center;
        }

        .monto-valor {
            font-size: 26px;
            font-weight: 900;
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
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .concepto-monto {
            display: table-cell;
            width: 25%;
            text-align: right;
            font-size: 13px;
            font-weight: bold;
        }

        .firmas {
            display: table;
            width: 100%;
            margin-top: 3mm;
        }

        .firma {
            display: table-cell;
            width: 50%;
            text-align: center;
        }

        .firma-line {
            border-top: 1.5px solid #111827;
            margin: 0 25px;
        }

        .footer {
            margin-top: 5mm;
            border-top: 1px solid #d1d5db;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
        }

    </style>
</head>

<body>
<div class="page">
    <div class="recibo-container">
        <div class="recibo">

            {{-- HEADER --}}
            <div class="header">
                <div class="header-col header-left">
                    <div class="logo"></div>
                    <div class="org-name">
                        {{ $organization?->name ?? 'ORGANIZACIÓN' }}
                    </div>
                </div>

                <div class="header-col header-center">
                    <div class="titulo-recibo">
                        <h1>RECIBO</h1>
                        <h2>Nº {{ str_pad($recibo->correlativo, 6, '0', STR_PAD_LEFT) }}</h2>
                        <p>INGRESO</p>
                    </div>
                </div>

                <div class="header-col header-right">
                    <div>
                        <div class="field-label">Fecha</div>
                        <div class="field-value destacado">
                            {{ $recibo->fecha_emision->format('d/m/Y') }}
                        </div>
                    </div>

                    <div>
                        <div class="field-label">Número</div>
                        <div class="field-value destacado">
                            ACP-{{ $recibo->anio }}
                        </div>
                    </div>

                    <div>
                        <div class="field-label">Importe</div>
                        <div class="field-value destacado">
                            L. {{ number_format($recibo->monto, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONTENT --}}
            <div class="content">
                <div class="row-two">
                    <div class="col-half">
                        <div class="section-label">Recibí de:</div>
                        <div class="persona-nombre">
                            {{ $recibo->cobro->miembro->persona->nombre }}
                            {{ $recibo->cobro->miembro->persona->apellido }}
                        </div>
                        <div class="persona-dni">
                            DNI: {{ $recibo->cobro->miembro->persona->dni }}
                        </div>
                    </div>

                    <div class="col-half">
                        <div class="section-label">La suma de:</div>
                        <div class="monto-box">
                            <div class="monto-valor">
                                L. {{ number_format($recibo->monto, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top:10px;">
                    <div class="section-label">Por Concepto:</div>
                    @foreach($recibo->cobro->detallesCobros as $detalle)
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
                    <div class="firma-line">Recibí Conforme</div>
                </div>
                <div class="firma">
                    <div class="firma-line">Entregué Conforme</div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="footer">
                Generado: {{ now()->format('d/m/Y H:i') }} |
                Usuario: {{ $recibo->user->name ?? auth()->user()->name }}
            </div>

        </div>
    </div>
</div>
</body>
</html>