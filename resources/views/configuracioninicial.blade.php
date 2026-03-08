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
            background: #0F172A;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-x: hidden;
            padding-bottom: 60px;
        }

        .bg { position: fixed; inset: 0; z-index: 0; pointer-events: none; }
        .blob { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.2; }
        .blob-1 { width: 600px; height: 600px; background: #6366F1; top: -200px; left: -150px; animation: f1 10s ease-in-out infinite; }
        .blob-2 { width: 500px; height: 500px; background: #EC4899; bottom: -150px; right: -100px; animation: f1 10s ease-in-out 4s infinite; }
        .blob-3 { width: 350px; height: 350px; background: #06B6D4; top: 40%; left: 50%; animation: f3 10s ease-in-out 2s infinite; }
        @keyframes f1 { 0%,100%{transform:translateY(0) scale(1)} 50%{transform:translateY(-28px) scale(1.06)} }
        @keyframes f3 { 0%,100%{transform:translate(-50%,-50%) scale(1)} 50%{transform:translate(-50%,calc(-50% - 28px)) scale(1.06)} }

        .wi-header { position:relative; z-index:1; text-align:center; padding:48px 24px 32px; animation:fadeUp .5s ease both; }
        .wi-header-icon {
            width:72px; height:72px;
            background:linear-gradient(135deg,#6366F1,#EC4899);
            border-radius:22px; display:flex; align-items:center; justify-content:center;
            font-size:2rem; margin:0 auto 20px;
            box-shadow:0 12px 36px rgba(99,102,241,0.4);
            animation:iconPop .5s cubic-bezier(.34,1.56,.64,1) .15s both;
        }
        @keyframes iconPop { from{transform:scale(0) rotate(-15deg)} to{transform:scale(1) rotate(0)} }
        .wi-eyebrow { font-size:.72rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:#818CF8; margin-bottom:8px; }
        .wi-title { font-family:'Fraunces',serif; font-size:2.2rem; font-weight:900; color:white; line-height:1.1; margin-bottom:10px; }
        .wi-title span { background:linear-gradient(90deg,#818CF8,#EC4899); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .wi-subtitle { font-size:.9rem; color:rgba(255,255,255,0.45); }

        .stepper-wrap { position:relative; z-index:1; width:100%; max-width:680px; padding:0 24px 28px; animation:fadeUp .5s ease .1s both; }
        .stepper { display:flex; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08); border-radius:18px; padding:16px 20px; }
        .step-item { flex:1; display:flex; flex-direction:column; align-items:center; gap:7px; position:relative; cursor:pointer; min-width:70px; }
        .step-node {
            width:38px; height:38px; border-radius:50%;
            border:2px solid rgba(255,255,255,0.15);
            background:rgba(255,255,255,0.05);
            color:rgba(255,255,255,0.35);
            display:flex; align-items:center; justify-content:center;
            font-size:.85rem; font-weight:700; transition:all .3s;
        }
        .step-item.active .step-node { border-color:#818CF8; background:linear-gradient(135deg,#6366F1,#818CF8); color:white; box-shadow:0 0 0 4px rgba(99,102,241,0.2),0 4px 14px rgba(99,102,241,0.4); }
        .step-item.done  .step-node  { border-color:#34D399; background:linear-gradient(135deg,#10B981,#34D399); color:white; box-shadow:0 4px 12px rgba(16,185,129,0.3); }
        .step-label { font-size:.7rem; font-weight:600; color:rgba(255,255,255,0.3); text-align:center; white-space:nowrap; }
        .step-item.active .step-label { color:#818CF8; }
        .step-item.done  .step-label  { color:#34D399; }
        .step-connector { position:absolute; top:19px; left:56%; width:88%; height:2px; background:rgba(255,255,255,0.08); border-radius:99px; z-index:-1; transition:background .3s; }
        .step-connector.done { background:#34D399; }
        .step-item:last-child .step-connector { display:none; }

        .wi-card { position:relative; z-index:1; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); backdrop-filter:blur(20px); border-radius:24px; padding:36px; width:100%; max-width:1500px; margin:0 24px; box-shadow:0 24px 60px rgba(0,0,0,0.35); }

        .wi-progress-bar { height: 8px;px; background:rgba(255,255,255,0.08); border-radius:99px; overflow:hidden; margin-bottom:6px; }
        .wi-progress-fill { height:100%; background:linear-gradient(90deg,#6366F1,#EC4899); border-radius:99px; transition:width .5s cubic-bezier(.4,0,.2,1); }
        .wi-progress-txt { font-size:.72rem; color:rgba(255,255,255,0.3); text-align:right; margin-bottom:28px; }

        .panel-head { display:flex; align-items:flex-start; gap:16px; margin-bottom:28px; }
        .panel-icon { width:52px; height:52px; border-radius:14px; font-size:1.5rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .panel-title { font-family:'Fraunces',serif; font-size:1.3rem; font-weight:700; color:white; margin-bottom:4px; }
        .panel-desc { font-size:.875rem; color:rgba(255,255,255,0.45); line-height:1.6; }

        .upload-zone { border:2px dashed rgba(255,255,255,0.12); border-radius:16px; padding:44px 28px; text-align:center; background:rgba(255,255,255,0.03); margin-bottom:24px; transition:all .2s; cursor:pointer; }
        .upload-zone:hover, .upload-zone.drag { border-color:#818CF8; background:rgba(99,102,241,0.08); }
        .upload-zone.has-file { border-color:#34D399; background:rgba(16,185,129,0.06); border-style:solid; }
        .upload-icon-wrap { width:64px; height:64px; border-radius:50%; background:rgba(99,102,241,0.15); color:#818CF8; display:flex; align-items:center; justify-content:center; margin:0 auto 14px; }
        .upload-msg  { font-size:.9rem; font-weight:600; color:rgba(255,255,255,0.7); margin-bottom:6px; }
        .upload-or   { font-size:.78rem; color:rgba(255,255,255,0.25); margin-bottom:12px; }
        .upload-hint { font-size:.72rem; color:rgba(255,255,255,0.2); margin-top:10px; }
        .logo-preview-wrap { display:flex; flex-direction:column; align-items:center; gap:12px; }
        .logo-preview-img { max-width:160px; max-height:100px; border-radius:10px; object-fit:contain; border:1px solid rgba(255,255,255,0.1); background:rgba(255,255,255,0.05); padding:10px; }
        .btn-remove { background:rgba(239,68,68,0.15); color:#FCA5A5; border:1px solid rgba(239,68,68,0.25); border-radius:8px; padding:5px 14px; font-size:.78rem; font-weight:600; cursor:pointer; }

        .frame-wrap { border-radius:14px; overflow:hidden; border:1px solid rgba(255,255,255,0.1); margin-bottom:24spx; }
        .frame-bar { background:rgba(255,255,255,0.05); border-bottom:1px solid rgba(255,255,255,0.08); padding:9px 14px; display:flex; align-items:center; gap:12px; }
        .frame-dots { display:flex; gap:5px; }
        .frame-dots span { width:10px; height:5px; border-radius:80%; background:rgba(255,255,255,0.12); }
        .frame-url  { flex:1; font-size:.73rem; color:rgba(255,255,255,0.25); }
        .frame-ext  { display:flex; align-items:center; gap:5px; font-size:.73rem; color:#818CF8; text-decoration:none; font-weight:600; }
        .frame-ext:hover { text-decoration:underline; }
        .frame-iframe { width:100%; height:750px; border:none; display:block; }

        .wi-actions { display:flex; justify-content:flex-end; gap:10px; padding-top:20px; border-top:1px solid rgba(255,255,255,0.07); flex-wrap:wrap; }

        .btn { display:inline-flex; align-items:center; gap:7px; padding:11px 22px; border-radius:11px; font-family:'DM Sans',sans-serif; font-size:.875rem; font-weight:700; border:none; cursor:pointer; text-decoration:none; transition:all .18s; }
        .btn:disabled { opacity:.4; cursor:not-allowed; }
        .btn-ghost { background:transparent; color:rgba(255,255,255,0.4); border:1.5px solid rgba(255,255,255,0.1); }
        .btn-ghost:hover { background:rgba(255,255,255,0.06); color:rgba(255,255,255,0.7); }
        .btn-primary { background:linear-gradient(135deg,#6366F1,#818CF8); color:white; box-shadow:0 4px 16px rgba(99,102,241,0.35); }
        .btn-primary:hover { transform:translateY(-1px); box-shadow:0 8px 22px rgba(99,102,241,0.5); }
        .btn-green  { background:linear-gradient(135deg,#10B981,#34D399); color:white; box-shadow:0 4px 16px rgba(16,185,129,0.3); }
        .btn-green:hover  { transform:translateY(-1px); }
        .btn-orange { background:linear-gradient(135deg,#F59E0B,#FBBF24); color:white; box-shadow:0 4px 16px rgba(245,158,11,0.3); }
        .btn-orange:hover { transform:translateY(-1px); }
        .btn-purple { background:linear-gradient(135deg,#9333EA,#A855F7); color:white; box-shadow:0 4px 16px rgba(147,51,234,0.3); }
        .btn-purple:hover { transform:translateY(-1px); }
        .btn-finish { background:linear-gradient(135deg,#6366F1,#EC4899); color:white; box-shadow:0 4px 20px rgba(99,102,241,0.4); padding:13px 28px; font-size:.925rem; }
        .btn-finish:hover { transform:translateY(-2px); box-shadow:0 8px 28px rgba(99,102,241,0.55); }

        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); backdrop-filter:blur(6px); align-items:center; justify-content:center; z-index:999; padding:24px; }
        .modal-overlay.show { display:flex; }
        .modal-box { background:#1E293B; border:1px solid rgba(255,255,255,0.1); border-radius:24px; padding:48px 40px; max-width:420px; width:100%; text-align:center; box-shadow:0 32px 80px rgba(0,0,0,0.5); animation:modalPop .35s cubic-bezier(.34,1.56,.64,1) both; }
        @keyframes modalPop { from{opacity:0;transform:scale(.8)} to{opacity:1;transform:scale(1)} }
        .modal-emoji { font-size:3.5rem; margin-bottom:16px; display:block; animation:bounce 1.2s ease infinite; }
        @keyframes bounce { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        .modal-title { font-family:'Fraunces',serif; font-size:1.5rem; font-weight:900; color:white; margin-bottom:10px; }
        .modal-desc  { font-size:.875rem; color:rgba(255,255,255,0.45); line-height:1.65; margin-bottom:28px; }
        .modal-actions { display:flex; gap:10px; justify-content:center; flex-wrap:wrap; }

        @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
        .sr-only { position:absolute; width:1px; height:1px; overflow:hidden; clip:rect(0,0,0,0); }

        @media(max-width:800px){
            .wi-card { padding:22px; margin:0 12px; }
            .wi-title { font-size:1.7rem; }
            .step-label { display:none; }
            .frame-iframe { height:370px; }
            .wi-actions { justify-content:stretch; }
            .btn { flex:1; justify-content:center; }
        }
    </style>
</head>
<body>

<div class="bg">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
</div>

{{-- HEADER --}}
<div class="wi-header">
    <h1 class="wi-title">Configuración de tu Organización</h1>
    <p class="wi-subtitle">Completa estos 4 pasos para dejar todo listo.</p>
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
            <span class="step-label">Miembro</span>
            <div class="step-connector"></div>
        </div>
        <div class="step-item" data-step="2">
            <div class="step-node">3</div>
            <span class="step-label">Directiva</span>
            <div class="step-connector"></div>
        </div>
        <div class="step-item" data-step="3">
            <div class="step-node">4</div>
            <span class="step-label">Activos</span>
        </div>
    </div>
</div>

{{-- CARD --}}
<div class="wi-card">

    <div class="wi-progress-bar">
        <div class="wi-progress-fill" id="progressFill" style="width:0%"></div>
    </div>
    <p class="wi-progress-txt" id="progressTxt">0 de 4 pasos completados</p>

    {{-- PASO 0: LOGO --}}
    <div id="panel-0">
        <div class="panel-head">
            <div class="panel-icon" style="background:rgba(99,102,241,0.15)"></div>
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
                <p id="logoName" style="font-size:.78rem; color:rgba(255,255,255,0.4)"></p>
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

    {{-- PASO 1: MIEMBRO --}}
    <div id="panel-1" style="display:none">
        <div class="panel-head">
            <div class="panel-icon" style="background:rgba(16,185,129,0.15)">👤</div>
            <div>
                <p class="panel-title">Agregar Miembro</p>
                <p class="panel-desc">Registra los primeros miembros de la organización.</p>
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
            <button class="btn btn-green" onclick="completeStep()">
                Listo, continuar
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>

    {{-- PASO 2: DIRECTIVA --}}
    <div id="panel-2" style="display:none">
        <div class="panel-head">
            <div class="panel-icon" style="background:rgba(245,158,11,0.15)">🏛️</div>
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
            <button class="btn btn-orange" onclick="completeStep()">
                Listo, continuar
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>

    {{-- PASO 3: ACTIVOS --}}
    <div id="panel-3" style="display:none">
        <div class="panel-head">
            <div class="panel-icon" style="background:rgba(147,51,234,0.15)">📦</div>
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
    const totalSteps = 4;
    const completedSteps = [];
    const checkIcon = `<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M2.5 8L6.5 12L13.5 4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>`;

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

    function completeStep() {
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
    }
    function removeLogo() {
        document.getElementById('logoImg').src = '';
        document.getElementById('uploadEmpty').style.display = 'block';
        document.getElementById('uploadPreview').style.display = 'none';
        document.getElementById('uploadZone').classList.remove('has-file');
        document.getElementById('btnUpload').disabled = true;
    }
</script>

</body>
</html>