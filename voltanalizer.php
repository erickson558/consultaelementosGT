<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);
ini_set('memory_limit', '2048M');
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

function extraer_bloques_desde_archivo($archivo) {
    $bloques = array();
    $handle = fopen($archivo, "r");
    if (!$handle) return $bloques;

    $buffer = '';
    while (!feof($handle)) {
        $buffer .= fread($handle, 8192);
        while (preg_match('/<httpSample.*?>.*?<\/httpSample>/s', $buffer, $match, PREG_OFFSET_CAPTURE)) {
            $bloques[] = str_replace(array("&lt;", "&gt;", "&amp;"), array("<", ">", "&"), $match[0][0]);
            $buffer = substr($buffer, $match[0][1] + strlen($match[0][0]));
        }
        if (count($bloques) > 10000) break;
    }
    fclose($handle);
    return $bloques;
}

function entre($texto, $ini, $fin) {
    $ini_pos = strpos($texto, $ini);
    $fin_pos = strpos($texto, $fin, $ini_pos);
    if ($ini_pos === false || $fin_pos === false) return '';
    return trim(substr($texto, $ini_pos + strlen($ini), $fin_pos - $ini_pos - strlen($ini)));
}

function procesar_xls_xml($ruta, $tipo) {
    if (!file_exists($ruta)) return array();
    $bloques = extraer_bloques_desde_archivo($ruta);
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
            if (preg_match_all('/(503\d{6,12})/', $xml_interno, $matches)) {
                foreach ($matches[1] as $m) {
                    $num = trim(preg_replace('/\D/', '', $m));
                    if ($num != '') $resultados[$num] = true;
                }
            }
        }
    }

    return $resultados;
}

