<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
set_time_limit(600);

function leer_msisdn($archivo) {
    $res = array();
    if (($f = fopen($archivo, "r")) !== false) {
        while (($row = fgetcsv($f, 1000, ",")) !== false) {
            $val = trim($row[0]);
            if ($val != '') $res[] = $val;
        }
        fclose($f);
    }
    return $res;
}

function leer_contenido($archivo) {
    $contenido = @file_get_contents($archivo);
    return str_replace(array("&lt;", "&gt;", "&amp;"), array("<", ">", "&"), $contenido);
}

function extraer_bloques($contenido) {
    preg_match_all('/<httpSample.*?>.*?<\/httpSample>/s', $contenido, $match);
    return $match[0];
}

function entre($texto, $ini, $fin) {
    $ini_pos = strpos($texto, $ini);
    $fin_pos = strpos($texto, $fin, $ini_pos);
    if ($ini_pos === false || $fin_pos === false) return '';
    return trim(substr($texto, $ini_pos + strlen($ini), $fin_pos - $ini_pos - strlen($ini)));
}

function procesar_xls_xml($ruta, $tipo) {
    if (!file_exists($ruta)) return array();
    $xml = leer_contenido($ruta);
    $bloques = extraer_bloques($xml);
    $resultados = array();

    foreach ($bloques as $bloque) {
        $xml_interno = entre($bloque, '<responseData class="java.lang.String">', '</responseData>');
        if ($tipo == 'HLR') {
            $msisdn = entre($xml_interno, '<ns:msisdn>', '</ns:msisdn>');
            $imsi = entre($xml_interno, '<ns:imsi>', '</ns:imsi>');
            $csp  = entre($xml_interno, '<ns:csp>', '</ns:csp>');
            if ($msisdn != '') $resultados[$msisdn] = array($imsi, $csp);
        } elseif ($tipo == 'HSS') {
            $msisdn = entre($xml_interno, '<ns:msisdn>', '</ns:msisdn>');
            $perfil = entre($xml_interno, '<ns:epsProfileId>', '</ns:epsProfileId>');
            if ($msisdn != '') $resultados[$msisdn] = array($perfil);
        } elseif ($tipo == 'IMS' || $tipo == 'ENS') {
            $msisdn = entre($xml_interno, '<ns:msisdn>', '</ns:msisdn>');
            if ($msisdn == '') $msisdn = entre($xml_interno, '<ns:user-name>', '</ns:user-name>');
            if ($tipo == 'ENS') $msisdn = entre($xml_interno, '<ns:E164NUM>', '</ns:E164NUM>');
            if ($msisdn != '') $resultados[$msisdn] = true;
        } elseif ($tipo == 'ATS') {
            // Buscar cualquier MSISDN-like en todo el bloque
            if (preg_match_all('/>?(502\d{6,12})<?/', $xml_interno, $matches)) {
                foreach ($matches[1] as $m) $resultados[trim($m)] = true;
            }
        }
    }
    return $resultados;
}

