<?php
// control.php - Control mejorado del estado del envío SOAP
header('Content-Type: text/plain');

// Configuración de errores
error_reporting(0);
ini_set('display_errors', 0);

// Función para limpiar estados
function limpiarEstados() {
    $archivos = ['pausar.txt', 'detener.txt'];
    foreach ($archivos as $archivo) {
        if (file_exists($archivo)) {
            if (!unlink($archivo)) {
                return false;
            }
        }
    }
    return true;
}

// Procesar acción
$accion = isset($_GET['accion']) ? trim($_GET['accion']) : '';

switch ($accion) {
    case 'pausar':
        if (file_put_contents('pausar.txt', '1') !== false) {
            echo '🟡 Envío pausado correctamente';
        } else {
            echo '❌ Error al pausar el envío';
        }
        break;
        
    case 'reanudar':
        if (file_exists('pausar.txt')) {
            if (unlink('pausar.txt')) {
                echo '🟢 Envío reanudado correctamente';
            } else {
                echo '❌ Error al reanudar el envío';
            }
        } else {
            echo '⚠️ El envío no estaba pausado';
        }
        break;
        
    case 'detener':
        if (file_put_contents('detener.txt', '1') !== false) {
            // Limpiar posible archivo de pausa también
            if (file_exists('pausar.txt')) @unlink('pausar.txt');
            echo '🔴 Envío detenido correctamente';
        } else {
            echo '❌ Error al detener el envío';
        }
        break;
        
    case 'estado':
        if (file_exists('detener.txt')) {
            echo 'detenido';
        } elseif (file_exists('pausar.txt')) {
            echo 'pausado';
        } else {
            echo 'activo';
        }
        break;
        
    default:
        echo '⚠️ Acción no válida';
}
?>