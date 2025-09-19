<?php
header("Content-Type: text/csv");
date_default_timezone_set("America/Guatemala");
$filename = "ResultadoVolte_Filtrado_" . date("Ymd_His") . ".csv";
header("Content-Disposition: attachment; filename=\"$filename\"");

// Detectar archivo CSV generado recientemente
$carpetas = glob("tmp_*", GLOB_ONLYDIR);
rsort($carpetas);
$ultima = isset($carpetas[0]) ? $carpetas[0] : '';
$archivos = glob($ultima . "/Resultado_*.csv");
rsort($archivos);
$archivo_csv = isset($archivos[0]) ? $archivos[0] : '';

if (!$archivo_csv || !file_exists($archivo_csv)) {
    echo "No se encontró el archivo CSV.";
    exit;
}

// Recibir filtros desde el POST
$filtros = isset($_POST['filters']) ? json_decode($_POST['filters'], true) : array();

// Abrir el archivo original
$f = fopen($archivo_csv, "r");
if (!$f) {
    echo "Error al abrir el archivo.";
    exit;
}

// Leer encabezado
$header = fgetcsv($f);
$salida = fopen("php://output", "w");
fputcsv($salida, $header);

// Procesar filas con filtros
while (($fila = fgetcsv($f)) !== false) {
    $incluir = true;

    // Validar fila contra filtros activos
    foreach ($filtros as $i => $filtro) {
        $valor = isset($fila[$i]) ? strtolower($fila[$i]) : '';
        $filtro = strtolower($filtro);
        if ($filtro !== '' && strpos($valor, $filtro) === false) {
            $incluir = false;
            break;
        }
    }

    if ($incluir) fputcsv($salida, $fila);
}
fclose($f);
fclose($salida);
exit;
