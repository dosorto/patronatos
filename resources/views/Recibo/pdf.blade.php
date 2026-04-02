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
        }

        .page {
            width: 100%;
            height: 100%;
            padding-top: 5mm; /* lo baja un poco */
        }

        .recibo-container {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .recibo {
            width: 180mm;         /* ancho controlado */
            height: 135mm;        /* media página aprox */
            border: 2px dashed #333;
            background: #fff;
            padding: 6mm;
            page-break-inside: avoid;
        }

        .header {
            width: 100%;
            display: table;
            table-layout: fixed;
            margin-bottom: 5mm;
            padding-bottom: 4mm;
            border-bottom: 1.5px solid #d1d5db;
        }

        .header-col {
            display: table-cell;
            vertical-align: top;
        }

        .header-left {
            width: 30%;
        }

        .header-center {
            width: 36%;
            text-align: center;
            vertical-align: middle;
        }

        .header-right {
            width: 34%;
            text-align: right;
        }

        .logo-org {
            width: 100%;
        }

        .logo-wrap {
            display: inline-block;
            vertical-align: top;
            margin-right: 8px;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: #d1d5db;
            border-radius: 50%;
            display: inline-block;
        }

        .logo-text {
            display: inline-block;
            vertical-align: top;
            max-width: 150px;
            margin-top: 2px;
        }

        .org-name {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .titulo-recibo h1 {
            font-size: 20px;
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .titulo-recibo h2 {
            font-size: 16px;
            font-weight: 900;
            text-transform: uppercase;
            color: #dc2626;
            margin-bottom: 3px;
        }

        .titulo-recibo p {
            font-size: 9px;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
        }

        .field {
            margin-bottom: 6px;
        }

        .field-label {
            font-size: 8px;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .field-value {
            width: 100%;
            border-bottom: 1.5px solid #111827;
            padding: 3px 4px;
            font-size: 11px;
            font-weight: bold;
            text-align: right;
        }

        .content {
            margin-bottom: 5mm;
            padding-bottom: 4mm;
            border-bottom: 1.5px solid #d1d5db;
        }

        .row-two {
            width: 100%;
            display: table;
            table-layout: fixed;
            margin-bottom: 10px;
        }

        .col-half {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }

        .col-half.left {
            padding-right: 10px;
        }

        .col-half.right {
            padding-left: 10px;
        }

        .section-label {
            font-size: 8px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .persona-box {
            border-bottom: 1px solid #111827;
            padding-bottom: 6px;
            min-height: 34px;
        }

        .persona-nombre {
            font-size: 11px;
            font-weight: bold;
            color: #111827;
            line-height: 1.2;
        }

        .persona-dni {
            font-size: 9px;
            color: #4b5563;
            margin-top: 2px;
        }

        .monto-box {
            border: 1.5px solid #111827;
            padding: 8px;
            text-align: center;
        }

        .monto-valor {
            font-size: 20px;
            font-weight: 900;
            color: #111827;
        }

        .conceptos {
            margin-top: 5px;
        }

        .concepto-item {
            width: 100%;
            display: table;
            table-layout: fixed;
            border-bottom: 1px solid #d1d5db;
            padding: 3px 0;
            font-size: 9px;
        }

        .concepto-texto,
        .concepto-monto {
            display: table-cell;
            vertical-align: top;
        }

        .concepto-texto {
            width: 75%;
            color: #111827;
            font-weight: 500;
            padding-right: 8px;
        }

        .concepto-monto {
            width: 25%;
            text-align: right;
            color: #111827;
            font-weight: bold;
            white-space: nowrap;
        }

        .firmas {
            width: 100%;
            display: table;
            table-layout: fixed;
            margin-top: 2mm;
        }

        .firma {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .firma-space {
            height: 30px;
            margin-bottom: 5px;
        }

        .firma-line {
            border-top: 1.5px solid #111827;
            padding-top: 3px;
            margin: 0 25px;
        }

        .firma-label {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #111827;
        }

        .footer {
            margin-top: 4mm;
            padding-top: 3mm;
            border-top: 1px solid #d1d5db;
            text-align: center;
            font-size: 7px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="recibo-container">
            <div class="recibo">
                <div class="header">
                    <div class="header-col header-left">
                        <div class="logo-org">
                            <div class="logo-wrap">
                                <div class="logo"></div>
                            </div>
                            <div class="logo-text">
                                <div class="org-name">{{ $organization?->name ?? 'ORGANIZACIÓN' }}</div>
                            </div>
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
                        <div class="field">
                            <div class="field-label">Fecha</div>
                            <div class="field-value">{{ $recibo->fecha_emision->format('d/m/Y') }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Número</div>
                            <div class="field-value">ACP-{{ $recibo->anio }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Importe</div>
                            <div class="field-value">L. {{ number_format($recibo->monto, 2) }}</div>
                        </div>
                    </div>
                </div>

                <div class="content">
                    <div class="row-two">
                        <div class="col-half left">
                            <div class="section-label">Recibí de:</div>
                            <div class="persona-box">
                                <div class="persona-nombre">
                                    {{ $recibo->cobro->miembro->persona->nombre }}
                                    {{ $recibo->cobro->miembro->persona->apellido }}
                                </div>
                                <div class="persona-dni">
                                    DNI: {{ $recibo->cobro->miembro->persona->dni }}
                                </div>
                            </div>
                        </div>

                        <div class="col-half right">
                            <div class="section-label">La suma de:</div>
                            <div class="monto-box">
                                <div class="monto-valor">L. {{ number_format($recibo->monto, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="section-label">Por Concepto:</div>
                        <div class="conceptos">
                            @foreach($recibo->cobro->detallesCobros as $detalle)
                                <div class="concepto-item">
                                    <div class="concepto-texto">{{ $detalle->concepto }}</div>
                                    <div class="concepto-monto">L. {{ number_format($detalle->monto, 2) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="firmas">
                    <div class="firma">
                        <div class="firma-space"></div>
                        <div class="firma-line">
                            <div class="firma-label">Recibí Conforme</div>
                        </div>
                    </div>
                    <div class="firma">
                        <div class="firma-space"></div>
                        <div class="firma-line">
                            <div class="firma-label">Entregué Conforme</div>
                        </div>
                    </div>
                </div>

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