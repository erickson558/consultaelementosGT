<?php
error_reporting(E_ERROR | E_PARSE); // Oculta warnings que pueden romper el PDF
ob_start();

require_once('tcpdf/tcpdf.php');
date_default_timezone_set('America/Guatemala');

// Función para cargar datos del CSV
function cargar_datos_csv($archivo) {
    $datos = array();
    if (($h = fopen($archivo, "r")) !== false) {
        while (($fila = fgetcsv($h, 10000, ",")) !== false) {
            $datos[] = $fila;
        }
        fclose($h);
    }
    return $datos;
}

// Totales por sistema
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

// Buscar archivo CSV más reciente
$dirs = glob("tmp_*", GLOB_ONLYDIR);
rsort($dirs);
$carpeta = isset($dirs[0]) ? $dirs[0] : '';
$archivos = glob("$carpeta/Resultado_*.csv");
rsort($archivos);
$csv = isset($archivos[0]) ? $archivos[0] : '';

if (!file_exists($csv)) {
    die("❌ No se encontró el archivo CSV.");
}

// Cargar datos
$filtrados = cargar_datos_csv($csv);
$totales = calcular_totales($filtrados);
$html = ''; // inicializar

// Crear PDF
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('VoLTE Analyzer');
$pdf->SetAuthor('Sistema VoLTE');
$pdf->SetTitle('Resultado Completo');
$pdf->SetMargins(10, 10, 10, true);
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 7); // Fuente más pequeña para más columnas

// Tabla de resultados
$html .= '<h2 style="text-align:center;">📶 Resultados del Análisis VoLTE</h2><br>';
$html .= '<table border="1" cellpadding="4" cellspacing="0"><thead><tr style="background-color:#d3d3d3;">';
foreach ($filtrados[0] as $col) {
    $html .= '<th style="white-space:normal; word-wrap:break-word; word-break: break-word;">' . htmlspecialchars($col) . '</th>';
}
$html .= '</tr></thead><tbody>';
foreach (array_slice($filtrados, 1, 100) as $fila){
    $html .= '<tr>';
    foreach ($fila as $celda) {
        $html .= '<td style="white-space:normal; word-wrap:break-word; word-break: break-word;">' . htmlspecialchars($celda) . '</td>';
    }
    $html .= '</tr>';
}
$html .= '</tbody></table><br><br>';

// Totales por sistema
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

// Agregar HTML al PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Limpiar cualquier salida previa y generar PDF
ob_end_clean();
// Descargar
$nombre = 'VoLTE_ResultadoCompleto_' . date("Ymd_His") . '.pdf';
header('Content-Disposition: attachment; filename="' . $nombre . '"'); // <-- esta línea nueva
$pdf->Output($nombre, 'D');

?>
