<?php
// ---------------------------------------------
// CONFIG & HELPERS (compatibles con PHP 5.4.31)
// ---------------------------------------------

// Evita notices en producción (ajusta según tu entorno)
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

// --- Helpers sencillos ---
function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// Detecta petición AJAX
function is_ajax() {
    return (isset($_POST['ajax']) && $_POST['ajax'] === '1');
}

// Lee países detectados del .axess (si ya lo parseas en tu flujo)
$countries = array(); // se llenará al subir archivo
$countrySelected = isset($_POST['country']) ? $_POST['country'] : '';

// Estado del procesamiento
$result_text = '';
$result_info = array();
$errors = array();

// -------------------------------
// LÓGICA PRINCIPAL (BACKEND)
// -------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Validaciones básicas
    $countrySelected = trim($_POST['country']);
    $cmtsName = trim($_POST['cmts_name']);
    $hostMin   = trim($_POST['host_min']); // ej: 10.74.32.1
    $hostMax   = trim($_POST['host_max']); // ej: 10.74.63.254
    $fileOk    = isset($_FILES['axess']) && $_FILES['axess']['error'] === UPLOAD_ERR_OK;

    if (!$fileOk) { $errors[] = 'Debes subir un archivo .axess'; }
    if ($countrySelected === '') { $errors[] = 'Selecciona un país'; }
    if ($cmtsName === '') { $errors[] = 'Ingresa/selecciona el CMTS'; }
    if ($hostMin === '' || $hostMax === '') { $errors[] = 'Completa HostMin y HostMax'; }

    // 2) Si todo ok, procesar
    if (!$errors) {
        $raw = file_get_contents($_FILES['axess']['tmp_name']);

        // TODO: Aquí parseas el archivo .axess para:
        //  - detectar lista de países ($countries)
        //  - encontrar el imsIndex del CMTS elegido ($imsIndexForCMTS)
        //  - insertar pools bajo el CMTS y devolver texto final ($result_text)

        // DEMO de cómo podrías obtener imsIndex del CMTS desde el archivo:
        // Busca una línea con: {"name": "<CMTS>", "giaddr": "...", "imsIndex": "X.X.X.X"},
        $imsIndexForCMTS = '';
        $pattern = '/\{"name":\s*"'.preg_quote($cmtsName,'/').'"\s*,\s*"giaddr":\s*"[0-9\.]+"\s*,\s*"imsIndex":\s*"([0-9\.]+)"\s*\},?/i';
        if (preg_match($pattern, $raw, $m)) {
            $imsIndexForCMTS = $m[1];
        }

        // Calcula el rango de GIADDRs (solo cambia el 3er octeto de hostMin..hostMax)
        // Extrae base A.B.X.Y
        function ip_to_parts($ip) {
            $p = explode('.', $ip);
            return count($p) === 4 ? array_map('intval', $p) : array(0,0,0,0);
        }
        list($a1,$a2,$a3,$a4) = ip_to_parts($hostMin);
        list($b1,$b2,$b3,$b4) = ip_to_parts($hostMax);

        if ($a1 !== $b1 || $a2 !== $b2) {
            $errors[] = 'HostMin y HostMax deben compartir los dos primeros octetos (A.B).';
        } elseif ($a3 > $b3) {
            $errors[] = 'El tercer octeto de HostMin no puede ser mayor que el de HostMax.';
        } else {
            // Generar líneas nuevas respetando tabulación del archivo (4 espacios por seguridad)
            $indent = '';
            // intenta detectar indent de la línea del CMTS original
            if (preg_match('/^(\s*)\{"name":\s*"'.preg_quote($cmtsName,'/').'"[^\n]*$/m', $raw, $mm)) {
                $indent = $mm[1];
            } else {
                $indent = "            "; // fallback (12 espacios) como en ejemplo
            }

            // Si no se detectó imsIndex, dejar el placeholder para que sea visible
            if ($imsIndexForCMTS === '') {
                $imsIndexForCMTS = '<<imsIndex_del_CMTS>>';
            }

            $newLines = array();
            for ($oct3 = $a3; $oct3 <= $b3; $oct3++) {
                $gi = $a1.'.'.$a2.'.'.$oct3.'.1';
                $newLines[] = $indent.'{"name": "'.$cmtsName.'", "giaddr": "'.$gi.'", "imsIndex": "'.$imsIndexForCMTS.'"},';
            }

            // Insertar justo DESPUÉS de la primera aparición del CMTS base
            // Ejemplo: debajo de {"name": "AMX-SAL-SON-E6000", "giaddr": "10.69.64.1", "imsIndex": "10.192.66.43"},
            $anchorRegex = '/(\{"name":\s*"'.preg_quote($cmtsName,'/').'"\s*,\s*"giaddr":\s*"[0-9\.]+"\s*,\s*"imsIndex":\s*"[0-9\.]+"\s*\},\s*\R)/i';
            if (preg_match($anchorRegex, $raw, $am, PREG_OFFSET_CAPTURE)) {
                $pos = $am[1][1] + strlen($am[1][0]);
                $result_text = substr($raw, 0, $pos) . implode("\r\n", $newLines) . "\r\n" . substr($raw, $pos);
            } else {
                // Si no encontramos ancla, agregamos al final del bloque del país
                $result_text = $raw . "\r\n" . implode("\r\n", $newLines) . "\r\n";
            }

            $result_info = array(
                'imsIndex'  => $imsIndexForCMTS,
                'added'     => count($newLines),
                'from'      => $a1.'.'.$a2.'.'.$a3.'.1',
                'to'        => $a1.'.'.$a2.'.'.$b3.'.1'
            );
        }
    }

    // Respuesta AJAX (JSON)
    if (is_ajax()) {
        header('Content-Type: application/json; charset=utf-8');
        if ($errors) {
            echo json_encode(array('ok'=>false, 'errors'=>$errors));
        } else {
            echo json_encode(array('ok'=>true, 'info'=>$result_info, 'text'=>$result_text));
        }
        exit;
    }
}

