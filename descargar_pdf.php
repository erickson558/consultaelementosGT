<?php
require_once('tcpdf/tcpdf.php');
date_default_timezone_set('America/Guatemala');

function cargar_datos_csv($archivo) {
    $datos = array();
    if (($h = fopen($archivo, "r")) !== false) {
        while (($fila = fgetcsv($h, 1000, ",")) !== false) {
            $datos[] = $fila;
        }
        fclose($h);
    }
    return $datos;
}

function aplicar_filtros($datos, $filtros) {
    $resultado = array();
    $headers = array_shift($datos);
    foreach ($datos as $fila) {
        if (count($fila) < count($headers)) continue;
        $coincide = true;
        foreach ($filtros as $col => $valor) {
            $valor = strtolower(trim($valor));
            if ($valor != '' && stripos($fila[$col], $valor) === false) {
                $coincide = false;
                break;
            }
        }
        if ($coincide) $resultado[] = $fila;
    }
    return array_merge(array($headers), $resultado);
}

function calcular_totales($datos) {
    $totales = array(
        "HLR" => array("APROVISIONADO" => 0, "NO APROVISIONADO" => 0),
        "HSS" => array("APROVISIONADO" => 0, "NO APROVISIONADO" => 0),
        "IMS" => array("APROVISIONADO" => 0, "NO APROVISIONADO" => 0),
        "ATS" => array("APROVISIONADO" => 0, "NO APROVISIONADO" => 0),
        "ENS" => array("APROVISIONADO" => 0, "NO APROVISIONADO" => 0)
    );
    foreach ($datos as $i => $fila) {
        if ($i === 0 || count($fila) < 9) continue;
        $totales["HLR"][$fila[1]]++;
        $totales["HSS"][$fila[4]]++;
        $totales["IMS"][$fila[6]]++;
        $totales["ATS"][$fila[7]]++;
        $totales["ENS"][$fila[8]]++;
    }
    return $totales;
}

// Cargar archivo más reciente
$dirs = glob("tmp_*", GLOB_ONLYDIR);
rsort($dirs);
$carpeta = isset($dirs[0]) ? $dirs[0] : '';
$archivos = glob("$carpeta/Resultado_*.csv");
rsort($archivos);
$csv = isset($archivos[0]) ? $archivos[0] : '';

if (!file_exists($csv)) {
    die("No se encontró el archivo CSV.");
}

$datos = cargar_datos_csv($csv);

// Decodificar filtros
$filtros = array();
if (isset($_POST['filters'])) {
    $filtros = json_decode($_POST['filters'], true);
}

$filtrados = aplicar_filtros($datos, $filtros);
$totales = calcular_totales($filtrados);

// Crear PDF
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('VoLTE Analyzer');
$pdf->SetTitle('Resultados Filtrados');
$pdf->SetMargins(10, 10, 10, true);
$pdf->AddPage();

// Tabla de resultados
$html = '<h2 style="text-align:center;">📶 Resultados de Análisis VoLTE</h2><br>';
$html .= '<table border="1" cellpadding="4"><thead><tr style="background-color:#d3d3d3;">';
foreach ($filtrados[0] as $col) {
    $html .= '<th>' . htmlspecialchars($col) . '</th>';
}
$html .= '</tr></thead><tbody>';
foreach (array_slice($filtrados, 1) as $fila) {
    $html .= '<tr>';
    foreach ($fila as $celda) {
        $html .= '<td>' . htmlspecialchars($celda) . '</td>';
    }
    $html .= '</tr>';
}
$html .= '</tbody></table><br><br>';

// Totales
$html .= '<h4>📊 Totales por Sistema:</h4>';
$html .= '<table border="1" cellpadding="4"><thead><tr style="background-color:#f0f0f0;"><th>Sistema</th><th>APROVISIONADO</th><th>NO APROVISIONADO</th></tr></thead><tbody>';
foreach ($totales as $sistema => $conteo) {
    $html .= '<tr>';
    $html .= '<td>' . $sistema . '</td>';
    $html .= '<td>' . $conteo["APROVISIONADO"] . '</td>';
    $html .= '<td>' . $conteo["NO APROVISIONADO"] . '</td>';
    $html .= '</tr>';
}
$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Descargar con timestamp
$nombre = 'VoLTE_Resultados_' . date("Ymd_His") . '.pdf';
$pdf->Output($nombre, 'D');
?>
