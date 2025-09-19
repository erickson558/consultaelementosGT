<?php
require_once("PHPExcel/Classes/PHPExcel.php");

// Obtener último CSV generado
$carpetas = glob("tmp_*", GLOB_ONLYDIR);
rsort($carpetas);
$ultima = isset($carpetas[0]) ? $carpetas[0] : '';
$csv = glob("$ultima/Resultado_*.csv");
rsort($csv);
$csvfile = isset($csv[0]) ? $csv[0] : '';

if (!file_exists($csvfile)) {
    die("Archivo CSV no encontrado");
}

$objPHPExcel = new PHPExcel();
$sheet = $objPHPExcel->setActiveSheetIndex(0);
$sheet->setTitle("Resultado");

$rowIndex = 1;
if (($handle = fopen($csvfile, "r")) !== false) {
    while (($data = fgetcsv($handle)) !== false) {
        $sheet->fromArray($data, null, "A$rowIndex");
        $rowIndex++;
    }
    fclose($handle);
}

$filename = $ultima . "_reporte.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$writer->save('php://output');
exit;