// ---------------------------------------------
// HTML + UI (Bootstrap + Select2 + Animate.css)
// ---------------------------------------------
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Editor de Pools por CMTS (.axess)</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap 4.6 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!-- Select2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css"/>

<style>
/* Ajustes visuales */
body { background: #0f172a; color: #e2e8f0; } /* azul oscuro + texto claro */
.card { border-radius: 14px; }
.card-header { background: #111827; border-bottom: 0; }
.card-body { background: #0b1220; }
label { font-weight: 600; }
small.hint { color:#94a3b8; }
pre#output { max-height: 55vh; overflow:auto; background:#020617; color:#e2e8f0; padding:1rem; border-radius:10px; border:1px solid #1f2937; }
.select2-container .select2-selection--single { height: 38px; }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 38px; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px; }
.btn-gradient {
    background: linear-gradient(90deg,#6366f1,#22d3ee);
    border:none; color:#0b1220; font-weight:700;
}
.btn-gradient:disabled { opacity: .7; }
.badge-soft { background:#111827; border:1px solid #334155; color:#93c5fd; }
.toast {
    opacity:1; background:#111827; color:#e2e8f0; border:1px solid #334155;
}
.custom-file-input ~ .custom-file-label::after { content: "Buscar"; }
.form-control, .custom-file-label { background:#0b1220; color:#e2e8f0; border:1px solid #334155; }
.form-control:focus, .custom-file-input:focus ~ .custom-file-label { border-color:#60a5fa; box-shadow:none; }
a, a:hover { color:#93c5fd; }
/* ----- Select2 dark (combobox País) ----- */
.select2-container--default .select2-selection--single {
  background-color: #0b1220 !important;
  border: 1px solid #334155 !important;
  color: #e2e8f0 !important;
  height: 38px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
  color: #e2e8f0 !important;
  line-height: 38px !important;
  padding-left: 0.75rem !important;
}
.select2-container--default .select2-selection--single .select2-selection__placeholder {
  color: #94a3b8 !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
  height: 36px !important;
}
.select2-container--default .select2-selection--single:focus,
.select2-container--default.select2-container--focus .select2-selection--single {
  border-color: #60a5fa !important;
  box-shadow: none !important;
}

/* Dropdown*
/* --- Parche Dark total para Select2 (dropdown y buscador) --- */
.select2-container--default .select2-selection--single {
  background-color: #0b1220 !important;
  border: 1px solid #334155 !important;
  color: #e2e8f0 !important;
}

.select2-dropdown {
  background-color: #0b1220 !important;
  border: 1px solid #334155 !important;
}

.select2-container .select2-search--dropdown .select2-search__field {
  background-color: #0b1220 !important;
  color: #e2e8f0 !important;
  border: 1px solid #334155 !important;
}

.select2-results__options {
  background-color: #0b1220 !important;
  color: #e2e8f0 !important;
}

.select2-results__option--highlighted[aria-selected],
.select2-results__option--highlighted {
  background-color: #1f2937 !important;
  color: #93c5fd !important;
}

.select2-results__option[aria-selected="true"] {
  background-color: #111827 !important;
  color: #93c5fd !important;
}

/* Borde y foco coherentes */
.select2-container--default .select2-selection--single:focus,
.select2-container--default.select2-container--focus .select2-selection--single {
  border-color: #60a5fa !important;
  box-shadow: none !important;
}

/* Scroll del dropdown (opcional) */
.select2-results__options::-webkit-scrollbar { width: 10px; }
.select2-results__options::-webkit-scrollbar-thumb { background: #334155; border-radius: 6px; }
.select2-results__options::-webkit-scrollbar-track { background: #0b1220; }

</style>
</head>
<body>

<div class="container py-4">
    <div class="mb-4 text-center">
        <h1 class="animate__animated animate__fadeInDown">Editor de Pools por CMTS</h1>
        <p class="text-muted">Genera e inserta pools en archivos <strong>.axess</strong> con cálculo de rangos por 3er octeto.</p>
    </div>

    <!-- ALERTS (PHP no-AJAX fallback) -->
    <?php if ($_SERVER['REQUEST_METHOD']==='POST' && !is_ajax()): ?>
        <?php if ($errors): ?>
            <div class="alert alert-danger animate__animated animate__fadeInDown">
                <strong>Ups:</strong>
                <ul class="mb-0"><?php foreach ($errors as $e) echo '<li>'.h($e).'</li>'; ?></ul>
            </div>
        <?php else: ?>
            <div class="alert alert-success animate__animated animate__fadeInDown">
                <strong>Listo:</strong> Se agregaron <?php echo (int)$result_info['added'];?> pools. IMS Index: <span class="badge badge-soft"><?php echo h($result_info['imsIndex']);?></span>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="card shadow animate__animated animate__fadeInUp">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Archivo base y parámetros</h5>
                <small class="hint">Sube tu archivo .axess, selecciona País y CMTS, define HostMin / HostMax y genera.</small>
            </div>
            <span class="badge badge-soft">PHP 5.4 compatible</span>
        </div>
        <div class="card-body">

            <form id="poolForm" method="post" enctype="multipart/form-data" class="mb-3">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Archivo .axess debe guardarse una copia del archivo /live/CFG/CMTSConfig con extensión .axess</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="axess" name="axess" accept=".axess,.txt">
                            <label class="custom-file-label" for="axess">Selecciona archivo...</label>
                        </div>
                        <small class="hint">El listado de países se llenará automáticamente tras procesar.</small>
                    </div>

                    <div class="form-group col-md-3">
                        <label>País</label>
                        <select class="form-control select2" name="country" id="country">
                            <option value="">-- Selecciona --</option>
                            <?php
                            // Si detectas países del archivo, imprime aquí
                            if (!empty($countries)) {
                                foreach ($countries as $cc) {
                                    $sel = ($cc === $countrySelected) ? 'selected' : '';
                                    echo '<option value="'.h($cc).'" '.$sel.'>'.h($cc).'</option>';
                                }
                            } else {
                                foreach (array('GT','SV','HN','NI','CR','PA') as $cc) {
                                    $sel = ($cc === $countrySelected) ? 'selected' : '';
                                    echo '<option value="'.h($cc).'" '.$sel.'>'.h($cc).'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label>CMTS</label>
                        <input type="text" class="form-control" name="cmts_name" id="cmts_name" placeholder="Ej: AMX-SAL-SON-E6000" value="<?php echo h($_POST['cmts_name']); ?>">
                        <small class="hint">Puedes pegar el nombre exacto tal cual en el .axess.</small>
                    </div>
                </div>

                <div class="form-row">

                    <div class="form-group col-md-3">
                        <label>HostMin</label>
                        <input type="text" class="form-control" name="host_min" id="host_min" placeholder="10.74.32.1" value="<?php echo h($_POST['host_min']); ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label>HostMax</label>
                        <input type="text" class="form-control" name="host_max" id="host_max" placeholder="10.74.63.254" value="<?php echo h($_POST['host_max']); ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>&nbsp;</label>
                        <div class="d-flex">
                            <button type="button" id="btnPreview" class="btn btn-gradient mr-2">
                                <span class="spinner-border spinner-border-sm d-none" id="spPreview"></span>
                                Procesar
                            </button>
                        </div>
                        <small class="hint">“Vista previa” no recarga la página; “Procesar” usa envío tradicional.</small>
                    </div>
                </div>

                <div class="mt-2">
                    <a class="text-decoration-none" data-toggle="collapse" href="#advanced" role="button" aria-expanded="false" aria-controls="advanced">
                        Parámetros avanzados (opcional)
                    </a>
                    <div class="collapse mt-2" id="advanced">
                        <div class="p-3" style="background:#0a1426;border:1px solid #1f2937;border-radius:10px;">
                            <small class="hint">Aquí podrías colocar flags extras si los necesitas en el futuro.</small>
                        </div>
                    </div>
                </div>

                <!-- Campo oculto para AJAX -->
                <input type="hidden" name="ajax" id="ajax" value="0">
            </form>

            <div id="resultWrap" class="d-none animate__animated">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Resultado</h5>
                    <div>
                        <button class="btn btn-sm btn-outline-info" id="btnCopy" data-toggle="tooltip" title="Copiar al portapapeles">Copiar</button>
                        <a id="btnDownload" class="btn btn-sm btn-outline-success ml-2" download="archivo_modificado.axess">Descargar</a>
                    </div>
                </div>
                <div class="mb-2">
                    <span class="badge badge-soft" id="badgeAdded">0 pools</span>
                    <span class="badge badge-soft" id="badgeIMS">imsIndex: n/d</span>
                    <span class="badge badge-soft" id="badgeRange">rango: n/d</span>
                </div>
                <pre id="output" class="animate__animated"><code></code></pre>
            </div>

        </div>
    </div>

    <!-- Toast -->
    <div aria-live="polite" aria-atomic="true" style="position: fixed; bottom: 15px; right: 15px; z-index: 1080;">
        <div id="appToast" class="toast" data-delay="2500" style="min-width:260px;">
            <div class="toast-body">Listo.</div>
        </div>
    </div>

    <footer class="text-center mt-4 text-muted">
        <small>Hecho para PHP 5.4.31 · Bootstrap 4.6 · jQuery 3.5 · Select2</small>
    </footer>
</div>

<!-- JS: jQuery + Popper + Bootstrap + Select2 -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
(function(){
    'use strict';

    // Select2
    $('.select2').select2({ width: '100%' });

    // Tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Nombre visible del archivo
    $('#axess').on('change', function(){
        var fileName = (this.files && this.files.length) ? this.files[0].name : 'Selecciona archivo...';
        $(this).next('.custom-file-label').text(fileName);
    });

    // Copiar al portapapeles
    $('#btnCopy').on('click', function(){
        var text = $('#output').text();
        if (!text) return;
        var ta = document.createElement('textarea');
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); showToast('Contenido copiado'); }
        catch(e){ showToast('No se pudo copiar'); }
        document.body.removeChild(ta);
    });

    // Descargar archivo
    function setDownload(text) {
        var blob = new Blob([text], {type: 'text/plain;charset=utf-8'});
        var url  = URL.createObjectURL(blob);
        $('#btnDownload').attr('href', url);
    }

    // Toast helper
    function showToast(msg){
        $('#appToast .toast-body').text(msg);
        $('#appToast').toast('show');
    }

    // Vista previa por AJAX
    $('#btnPreview').on('click', function(){
        var $btn = $(this);
        var $spin = $('#spPreview');
        var form = document.getElementById('poolForm');
        var fd = new FormData(form);
        fd.set('ajax','1');

        // Validación simple en front
        if (!$('#axess')[0].files.length) { showToast('Sube un archivo .axess primero'); return; }
        if (!$('#country').val()) { showToast('Selecciona un país'); return; }
        if (!$('#cmts_name').val()) { showToast('Indica el CMTS'); return; }
        if (!$('#host_min').val() || !$('#host_max').val()) { showToast('Completa HostMin y HostMax'); return; }

        $btn.prop('disabled', true); $spin.removeClass('d-none');

        $.ajax({
            url: '',
            method: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            cache: false
        }).done(function(resp){
            if (resp && resp.ok) {
                var info = resp.info || {};
                var text = resp.text || '';

                $('#badgeAdded').text((info.added||0)+' pools');
                $('#badgeIMS').text('imsIndex: '+(info.imsIndex||'n/d'));
                if (info.from && info.to) $('#badgeRange').text('rango: '+info.from+' .. '+info.to);

                $('#output').find('code').text(text);
                $('#resultWrap').removeClass('d-none').addClass('animate__fadeInUp');
                setDownload(text);

                $('html, body').animate({scrollTop: $('#resultWrap').offset().top - 20}, 400);
                showToast('Vista previa generada');
            } else {
                var errs = (resp && resp.errors) ? ('• ' + resp.errors.join('\n• ')) : 'Error desconocido';
                showToast(errs);
            }
        }).fail(function(){
            showToast('Fallo la petición AJAX');
        }).always(function(){
            $btn.prop('disabled', false); $spin.addClass('d-none');
        });
    });

})();
</script>
</body>
</html>