$mensaje = "";
$resultado = array();
$csv_generado = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['zipfile'])) {
    echo "<script>document.getElementById('progress').style.display='block';</script>";
    flush(); ob_flush();

    $zip = new ZipArchive;
    $tmp = $_FILES['zipfile']['tmp_name'];
    $carpeta = "tmp_" . time();
    mkdir($carpeta);

    if ($zip->open($tmp) === TRUE) {
        $zip->extractTo($carpeta);
        $zip->close();
        echo "<script>document.getElementById('status').innerHTML='✅ ZIP extraído...';</script>";

        $msisdn_csv = "$carpeta/MSISDN.csv";
        if (!file_exists($msisdn_csv)) {
            $mensaje = "<p style='color:red;'>❌ No se encontró MSISDN.csv</p>";
        } else {
            $msisdn_lista = leer_msisdn($msisdn_csv);
            $hlr = procesar_xls_xml("$carpeta/ConsultaHLR.xls", "HLR");
            echo "<script>document.getElementById('status').innerHTML='📡 HLR procesado';</script>";
            $hss = procesar_xls_xml("$carpeta/ConsultaHSS.xls", "HSS");
            echo "<script>document.getElementById('status').innerHTML='📡 HSS procesado';</script>";
            $ims = procesar_xls_xml("$carpeta/ConsultaIMS.xls", "IMS");
            $ats = procesar_xls_xml("$carpeta/ConsultaATS.xls", "ATS");
            $ens = procesar_xls_xml("$carpeta/ConsultaENS.xls", "ENS");

            foreach ($msisdn_lista as $m) {
                $hlr_data = isset($hlr[$m]) ? $hlr[$m] : array("", "");
                $hss_data = isset($hss[$m]) ? $hss[$m] : array("");
                $resultado[] = array(
                    $m,
                    isset($hlr[$m]) ? "APROVISIONADO" : "NO APROVISIONADO",
                    $hlr_data[0],
                    $hlr_data[1],
                    isset($hss[$m]) ? "APROVISIONADO" : "NO APROVISIONADO",
                    $hss_data[0],
                    isset($ims[$m]) ? "APROVISIONADO" : "NO APROVISIONADO",
                    isset($ats[$m]) ? "APROVISIONADO" : "NO APROVISIONADO",
                    isset($ens[$m]) ? "APROVISIONADO" : "NO APROVISIONADO"
                );
            }

            $csv_generado = "$carpeta/Resultado_" . time() . ".csv";
            $h = fopen($csv_generado, "w");
            fputcsv($h, array("MSISDN", "HLR", "IMSI", "CSP", "HSS", "PERFIL", "IMS", "ATS", "ENS"));
            foreach ($resultado as $fila) fputcsv($h, $fila);
            fclose($h);
        }
    } else {
        $mensaje = "<p style='color:red;'>❌ No se pudo abrir el ZIP</p>";
    }

    echo "<script>document.getElementById('progress').style.display='none';</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🔍 Analizador VoLTE</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <style>
    body {
      background: #121212; color: #eee; font-family: Arial, sans-serif;
      text-align: center; padding: 20px;
    }
    table {
      margin-top: 20px;
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 10px; border: 1px solid #333;
    }
    th { background: #222; }
    input[type="submit"], .btn {
      background: #2196F3; color: white;
      border: none; padding: 10px 20px;
      font-weight: bold; cursor: pointer;
      border-radius: 5px; transition: background 0.3s;
    }
    input[type="submit"]:hover {
      background: #1976D2;
    }
    #progress {
      display: none; margin: 20px auto; width: 80%;
    }
    #bar {
      width: 0%; height: 25px; background: #4caf50;
      transition: width 1s ease-in-out;
    }
    #progress-container {
      width: 100%; background: #333; border-radius: 10px; overflow: hidden;
    }
  </style>
</head>
<body>

<h2>📁 Analizador VoLTE (modo oscuro, animado)</h2>

<form method="POST" enctype="multipart/form-data">
  <input type="file" name="zipfile" accept=".zip" required>
  <br><br><input type="submit" value="📦 Subir y Analizar ZIP">
</form>

<div id="progress">
  <p id="status">⏳ Procesando archivo ZIP...</p>
  <div id="progress-container"><div id="bar"></div></div>
</div>

<?php echo $mensaje; ?>

<?php if (!empty($resultado)): ?>
  <h3>📊 Resultado del Análisis</h3>
  <p>⬇️ <a class="btn" href="<?php echo $csv_generado; ?>" download>Descargar CSV</a></p>
  <table id="tabla">
    <thead>
      <tr><th>MSISDN</th><th>HLR</th><th>IMSI</th><th>CSP</th><th>HSS</th><th>PERFIL</th><th>IMS</th><th>ATS</th><th>ENS</th></tr>
    </thead>
    <tbody>
    <?php foreach ($resultado as $fila): ?>
      <tr><?php foreach ($fila as $celda): ?><td><?php echo htmlspecialchars($celda); ?></td><?php endforeach; ?></tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function(){
    $('#tabla').DataTable({
      responsive: true,
      language: { search: "🔎 Buscar:" }
    });
  });

  // Simula barra de progreso
  var interval = setInterval(function(){
    var bar = document.getElementById('bar');
    if (bar && bar.offsetWidth < 100) {
      bar.style.width = (bar.offsetWidth + 10) + "%";
    } else {
      clearInterval(interval);
    }
  }, 800);
</script>

</body>
</html>
