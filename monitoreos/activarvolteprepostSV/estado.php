<?php
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

switch ($accion) {
    case 'pausar':
        file_put_contents("pausar.txt", "1");
        break;
    case 'reanudar':
        if (file_exists("pausar.txt")) unlink("pausar.txt");
        break;
    case 'detener':
        file_put_contents("detener.txt", "1");
        break;
}
?>