$mensaje = "";
$resultado = array();
$csv_generado = "";
$excel_file = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['zipfile'])) {
    echo "<script>document.getElementById('progress').style.display='block';</script>";
    flush(); ob_flush();

    $zip = new ZipArchive;
    $tmp = $_FILES['zipfile']['tmp_name'];
    $carpeta = "ResultadoVolte_" . time();
    mkdir($carpeta);

    if ($zip->open($tmp) === TRUE) {
        $zip->extractTo($carpeta);
        $zip->close();
        echo "<script>document.getElementById('status').innerHTML='✅ ZIP extraído...';</script>";

        $msisdn_csv = "$carpeta/MSISDN.csv";
        if (!file_exists($msisdn_csv)) {
            $mensaje = "<div class='alert alert-danger'>❌ No se encontró MSISDN.csv</div>";
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

            // Totales
            $totales = array(
                "HLR" => ["APROVISIONADO" => 0, "NO APROVISIONADO" => 0],
                "HSS" => ["APROVISIONADO" => 0, "NO APROVISIONADO" => 0],
                "IMS" => ["APROVISIONADO" => 0, "NO APROVISIONADO" => 0],
                "ATS" => ["APROVISIONADO" => 0, "NO APROVISIONADO" => 0],
                "ENS" => ["APROVISIONADO" => 0, "NO APROVISIONADO" => 0]
            );
            foreach ($resultado as $fila) {
                $totales["HLR"][$fila[1]]++;
                $totales["HSS"][$fila[4]]++;
                $totales["IMS"][$fila[6]]++;
                $totales["ATS"][$fila[7]]++;
                $totales["ENS"][$fila[8]]++;
            }

            // CSV
            $csv_generado = "$carpeta/ResultadoVolte_" . time() . ".csv";
            $h = fopen($csv_generado, "w");
            fputcsv($h, array("MSISDN", "HLR", "IMSI", "CSP", "HSS", "PERFIL", "IMS", "ATS", "ENS"));
            foreach ($resultado as $fila) fputcsv($h, $fila);
            fputcsv($h, array());
            fputcsv($h, array("TOTAL POR SISTEMA", "APROVISIONADO", "NO APROVISIONADO"));
            foreach ($totales as $sistema => $cont) {
                fputcsv($h, array($sistema, $cont["APROVISIONADO"], $cont["NO APROVISIONADO"]));
            }
            fclose($h);
        }
    } else {
        $mensaje = "<div class='alert alert-danger'>❌ No se pudo abrir el ZIP</div>";
    }

    echo "<script>document.getElementById('progress').style.display='none';</script>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>📡 Analizador VoLTE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <style>
    body { background: #121212; color: #eee; font-family: 'Segoe UI', sans-serif; }
    .container { margin-top: 40px; }
    #progress-container { height: 25px; background: #333; border-radius: 10px; overflow: hidden; margin-bottom: 20px; }
    #bar { height: 100%; width: 100%; background-color: #4caf50; animation: fill 5s ease forwards; }
    @keyframes fill { from { width: 0%; } to { width: 100%; } }
  </style>
</head>
<body>
<div class="container text-center">
  <h1 class="mb-4 text-primary">📁 Analizador VoLTE Avanzado CENAM (Subir en .Zip el archivo MSISDN.csv y las consultas en .xls</h1>

  <form method="POST" enctype="multipart/form-data" class="mb-4">
    <input type="file" name="zipfile" class="form-control mb-2" accept=".zip" required>
    <button type="submit" class="btn btn-primary btn-lg">📦 Subir y Analizar ZIP</button>
  </form>

  <div id="progress" style="display:none;">
    <div id="progress-container"><div id="bar"></div></div>
    <p id="status">⏳ Procesando ZIP...</p>
  </div>

  <?php echo $mensaje; ?>

  <?php if (!empty($resultado)): ?>
    <div class="row justify-content-center mb-4">
      <?php foreach ($totales as $sistema => $conteos): ?>
        <div class="col-md-2 col-6 mb-2">
          <div class="card text-center bg-dark text-white border-secondary shadow-sm">
            <div class="card-header fw-bold"><?php echo $sistema; ?></div>
            <div class="card-body p-2">
              <small class="text-success">✅ Aprov: <strong><?php echo $conteos["APROVISIONADO"]; ?></strong></small><br>
              <small class="text-danger">❌ No Aprov: <strong><?php echo $conteos["NO APROVISIONADO"]; ?></strong></small>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

 <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
  <a class="btn btn-success btn-lg shadow-sm" href="<?php echo $csv_generado; ?>" download>
    📥 Descargar CSV
  </a>
  <a class="btn btn-warning btn-lg shadow-sm" href="excel.php">
    📊 Descargar Excel (.xlsx)
  </a>
  <a class="btn btn-danger btn-lg shadow-sm" href="pdf_completo.php" target="_blank">
    🖨️ Descargar PDF
  </a>
</div>


<div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
  <form method="POST" action="descargar_csv.php" target="_blank" id="formCSV">
    <input type="hidden" name="filters" id="csvFilters">
    <button type="submit" class="btn btn-success btn-lg shadow-sm">📥 Descargar CSV filtrado</button>
  </form>

  <form method="POST" action="descargar_excel.php" target="_blank" id="formExcel">
    <input type="hidden" name="filters" id="excelFilters">
    <button type="submit" class="btn btn-warning btn-lg shadow-sm">📊 Descargar Excel filtrado</button>
  </form>

  <!-- 👇🏽 Agrega este bloque si no lo tienes -->
  <form method="POST" action="descargar_pdf.php" target="_blank" id="formPDF">
    <input type="hidden" name="filters" id="pdfFilters">
    <button type="submit" class="btn btn-danger btn-lg shadow-sm">🖨️ Descargar PDF filtrado</button>
  </form>
</div>

    <table id="tabla" class="table table-dark table-striped table-bordered table-hover w-100 table-sm" data-url="data.php">
      <thead>
        <tr>
          <th>MSISDN</th><th>HLR</th><th>IMSI</th><th>CSP</th><th>HSS</th><th>PERFIL</th><th>IMS</th><th>ATS</th><th>ENS</th>
        </tr>
        <tr>
          <?php for ($i = 0; $i < 9; $i++): ?>
            <th><input type="text" class="form-control form-control-sm column-filter" placeholder="🔍 Buscar..." /></th>
          <?php endfor; ?>
        </tr>
      </thead>
    </table>
  <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
  $(document).ready(function(){
    let tabla = $('#tabla').DataTable({
      language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
      responsive: true,
      processing: true,
      serverSide: true,
      pageLength: 50,
      lengthMenu: [[50, 100, 150, 300], [50, 100, 150, 300]],
      ajax: {
        url: $('#tabla').data('url'),
        type: "POST"
      },
      orderCellsTop: true,
      fixedHeader: true,
      initComplete: function () {
  const api = this.api();

  // Clona el header para crear la fila de filtros (si aún no existe)
  if ($('#tabla thead tr').length < 2) {
    $('#tabla thead tr').clone(true).appendTo('#tabla thead');
  }

  api.columns().every(function (i) {
    let column = this;
    let input = $('input', $('#tabla thead tr:eq(1) th').eq(i));
    input.off().on('keyup change clear', function () {
      if (column.search() !== this.value) {
        column.search(this.value, true, false).draw();
      }
    });
  });
}

    });

    setTimeout(() => {
      const div = document.createElement("div");
      div.className = "alert alert-success position-fixed bottom-0 end-0 m-4 shadow-lg animate__animated animate__fadeInUp";
      div.style.zIndex = "1055";
      div.innerHTML = "✅ Análisis completado correctamente.";
      document.body.appendChild(div);
      setTimeout(() => div.remove(), 5000);
    }, 1000);
  });
  function obtenerFiltrosActivos() {
  const filtros = {};
  $('#tabla thead tr:eq(1) th').each(function(index) {
    const val = $(this).find('input').val();
    if (val) filtros[index] = val;
  });
  return JSON.stringify(filtros);
}

$('#formCSV').on('submit', function() {
  $('#csvFilters').val(obtenerFiltrosActivos());
});

$('#formExcel').on('submit', function() {
  $('#excelFilters').val(obtenerFiltrosActivos());
});
$('#formPDF').on('submit', function() {
  $('#pdfFilters').val(obtenerFiltrosActivos());
});

</script>
</body>
</html>
