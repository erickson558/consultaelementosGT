<?php
// index.php - PHP 5.4.31 Compatible

// -------------------------------------------------------------
// Helpers: parsing del archivo y utilidades
// -------------------------------------------------------------

function read_uploaded_text($key) {
    if (!isset($_FILES[$key]) || !is_uploaded_file($_FILES[$key]['tmp_name'])) return '';
    $name = $_FILES[$key]['name'];
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if ($ext !== 'axess') {
        return '';
    }
    $data = file_get_contents($_FILES[$key]['tmp_name']);
    return $data === false ? '' : $data;
}

function parse_ip($ip) {
    // Devuelve array [a,b,c,d] o false
    if (!preg_match('/^\s*(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\s*$/', $ip, $m)) return false;
    $a = intval($m[1]); $b = intval($m[2]); $c = intval($m[3]); $d = intval($m[4]);
    if ($a>255||$b>255||$c>255||$d>255) return false;
    return array($a,$b,$c,$d);
}

function build_giaddrs_from_range($hostMin, $hostMax) {
    // hostMin/hostMax como strings
    $s = parse_ip($hostMin);
    $e = parse_ip($hostMax);
    if ($s === false || $e === false) return array('error' => 'IPs inválidas');
    // Reglas simples: mismos primeros dos octetos; rango por tercer octeto; giaddr usa .1
    if ($s[0] !== $e[0] || $s[1] !== $e[1]) {
        return array('error' => 'Los dos primeros octetos deben ser iguales (ej. 10.74.x.x).');
    }
    if ($s[2] > $e[2]) {
        return array('error' => 'El tercer octeto de inicio es mayor que el final.');
    }
    $gi = array();
    for ($c = $s[2]; $c <= $e[2]; $c++) {
        $gi[] = $s[0].'.'.$s[1].'.'.$c.'.1';
    }
    return $gi;
}

function find_country_bounds($rows, $country) {
    $start = -1; $end = -1;
    $countryPattern = '/"'.preg_quote($country,'/').'"\s*:\s*\[/';
    for ($i=0; $i<count($rows); $i++){
        if (preg_match($countryPattern, $rows[$i])) { $start = $i; break; }
    }
    if ($start === -1) return array(-1,-1);

    // balance de corchetes para cerrar el array del país
    $br = 0;
    for ($i=$start; $i<count($rows); $i++){
        $line = $rows[$i];
        $br += substr_count($line, '[') - substr_count($line, ']');
        if ($br === 0) { $end = $i; break; }
    }
    if ($end === -1) $end = count($rows)-1;
    return array($start,$end);
}

function list_countries($text) {
    $rows = preg_split("/\r\n|\n|\r/", $text);
    $out = array();
    foreach ($rows as $line) {
        if (preg_match('/"([A-Z]{2})"\s*:\s*\[/', $line, $m)) {
            $out[$m[1]] = true;
        }
    }
    return array_keys($out);
}

function list_cmts_in_country($text, $country) {
    $rows = preg_split("/\r\n|\n|\r/", $text);
    list($cs, $ce) = find_country_bounds($rows, $country);
    if ($cs === -1) return array();
    $out = array();
    for ($i=$cs; $i<=$ce; $i++) {
        if (preg_match('/"name"\s*:\s*"([^"]+)"/', $rows[$i], $m)) {
            $out[$m[1]] = true;
        }
    }
    return array_keys($out);
}

