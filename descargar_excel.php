<?php
require_once('Classes/PHPExcel.php'); // PHPExcel compatible con PHP 5.4.31

function filtrar_linea($linea, $filtros) {
    foreach ($filtros as $col => $val) {
        $val = strtolower(trim($val));
        if ($val == '') continue;
        if (!isset($linea[$col])) return false;
        if (strpos(strtolower($linea[$col]), $val) === false) return false;
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filters'])) {
    $filtros = json_decode($_POST['filters'], true);

    $carpetas = glob("tmp_*", GLOB_ONLYDIR);
    rsort($carpetas);
    $ultima = isset($carpetas[0]) ? $carpetas[0] : '';
    $archivos = glob($ultima . "/Resultado_*.csv");
    rsort($archivos);
    $archivo_csv = isset($archivos[0]) ? $archivos[0] : '';

    if (!$archivo_csv || !file_exists($archivo_csv)) {
        die("No se encontró el archivo CSV.");
    }

    $objPHPExcel = new PHPExcel();
    $sheet = $objPHPExcel->setActiveSheetIndex(0);
    $row_num = 1;

    if (($f = fopen($archivo_csv, "r")) !== false) {
        $cabecera = fgetcsv($f);
        foreach ($cabecera as $i => $col) {
            $sheet->setCellValueByColumnAndRow($i, $row_num, $col);
        }
        $row_num++;

        while (($linea = fgetcsv($f)) !== false) {
            if (count($linea) < 5 || $linea[0] == 'TOTAL POR SISTEMA') break;
            if (filtrar_linea($linea, $filtros)) {
                foreach ($linea as $i => $val) {
                    $sheet->setCellValueByColumnAndRow($i, $row_num, $val);
                }
                $row_num++;
            }
        }
        fclose($f);
    }

    $nombre = "ResultadoVolte_filtrado_" . date("Ymd_His") . ".xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $nombre . '"');
    header('Cache-Control: max-age=0');

    $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $writer->save('php://output');
    exit;
}
?>
