<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración Inicial</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@700;900&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            background: #EEF4FF;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-x: hidden;
            padding-bottom: 60px;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 50% at 20% -10%, rgba(99,102,241,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 80% 110%, rgba(59,130,246,0.1) 0%, transparent 60%),
                radial-gradient(ellipse 40% 30% at 60% 50%, rgba(139,92,246,0.06) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(rgba(99,102,241,0.08) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
            z-index: 0;
        }

        .wi-header {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 48px 24px 24px;
            animation: fadeUp .5s ease both;
        }
        .wi-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(99,102,241,0.1);
            border: 1px solid rgba(99,102,241,0.2);
            color: #4F46E5;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 99px;
            margin-bottom: 16px;
        }
        .wi-title {
            font-family: 'Fraunces', serif;
            font-size: 2.2rem;
            font-weight: 900;
            color: #1E1B4B;
            line-height: 1.15;
            margin-bottom: 10px;
        }
        .wi-title span {
            background: linear-gradient(90deg, #4F46E5, #3B82F6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .wi-subtitle { font-size: .9rem; color: #6B7280; }

        .stepper-wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 680px;
            padding: 0 24px 28px;
            animation: fadeUp .5s ease .1s both;
        }
        .stepper {
            display: flex;
            background: white;
            border: 1px solid #E0E7FF;
            border-radius: 18px;
            padding: 16px 20px;
            box-shadow: 0 2px 12px rgba(99,102,241,0.08);
        }
        .step-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 7px;
            position: relative;
            cursor: pointer;
            min-width: 60px;
        }
        .step-node {
            width: 36px; height: 36px;
            border-radius: 50%;
            border: 2px solid #E0E7FF;
            background: #F5F7FF;
            color: #9CA3AF;
            display: flex; align-items: center; justify-content: center;
            font-size: .82rem; font-weight: 700;
            transition: all .3s;
        }
        .step-item.active .step-node {
            border-color: #4F46E5;
            background: linear-gradient(135deg, #4F46E5, #6366F1);
            color: white;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.15), 0 4px 12px rgba(99,102,241,0.3);
        }
        .step-item.done .step-node {
            border-color: #10B981;
            background: linear-gradient(135deg, #10B981, #34D399);
            color: white;
            box-shadow: 0 4px 10px rgba(16,185,129,0.25);
        }
        .step-label { font-size: .68rem; font-weight: 600; color: #9CA3AF; text-align: center; white-space: nowrap; }
        .step-item.active .step-label { color: #4F46E5; }
        .step-item.done .step-label { color: #10B981; }
        .step-connector {
            position: absolute;
            top: 18px; left: 56%;
            width: 88%; height: 2px;
            background: #E0E7FF;
            border-radius: 99px;
            z-index: -1;
            transition: background .3s;
        }
        .step-connector.done { background: #34D399; }
        .step-item:last-child .step-connector { display: none; }

        .wi-card {
            position: relative;
            z-index: 1;
            background: #79b9b9;
            border: 1px solid #050505;
            border-radius: 24px;
            padding: 36px;
            width: calc(100% - 48px);
            max-width: 1600px;
            margin: 0 auto;
            box-shadow: 0 4px 24px rgba(99,102,241,0.08), 0 1px 3px rgba(0,0,0,0.04);
        }

        .wi-progress-bar {
            height: 6px;
            background: #EEF2FF;
            border-radius: 99px;
            overflow: hidden;
            margin-bottom: 6px;
        }
        .wi-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4F46E5, #3B82F6);
            border-radius: 99px;
            transition: width .5s cubic-bezier(.4,0,.2,1);
        }
        .wi-progress-txt { font-size: .85rem; font-weight: 800; color: #1E1B4B; text-align: right; margin-bottom: 28px; }

        .panel-head { display: flex; align-items: flex-start; gap: 16px; margin-bottom: 28px; }
        .panel-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            font-size: 1.5rem;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .panel-title { font-family: 'Fraunces', serif; font-size: 1.3rem; font-weight: 700; color: #1E1B4B; margin-bottom: 4px; }
        .panel-desc { font-size: .875rem; color: #6B7280; line-height: 1.6; }

        .upload-zone {
            border: 2px dashed #C7D2FE;
            border-radius: 16px;
            padding: 44px 28px;
            text-align: center;
            background: #F5F7FF;
            margin-bottom: 24px;
            transition: all .2s;
            cursor: pointer;
        }
        .upload-zone:hover, .upload-zone.drag { border-color: #4F46E5; background: #EEF2FF; }
        .upload-zone.has-file { border-color: #10B981; background: #F0FDF4; border-style: solid; }
        .upload-icon-wrap {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: #EEF2FF; color: #4F46E5;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
        }
        .upload-msg { font-size: .9rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .upload-or { font-size: .78rem; color: #9CA3AF; margin-bottom: 12px; }
        .upload-hint { font-size: .72rem; color: #9CA3AF; margin-top: 10px; }
        .logo-preview-wrap { display: flex; flex-direction: column; align-items: center; gap: 12px; }
        .logo-preview-img {
            max-width: 160px; max-height: 100px;
            border-radius: 10px; object-fit: contain;
            border: 1px solid #E0E7FF; background: white; padding: 10px;
        }
        .btn-remove {
            background: #FEF2F2; color: #EF4444;
            border: 1px solid #FECACA;
            border-radius: 8px; padding: 5px 14px;
            font-size: .78rem; font-weight: 600; cursor: pointer;
        }

        .frame-wrap {
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #E0E7FF;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(99,102,241,0.06);
        }
        .frame-bar {
            background: #F5F7FF;
            border-bottom: 1px solid #E0E7FF;
            padding: 9px 14px;
            display: flex; align-items: center; gap: 12px;
        }
        .frame-dots { display: flex; gap: 5px; }
        .frame-dots span { width: 10px; height: 10px; border-radius: 50%; }
        .frame-dots span:nth-child(1) { background: #FCA5A5; }
        .frame-dots span:nth-child(2) { background: #FCD34D; }
        .frame-dots span:nth-child(3) { background: #6EE7B7; }
        .frame-url { flex: 1; font-size: .73rem; color: #9CA3AF; }
        .frame-ext { display: flex; align-items: center; gap: 5px; font-size: .73rem; color: #4F46E5; text-decoration: none; font-weight: 600; }
        .frame-ext:hover { text-decoration: underline; }
        .frame-iframe { width: 100%; height: 500px; border: none; display: block; }

        .wi-actions {
            display: flex; justify-content: flex-end; gap: 10px;
            padding-top: 20px;
            border-top: 1px solid #EEF2FF;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 11px 22px; border-radius: 11px;
            font-family: 'DM Sans', sans-serif; font-size: .875rem; font-weight: 700;
            border: none; cursor: pointer; text-decoration: none;
            transition: all .18s;
        }
        .btn:disabled { opacity: .4; cursor: not-allowed; }
        .btn-ghost { background: transparent; color: #6B7280; border: 1.5px solid #E0E7FF; }
        .btn-ghost:hover { background: #F5F7FF; color: #374151; border-color: #C7D2FE; }
        .btn-primary { background: linear-gradient(135deg, #4F46E5, #6366F1); color: white; box-shadow: 0 4px 14px rgba(79,70,229,0.25); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(79,70,229,0.35); }
        .btn-green  { background: linear-gradient(135deg, #10B981, #34D399); color: white; box-shadow: 0 4px 14px rgba(16,185,129,0.25); }
        .btn-green:hover  { transform: translateY(-1px); }
        .btn-orange { background: linear-gradient(135deg, #F59E0B, #FBBF24); color: white; box-shadow: 0 4px 14px rgba(245,158,11,0.25); }
        .btn-orange:hover { transform: translateY(-1px); }
        .btn-purple { background: linear-gradient(135deg, #9333EA, #A855F7); color: white; box-shadow: 0 4px 14px rgba(147,51,234,0.25); }
        .btn-purple:hover { transform: translateY(-1px); }
        .btn-cyan   { background: linear-gradient(135deg, #0891B2, #06B6D4); color: white; box-shadow: 0 4px 14px rgba(6,182,212,0.25); }
        .btn-cyan:hover   { transform: translateY(-1px); }
        .btn-finish {
            background: linear-gradient(135deg, #4F46E5, #3B82F6);
            color: white; box-shadow: 0 4px 18px rgba(79,70,229,0.3);
            padding: 13px 28px; font-size: .925rem;
        }
        .btn-finish:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(79,70,229,0.4); }

        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(30,27,75,0.35);
            backdrop-filter: blur(8px);
            align-items: center; justify-content: center;
            z-index: 999; padding: 24px;
        }
        .modal-overlay.show { display: flex; }
        .modal-box {
            background: white;
            border: 1px solid #E0E7FF;
            border-radius: 24px;
            padding: 48px 40px;
            max-width: 420px; width: 100%;
            text-align: center;
            box-shadow: 0 32px 80px rgba(79,70,229,0.15);
            animation: modalPop .35s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes modalPop { from{opacity:0;transform:scale(.85)} to{opacity:1;transform:scale(1)} }
        .modal-emoji { font-size: 3.5rem; margin-bottom: 16px; display: block; animation: bounce 1.2s ease infinite; }
        @keyframes bounce { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        .modal-title { font-family: 'Fraunces', serif; font-size: 1.5rem; font-weight: 900; color: #1E1B4B; margin-bottom: 10px; }
        .modal-desc { font-size: .875rem; color: #6B7280; line-height: 1.65; margin-bottom: 28px; }
        .modal-actions { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }

        @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
        .sr-only { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0); }

        @media(max-width:800px){
            .wi-card { padding: 22px; width: calc(100% - 24px); }
            .wi-title { font-size: 1.7rem; }
            .step-label { display: none; }
            .frame-iframe { height: 370px; }
            .wi-actions { justify-content: stretch; }
            .btn { flex: 1; justify-content: center; }
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="wi-header">
    <div class="wi-badge">
        <svg width="8" height="8" viewBox="0 0 8 8" fill="currentColor"><circle cx="4" cy="4" r="4"/></svg>
        Configuración inicial
    </div>
    <p class="wi-subtitle">Completa estos 5 pasos para dejar todo listo.</p>
</div>

{{-- STEPPER --}}
<div class="stepper-wrap">
    <div class="stepper">
        <div class="step-item active" data-step="0">
            <div class="step-node">1</div>
            <span class="step-label">Logo</span>
            <div class="step-connector"></div>
        </div>
        <div class="step-item" data-step="1">
            <div class="step-node">2</div>
            <span class="step-label">Servicios</span>
            <div class="step-connector"></div>
        </div>
        <div class="step-item" data-step="2">
            <div class="step-node">3</div>
            <span class="step-label">Miembro</span>
            <div class="step-connector"></div>
        </div>
        <div class="step-item" data-step="3">
            <div class="step-node">4</div>
            <span class="step-label">Directiva</span>
            <div class="step-connector"></div>
        </div>
        <div class="step-item" data-step="4">
            <div class="step-node">5</div>
            <span class="step-label">Activos</span>
        </div>
    </div>
</div>

{{-- CARD --}}
<div class="wi-card">

    <div class="wi-progress-bar">
        <div class="wi-progress-fill" id="progressFill" style="width:0%"></div>
    </div>
    <p class="wi-progress-txt" id="progressTxt">0 de 5 pasos completados</p>

    {{-- PASO 0: LOGO --}}
    <div id="panel-0">
        <div class="panel-head">
            <div class="panel-icon" style="background:#EEF2FF;">🖼️</div>
            <div>
                <p class="panel-title">Logo de la Organización</p>
                <p class="panel-desc">Sube el logo oficial. Si no tienes uno aún puedes omitir este paso.</p>
            </div>
        </div>
        <div class="upload-zone" id="uploadZone"
             ondragover="event.preventDefault(); this.classList.add('drag')"
             ondragleave="this.classList.remove('drag')"
             ondrop="handleDrop(event)">
            <div id="uploadEmpty">
                <div class="upload-icon-wrap">
                    <svg width="30" height="30" viewBox="0 0 30 30" fill="none">
                        <path d="M15 22V8M15 8L9 14M15 8L21 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 24H25" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <p class="upload-msg">Arrastra tu imagen aquí</p>
                <p class="upload-or">— o —</p>
                <label class="btn btn-primary" style="cursor:pointer">
                    Seleccionar archivo
                    <input type="file" class="sr-only" accept="image/*" onchange="handleFile(event)">
                </label>
                <p class="upload-hint">PNG, JPG, SVG · máx. 2 MB</p>
            </div>
            <div id="uploadPreview" style="display:none" class="logo-preview-wrap">
                <img id="logoImg" src="" alt="Logo" class="logo-preview-img">
                <p id="logoName" style="font-size:.78rem; color:#9CA3AF"></p>
                <button class="btn-remove" onclick="removeLogo()">✕ Quitar</button>
            </div>
        </div>
        <div class="wi-actions">
            <button class="btn btn-ghost" onclick="skipStep()">Omitir</button>
            <button class="btn btn-primary" id="btnUpload" disabled onclick="completeStep()">
                Guardar y continuar
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>

    {{-- PASO 1: SERVICIOS --}}
    <div id="panel-1" style="display:none">
        <div class="panel-head">
            <div class="panel-icon" style="background:#F0F9FF;">🛠️</div>
            <div>
                <p class="panel-title">Configurar Servicios</p>
                <p class="panel-desc">Registra los servicios que ofrece la organización y sus tarifas.</p>
            </div>
        </div>
        <div class="frame-wrap">
            <div class="frame-bar">
                <div class="frame-dots"><span></span><span></span><span></span></div>
                <span class="frame-url">servicio / index</span>
                <a href="{{ route('servicios.index') }}?wizard=1" target="_blank" class="frame-ext">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M1 11L11 1M11 1H5M11 1V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Pantalla completa
                </a>
            </div>
            <iframe src="{{ route('servicios.index') }}?wizard=1" class="frame-iframe"></iframe>
        </div>
        <div class="wi-actions">
            <button class="btn btn-ghost" onclick="prevStep()">← Atrás</button>
            <button class="btn btn-ghost" onclick="skipStep()">Omitir</button>
            <button class="btn btn-green" onclick="completeStep()">
                Listo, continuar
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>

    {{-- PASO 2: MIEMBRO --}}
    <div id="panel-2" style="display:none">
        <div class="panel-head">
            <div class="panel-icon" style="background:#F0FDF4;">👤</div>
            <div>
                <p class="panel-title">Agregar Miembro</p>
                <p class="panel-desc">Registra los primeros miembros de la organización y suscríbelos a servicios.</p>
            </div>
        </div>
        <div class="frame-wrap">
            <div class="frame-bar">
                <div class="frame-dots"><span></span><span></span><span></span></div>
                <span class="frame-url">miembro / index</span>
                <a href="{{ route('miembro.index') }}?wizard=1" target="_blank" class="frame-ext">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M1 11L11 1M11 1H5M11 1V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Pantalla completa
                </a>
            </div>
            <iframe src="{{ route('miembro.index') }}?wizard=1" class="frame-iframe"></iframe>
        </div>
        <div class="wi-actions">
            <button class="btn btn-ghost" onclick="prevStep()">← Atrás</button>
            <button class="btn btn-ghost" onclick="skipStep()">Omitir</button>
            <button class="btn btn-orange" onclick="completeStep()">
                Listo, continuar
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>

    {{-- PASO 3: DIRECTIVA --}}
    <div id="panel-3" style="display:none">
        <div class="panel-head">
            <div class="panel-icon" style="background:#FFFBEB;">🏛️</div>
            <div>
                <p class="panel-title">Configurar Directiva</p>
                <p class="panel-desc">Establece los cargos directivos y sus responsables.</p>
            </div>
        </div>
        <div class="frame-wrap">
            <div class="frame-bar">
                <div class="frame-dots"><span></span><span></span><span></span></div>
                <span class="frame-url">directiva / index</span>
                <a href="{{ route('directiva.index') }}?wizard=1" target="_blank" class="frame-ext">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M1 11L11 1M11 1H5M11 1V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Pantalla completa
                </a>
            </div>
            <iframe src="{{ route('directiva.index') }}?wizard=1" class="frame-iframe"></iframe>
        </div>
        <div class="wi-actions">
            <button class="btn btn-ghost" onclick="prevStep()">← Atrás</button>
            <button class="btn btn-ghost" onclick="skipStep()">Omitir</button>
            <button class="btn btn-purple" onclick="completeStep()">
                Listo, continuar
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>

    {{-- PASO 4: ACTIVOS --}}
    <div id="panel-4" style="display:none">
        <div class="panel-head">
            <div class="panel-icon" style="background:#FAF5FF;">📦</div>
            <div>
                <p class="panel-title">Agregar Activos</p>
                <p class="panel-desc">Registra los bienes e inventario de la organización.</p>
            </div>
        </div>
        <div class="frame-wrap">
            <div class="frame-bar">
                <div class="frame-dots"><span></span><span></span><span></span></div>
                <span class="frame-url">activo / index</span>
                <a href="{{ route('activo.index') }}?wizard=1" target="_blank" class="frame-ext">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M1 11L11 1M11 1H5M11 1V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Pantalla completa
                </a>
            </div>
            <iframe src="{{ route('activo.index') }}?wizard=1" class="frame-iframe"></iframe>
        </div>
        <div class="wi-actions">
            <button class="btn btn-ghost" onclick="prevStep()">← Atrás</button>
            <button class="btn btn-finish" onclick="document.getElementById('modalFinal').classList.add('show')">
                🎉 Finalizar configuración
            </button>
        </div>
    </div>

</div>

{{-- MODAL FINAL --}}
<div class="modal-overlay" id="modalFinal" onclick="if(event.target===this) this.classList.remove('show')">
    <div class="modal-box">
        <span class="modal-emoji">🎉</span>
        <h3 class="modal-title">¡Todo listo!</h3>
        <p class="modal-desc">Tu organización está configurada y lista para operar. Podrás editar cualquier sección desde el menú principal.</p>
        <div class="modal-actions">
            <button class="btn btn-ghost" onclick="document.getElementById('modalFinal').classList.remove('show')">Revisar</button>
            <a href="{{ route('dashboard') }}" class="btn btn-finish">Ir al Dashboard →</a>
        </div>
    </div>
</div>


<script>
    let currentStep = 0;
    const totalSteps = 5;
    const completedSteps = [];
    const checkIcon = `<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M2.5 8L6.5 12L13.5 4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>`;

    // Mapa: paso → endpoint de conteo (null = sin validación)
    const stepCountUrl = {
        0: null,
        1: '/wizard/count/servicios',
        2: '/wizard/count/miembros',
        3: '/wizard/count/directiva',
        4: '/wizard/count/activos',
    };

    const stepNames = {
        1: 'servicio',
        2: 'miembro',
        3: 'directiva',
        4: 'activo',
    };

    function updateStepper() {
        document.querySelectorAll('.step-item').forEach((el, i) => {
            el.classList.remove('active', 'done');
            const node = el.querySelector('.step-node');
            const conn = el.querySelector('.step-connector');
            if (completedSteps.includes(i)) {
                el.classList.add('done');
                node.innerHTML = checkIcon;
                if (conn) conn.classList.add('done');
            } else {
                node.innerHTML = i + 1;
                if (conn) conn.classList.remove('done');
            }
            if (i === currentStep) el.classList.add('active');
        });
        const pct = (completedSteps.length / totalSteps) * 100;
        document.getElementById('progressFill').style.width = pct + '%';
        document.getElementById('progressTxt').textContent = completedSteps.length + ' de ' + totalSteps + ' pasos completados';
    }

    function showPanel(step) {
        for (let i = 0; i < totalSteps; i++) {
            const p = document.getElementById('panel-' + i);
            if (p) p.style.display = i === step ? 'block' : 'none';
        }
        currentStep = step;
        updateStepper();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showToast(msg) {
        // Eliminar toast anterior si existe
        const old = document.getElementById('wizardToast');
        if (old) old.remove();

        const toast = document.createElement('div');
        toast.id = 'wizardToast';
        toast.style.cssText = `
            position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%);
            background: #1E1B4B; color: white; padding: 14px 24px;
            border-radius: 12px; font-size: .875rem; font-weight: 600;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2); z-index: 9999;
            display: flex; align-items: center; gap: 10px;
            animation: fadeUp .3s ease both;
        `;
        toast.innerHTML = `
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                <circle cx="9" cy="9" r="8" stroke="#F59E0B" stroke-width="1.8"/>
                <path d="M9 5v4M9 12v.5" stroke="#F59E0B" stroke-width="2" stroke-linecap="round"/>
            </svg>
            ${msg}
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3500);
    }

    async function completeStep() {
        const url = stepCountUrl[currentStep];

        // Paso 0 (logo): upload si hay archivo
        if (currentStep === 0 && window._logoFile) {
            const formData = new FormData();
            formData.append('logo', window._logoFile);
            formData.append('_token', '{{ csrf_token() }}');

            const btn = document.getElementById('btnUpload');
            btn.disabled = true;
            btn.textContent = 'Guardando...';

            try {
                const res  = await fetch('{{ route("organization.upload-logo") }}', {
                    method: 'POST', body: formData
                });
                const data = await res.json();

                if (!data.success) {
                    showToast('Error al guardar el logo: ' + (data.message ?? 'intenta de nuevo'));
                    btn.disabled = false;
                    btn.innerHTML = 'Guardar y continuar <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>';
                    return;
                }

                // Éxito: avanzar al siguiente paso
                if (!completedSteps.includes(currentStep)) completedSteps.push(currentStep);
                showPanel(currentStep + 1);

            } catch (err) {
                showToast('Error de red al guardar el logo. Intenta de nuevo.');
                btn.disabled = false;
                btn.innerHTML = 'Guardar y continuar <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>';
            }
            return;
        }

        // Pasos con validación de conteo
        if (url) {
            try {
                const res  = await fetch(url);
                const data = await res.json();

                if (data.count === 0) {
                    showToast(`Debes registrar al menos un ${stepNames[currentStep]} antes de continuar.`);
                    return;
                }
            } catch {
                showToast('No se pudo verificar el registro. Intenta de nuevo.');
                return;
            }
        }

        if (!completedSteps.includes(currentStep)) completedSteps.push(currentStep);
        if (currentStep < totalSteps - 1) showPanel(currentStep + 1);
        else document.getElementById('modalFinal').classList.add('show');
    }

    function skipStep() { if (currentStep < totalSteps - 1) showPanel(currentStep + 1); }
    function prevStep() { if (currentStep > 0) showPanel(currentStep - 1); }

    document.querySelectorAll('.step-item').forEach(el => {
        el.addEventListener('click', () => {
            const i = parseInt(el.dataset.step);
            if (i <= currentStep || completedSteps.includes(i - 1) || i === 0) showPanel(i);
        });
    });

    function handleFile(e) { const f = e.target.files[0]; if(f) previewLogo(f); }
    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('uploadZone').classList.remove('drag');
        const f = e.dataTransfer.files[0];
        if (f && f.type.startsWith('image/')) previewLogo(f);
    }
    function previewLogo(file) {
        const reader = new FileReader();
        reader.onload = ev => {
            document.getElementById('logoImg').src = ev.target.result;
            document.getElementById('logoName').textContent = file.name;
            document.getElementById('uploadEmpty').style.display = 'none';
            document.getElementById('uploadPreview').style.display = 'flex';
            document.getElementById('uploadZone').classList.add('has-file');
            document.getElementById('btnUpload').disabled = false;
        };
        reader.readAsDataURL(file);
        window._logoFile = file;
    }
    function removeLogo() {
        document.getElementById('logoImg').src = '';
        document.getElementById('uploadEmpty').style.display = 'block';
        document.getElementById('uploadPreview').style.display = 'none';
        document.getElementById('uploadZone').classList.remove('has-file');
        document.getElementById('btnUpload').disabled = true;
        window._logoFile = null;
    }
</script>

</body>
</html>