// -------------------------------------------------------------
// Inserción principal con tabulación + imsIndex detectado
// -------------------------------------------------------------
function text_insert_after_cmts($baseText, $country, $cmtsName, $itemsGiaddr, $forceImsIndex = '') {
    $rows = preg_split("/\r\n|\n|\r/", $baseText);

    // 1) localizar bloque del país
    list($countryStart, $countryEnd) = find_country_bounds($rows, $country);
    if ($countryStart === -1) {
        // país no ubicado: sugerencia al final
        $block = array("");
        $block[] = "# === No se ubicó CountrySpecific[{$country}] / CMTS \"{$cmtsName}\". Sugerencias: ===";
        if (is_array($itemsGiaddr)) {
            foreach ($itemsGiaddr as $g){
                $block[] = '# {"name": "'.$cmtsName.'", "giaddr": "'.$g.'", "imsIndex": "'.($forceImsIndex!==''?$forceImsIndex:'<<imsIndex_del_CMTS>>').'"},';
            }
        }
        $block[] = "# === Fin sugerencia ===";
        return rtrim($baseText,"\r\n")."\n".implode("\n",$block)."\n";
    }

    // 2) primera línea del CMTS dentro del país
    $needle = '"name": "'.$cmtsName.'"';
    $cmtsIdx = -1;
    for ($i=$countryStart; $i<=$countryEnd; $i++){
        if (strpos($rows[$i], $needle) !== false) { $cmtsIdx = $i; break; }
    }

    // 3) indentación + imsIndex
    $indent = '            '; // fallback (12 espacios aprox como tu ejemplo)
    $imsIndex = ($forceImsIndex !== '') ? $forceImsIndex : '';
    if ($cmtsIdx !== -1) {
        $line = $rows[$cmtsIdx];
        if (preg_match('/^(\s*)/', $line, $mIndent)) { $indent = $mIndent[1]; }

        // intentar en la misma línea
        if ($imsIndex === '' && preg_match('/"imsIndex"\s*:\s*"([^"]+)"/', $line, $mIms)) {
            $imsIndex = $mIms[1];
        }
        // buscar arriba/abajo cercanos si no estaba en la misma línea
        if ($imsIndex === '') {
            for ($j=max($countryStart,$cmtsIdx-5); $j<=min($countryEnd,$cmtsIdx+5); $j++){
                if (preg_match('/"imsIndex"\s*:\s*"([^"]+)"/', $rows[$j], $mIms2)) {
                    $imsIndex = $mIms2[1];
                    break;
                }
            }
        }
    }
    if ($imsIndex === '') $imsIndex = '<<imsIndex_del_CMTS>>';

    // 4) construir nuevas líneas
    $newLines = array();
    if (is_array($itemsGiaddr)) {
        foreach ($itemsGiaddr as $g){
            $newLines[] = $indent.'{"name": "'.$cmtsName.'", "giaddr": "'.$g.'", "imsIndex": "'.$imsIndex.'"},';
        }
    }

    // 5) insertar
    if ($cmtsIdx !== -1) {
        $before = array_slice($rows, 0, $cmtsIdx+1);
        $after  = array_slice($rows, $cmtsIdx+1);
        return implode("\n", array_merge($before, $newLines, $after));
    } else {
        // CMTS no encontrado dentro del país -> insertamos antes del cierre del país
        $insertAt = $countryEnd;
        $before = array_slice($rows, 0, $insertAt);
        $after  = array_slice($rows, $insertAt);
        return implode("\n", array_merge($before, $newLines, $after));
    }
}

// -------------------------------------------------------------
// Evitar duplicados en el país (opcional pero recomendado)
// -------------------------------------------------------------
function country_giaddrs_present($baseText, $country){
    $present = array();
    $rows = preg_split("/\r\n|\n|\r/", $baseText);
    list($countryStart,$countryEnd) = find_country_bounds($rows, $country);
    if ($countryStart === -1) return $present;

    $bracket = 0;
    for ($i=$countryStart; $i<count($rows); $i++){
        $line = $rows[$i];
        $bracket += substr_count($line, '[') - substr_count($line, ']');
        if (preg_match('/"giaddr"\s*:\s*"([^"]+)"/', $line, $m)) {
            $present[$m[1]] = true;
        }
        if ($bracket === 0) break;
    }
    return $present;
}

