<?php
// analizadorARH.php - Exporta a CSV + Excel (.xlsx) con resumen por URL - PHP 5.4.31 + PHPExcel
ini_set('max_execution_time', 600);
date_default_timezone_set('America/Guatemala');

// Incluir PHPExcel
require_once("PHPExcel/Classes/PHPExcel.php");

function analizar_contenido($lineas) {
    $resultados = array();
    $timestamp = $url = $descripcion = '';
    foreach ($lineas as $line) {
        $line = trim($line);
        if (stripos($line, 'Timestamp') !== false) {
            $timestamp = trim(str_replace('Timestamp          :', '', $line));
        }
        if (stripos($line, 'Model Description') !== false && stripos($line, 'ARH failed to send notification') !== false) {
            $descripcion = trim($line);
        }
        if (stripos($line, 'Active Description') !== false && stripos($line, 'ARH failed to send notification to') !== false) {
            preg_match('/http[s]?:\/\/[^\s]+/', $line, $match);
            $url = isset($match[0]) ? $match[0] : '';
            $resultados[] = array(
                'timestamp' => $timestamp,
                'url' => $url,
                'descripcion' => $descripcion
            );
            $timestamp = $url = $descripcion = '';
        }
    }
    return $resultados;
}

function analizar_archivo($ruta, $nombre) {
    $lineas = array();
    if (substr($nombre, -3) === '.gz') {
        $gz = @gzopen($ruta, 'r');
        if ($gz) {
            while (!gzeof($gz)) {
                $lineas[] = gzgets($gz);
            }
            gzclose($gz);
        }
    } else {
        $fh = @fopen($ruta, 'r');
        if ($fh) {
            while (!feof($fh)) {
                $lineas[] = fgets($fh);
            }
            fclose($fh);
        }
    }
    return analizar_contenido($lineas);
}

$datos = array();
$nombre_csv = '';
$nombre_excel = '';
$resumen_urls = array();

if (isset($_FILES['archivo'])) {
    foreach ($_FILES['archivo']['tmp_name'] as $i => $tmpName) {
        $nombre = $_FILES['archivo']['name'][$i];
        if (preg_match('/\.gz$|\.log\d*$/i', $nombre)) {
            $datos = array_merge($datos, analizar_archivo($tmpName, $nombre));
        }
    }

    // 🕒 Timestamp
    $timestamp_actual = date('Ymd_Hi');
    $nombre_csv = "resultados_{$timestamp_actual}.csv";
    $nombre_excel = "resultados_{$timestamp_actual}.xlsx";

    // 📄 CSV
    $csv = fopen($nombre_csv, "w");
    fputcsv($csv, array("Timestamp", "URL", "Descripción"));
    foreach ($datos as $fila) {
        fputcsv($csv, array($fila['timestamp'], $fila['url'], $fila['descripcion']));
    }
    fclose($csv);

    // 📊 Resumen
    foreach ($datos as $fila) {
        $u = $fila['url'];
        $resumen_urls[$u] = isset($resumen_urls[$u]) ? $resumen_urls[$u] + 1 : 1;
    }

    // 🧾 Excel
    $excel = new PHPExcel();
    $excel->getProperties()->setCreator("Analizador ARH")->setTitle("Resultados");

    // Hoja 1: Detalle
    $excel->setActiveSheetIndex(0)->setTitle("Detalle");
    $sheet = $excel->getActiveSheet();
    $sheet->setCellValue("A1", "Timestamp")->setCellValue("B1", "URL")->setCellValue("C1", "Descripción");

    $row = 2;
    foreach ($datos as $d) {
        $sheet->setCellValue("A$row", $d['timestamp']);
        $sheet->setCellValue("B$row", $d['url']);
        $sheet->setCellValue("C$row", $d['descripcion']);
        $row++;
    }

    // Hoja 2: Resumen
    $resumen = $excel->createSheet();
    $resumen->setTitle("Resumen por URL");
    $resumen->setCellValue("A1", "URL")->setCellValue("B1", "Ocurrencias");
    $row = 2;
    foreach ($resumen_urls as $url => $count) {
        $resumen->setCellValue("A$row", $url);
        $resumen->setCellValue("B$row", $count);
        $row++;
    }

    // Guardar Excel
    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $writer->save($nombre_excel);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>🔍 Analizador ARH Logs</title>
<style>
body {
    background: #1e1e2f; color: #f0f0f0; font-family: Arial, sans-serif;
    text-align: center; margin: 0; padding: 0;
}
h1 { margin: 20px; font-size: 2em; }
form {
    margin: 20px auto; background: #2c2f4a; padding: 20px;
    border-radius: 12px; box-shadow: 0 0 20px #00000088; width: 90%; max-width: 600px;
}
input[type="file"] {
    padding: 10px; background: #1f1f2f; color: #fff; border: 1px solid #444;
    border-radius: 8px; margin-bottom: 10px;
}
button {
    padding: 10px 20px; background: #0066cc; color: white; border: none;
    border-radius: 8px; cursor: pointer;
}
button:hover { background: #0055aa; transform: scale(1.05); }
a.descarga {
    display: inline-block; margin: 10px; padding: 10px 15px;
    background: #28a745; color: #fff; border-radius: 8px; text-decoration: none;
}
a.descarga:hover { background: #218838; transform: scale(1.05); }
table {
    margin: 20px auto; width: 95%; border-collapse: collapse;
    background: #2b2b3c; border-radius: 10px; overflow: hidden;
    box-shadow: 0 0 15px #00000066;
}
th, td {
    padding: 10px; border: 1px solid #444; text-align: left;
}
th { background: #333; }
tr:hover { background: #3c3c5c; transition: 0.2s; }
ul { list-style: none; padding: 0; }
li { margin: 5px 0; }
</style>
</head>
<body>
<h1>📄 Analizador de Logs ARH</h1>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="archivo[]" multiple required><br>
    <button type="submit">🔎 Analizar archivos</button>
</form>

<?php if (!empty($datos)): ?>
    <h2>📊 Eventos encontrados: <?php echo count($datos); ?></h2>
    <a class="descarga" href="<?php echo $nombre_csv; ?>" download>📥 Descargar CSV</a>
    <a class="descarga" href="<?php echo $nombre_excel; ?>" download>📥 Descargar Excel</a>

    <h3>📈 Totales por URL:</h3>
    <ul>
    <?php foreach ($resumen_urls as $url => $count): ?>
        <li><strong><?php echo htmlspecialchars($url); ?></strong> → <?php echo $count; ?> ocurrencia<?php echo $count > 1 ? 's' : ''; ?></li>
    <?php endforeach; ?>
    </ul>

    <table>
        <tr><th>Timestamp</th><th>URL</th><th>Descripción</th></tr>
        <?php foreach ($datos as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
                <td><?php echo htmlspecialchars($row['url']); ?></td>
                <td><details><summary>Ver</summary><?php echo htmlspecialchars($row['descripcion']); ?></details></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif (isset($_FILES['archivo'])): ?>
    <h2 style="color: red;">❌ No se encontraron coincidencias.</h2>
<?php endif; ?>
</body>
</html>
