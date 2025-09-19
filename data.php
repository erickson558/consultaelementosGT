<?php
session_start();
header('Content-Type: application/json');

// Buscar el último archivo CSV generado
$carpetas = glob("tmp_*", GLOB_ONLYDIR);
rsort($carpetas);
$ultima = isset($carpetas[0]) ? $carpetas[0] : '';
$archivo = "$ultima/Resultado_*.csv";
$files = glob($archivo);
rsort($files);
$csv = isset($files[0]) ? $files[0] : '';

if (!file_exists($csv)) {
    echo json_encode(["data" => [], "recordsTotal" => 0, "recordsFiltered" => 0]);
    exit;
}

// Parámetros de DataTables
$start  = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 50;
$draw   = isset($_POST['draw']) ? intval($_POST['draw']) : 0;
$globalSearch = isset($_POST['search']['value']) ? strtolower(trim($_POST['search']['value'])) : '';

// Columnas esperadas
$columns = array("MSISDN", "HLR", "IMSI", "CSP", "HSS", "PERFIL", "IMS", "ATS", "ENS");

// Filtros por columna
$searchColumns = [];
if (isset($_POST['columns'])) {
    foreach ($_POST['columns'] as $i => $col) {
        $searchColumns[$i] = strtolower(trim($col['search']['value']));
    }
}

$data = [];
$total = 0;
$filtered = 0;

if (($handle = fopen($csv, "r")) !== false) {
    $headers = fgetcsv($handle); // Saltar encabezados

    while (($row = fgetcsv($handle)) !== false) {
        if (count($row) < 9 || trim($row[0]) == '') continue;
        $total++;

        $match = true;

        // Filtro global
        if ($globalSearch !== '') {
            $match = false;
            foreach ($row as $cell) {
                if (stripos($cell, $globalSearch) !== false) {
                    $match = true;
                    break;
                }
            }
        }

        // Filtro por columna
        if ($match && !empty($searchColumns)) {
            foreach ($searchColumns as $i => $searchTerm) {
                if ($searchTerm !== '' && stripos($row[$i], $searchTerm) === false) {
                    $match = false;
                    break;
                }
            }
        }

        if ($match) {
            if ($filtered >= $start && count($data) < $length) {
                $data[] = $row;
            }
            $filtered++;
        }
    }

    fclose($handle);
}

// Respuesta JSON para DataTables
echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $total,
    "recordsFiltered" => $filtered,
    "data" => $data
]);