// -------------------------------------------------------------
// Lógica de formulario
// -------------------------------------------------------------
$baseText = '';
$resultText = '';
$error = '';
$downloadPath = '';
$countries = array();
$cmtsCandidates = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Paso 1: leer archivo (nuevo upload o persistido via hidden)
    if (isset($_POST['base_raw']) && $_POST['base_raw'] !== '') {
        // viene desde un textarea/hidden (por si no se re-sube)
        $baseText = $_POST['base_raw'];
    } else {
        $baseText = read_uploaded_text('axess');
        if ($baseText === '') {
            $error = 'Sube un archivo .axess válido.';
        }
    }

    if ($baseText !== '') {
        $countries = list_countries($baseText);
    }

    // Recoger inputs
    $country   = isset($_POST['country']) ? trim($_POST['country']) : '';
    $cmtsName  = isset($_POST['cmts']) ? trim($_POST['cmts']) : '';
    $hostMin   = isset($_POST['hostmin']) ? trim($_POST['hostmin']) : '';
    $hostMax   = isset($_POST['hostmax']) ? trim($_POST['hostmax']) : '';
    $forceIms  = isset($_POST['forceims']) ? trim($_POST['forceims']) : '';

    if ($baseText !== '' && $country !== '') {
        $cmtsCandidates = list_cmts_in_country($baseText, $country);
    }

    if (isset($_POST['action']) && $_POST['action'] === 'preview' && $error === '') {
        if ($country === '' || $cmtsName === '' || $hostMin === '' || $hostMax === '') {
            $error = 'Faltan campos: país, CMTS, primera y última dirección.';
        } else {
            $gi = build_giaddrs_from_range($hostMin, $hostMax);
            if (isset($gi['error'])) {
                $error = $gi['error'];
            } else {
                // Filtrar duplicados existentes en el país
                $present = country_giaddrs_present($baseText, $country);
                $gi = array_values(array_filter($gi, function($g) use ($present){
                    return !isset($present[$g]);
                }));

                if (empty($gi)) {
                    $error = 'Nada que agregar: todos los giaddr del rango ya existen en ese país.';
                } else {
                    $resultText = text_insert_after_cmts($baseText, $country, $cmtsName, $gi, $forceIms);
                    // Guardar para descarga
                    $outName = 'output_'.date('Ymd_His').'.axess';
                    $full = dirname(__FILE__).DIRECTORY_SEPARATOR.$outName;
                    file_put_contents($full, $resultText);
                    $downloadPath = $outName;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Editor de Pools por CMTS para archivo .axess</title>
    <style>
        body { font-family: Consolas, "Courier New", monospace; background:#f7f7f7; margin:20px; }
        .card { background:#fff; border:1px solid #ddd; border-radius:8px; padding:16px; margin-bottom:16px; }
        label { display:block; margin-top:8px; font-weight:bold; }
        input[type=text], select { width:100%; padding:6px; box-sizing:border-box; }
        textarea { width:100%; height:360px; white-space:pre; }
        .row { display:flex; gap:16px; }
        .col { flex:1; }
        .btn { background:#1e88e5; color:#fff; padding:8px 14px; border:none; border-radius:4px; cursor:pointer; }
        .btn:disabled { opacity:.5; cursor:not-allowed; }
        .error { color:#c62828; font-weight:bold; }
        .hint { color:#666; font-size:12px; }
        .pill { display:inline-block; background:#eee; border-radius:999px; padding:2px 8px; margin-right:6px; }
    </style>
</head>
<body>
    <h2>Editor de Pools por CMTS para archivo .axess</h2>

    <form method="post" enctype="multipart/form-data" class="card">
        <div class="row">
            <div class="col">
                <label>Archivo base (.axess)</label>
                <input type="file" name="axess" accept=".axess">
                <div class="hint">Si ya cargaste un archivo, puedes seguir usando el mismo contenido sin volver a subirlo.</div>
            </div>
        </div>

        <?php if ($baseText !== ''): ?>
            <input type="hidden" name="base_raw" value="<?php echo htmlspecialchars($baseText, ENT_QUOTES, 'UTF-8'); ?>">
            <div style="margin-top:8px;">
                <span class="pill">País detectados: <?php echo count($countries); ?></span>
                <?php foreach ($countries as $cc) echo '<span class="pill">'.htmlspecialchars($cc).'</span>'; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col">
    <label>País (código)</label>
    <select name="country">
        <option value="">-- Selecciona --</option>
        <?php
        // Si detectamos países del archivo, los usamos.
        if (!empty($countries)) {
            foreach ($countries as $cc) {
                $sel = (isset($_POST['country']) && $_POST['country'] === $cc) ? 'selected' : '';
                echo '<option value="'.htmlspecialchars($cc).'" '.$sel.'>'.htmlspecialchars($cc).'</option>';
            }
        } else {
            // Si aún no hay archivo cargado, mostramos una lista base (ajústala si quieres)
            $defaultCountries = array('GT','SV','HN','NI','CR','PA');
            foreach ($defaultCountries as $cc) {
                $sel = (isset($_POST['country']) && $_POST['country'] === $cc) ? 'selected' : '';
                echo '<option value="'.htmlspecialchars($cc).'" '.$sel.'>'.htmlspecialchars($cc).'</option>';
            }
        }
        ?>
    </select>
    <div class="hint">El listado se llena automáticamente al cargar tu archivo .axess.</div>
</div>

            <div class="col">
                <label>CMTS (ej. AMX-SAL-SON-E6000)</label>
                <?php if (!empty($cmtsCandidates)): ?>
                    <input list="cmtsList" name="cmts" value="<?php echo isset($_POST['cmts'])?htmlspecialchars($_POST['cmts']):''; ?>">
                    <datalist id="cmtsList">
                        <?php foreach ($cmtsCandidates as $nm): ?>
                            <option value="<?php echo htmlspecialchars($nm); ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                <?php else: ?>
                    <input type="text" name="cmts" placeholder="AMX-SAL-SON-E6000" value="<?php echo isset($_POST['cmts'])?htmlspecialchars($_POST['cmts']):''; ?>">
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label>Primera dirección utilizable (HostMin)</label>
                <input type="text" name="hostmin" placeholder="10.74.32.1" value="<?php echo isset($_POST['hostmin'])?htmlspecialchars($_POST['hostmin']):''; ?>">
            </div>
            <div class="col">
                <label>Última dirección utilizable (HostMax)</label>
                <input type="text" name="hostmax" placeholder="10.74.63.254" value="<?php echo isset($_POST['hostmax'])?htmlspecialchars($_POST['hostmax']):''; ?>">
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label>Forzar imsIndex (opcional)</label>
                <input type="text" name="forceims" placeholder="10.192.66.43" value="<?php echo isset($_POST['forceims'])?htmlspecialchars($_POST['forceims']):''; ?>">
                <div class="hint">Si se deja vacío, el sistema detecta el imsIndex del CMTS en el archivo base.</div>
            </div>
            <div class="col" style="display:flex; align-items:flex-end;">
                <button type="submit" name="action" value="preview" class="btn">Generar / Previsualizar</button>
            </div>
        </div>

        <?php if ($error !== ''): ?>
            <div class="error" style="margin-top:10px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </form>

    <?php if ($resultText !== ''): ?>
        <div class="card">
            <label>Resultado</label>
            <textarea readonly><?php echo htmlspecialchars($resultText, ENT_QUOTES, 'UTF-8'); ?></textarea>
            <?php if ($downloadPath !== ''): ?>
                <div style="margin-top:8px;">
                    <a class="btn" href="<?php echo htmlspecialchars($downloadPath); ?>" download>Descargar archivo modificado</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <strong>Notas:</strong>
        <ul>
            <li>Los giaddr se generan como <em>.1</em> para cada tercer octeto entre HostMin y HostMax.</li>
            <li>Si el CMTS existe en el país, las nuevas líneas se insertan justo debajo de la primera coincidencia.</li>
            <li>La tabulación (espacios iniciales) se toma de la línea del CMTS para que el formato sea idéntico.</li>
            <li>Se evitan duplicados de giaddr ya presentes en el array del país.</li>
        </ul>
    </div>
</body>
</html>
