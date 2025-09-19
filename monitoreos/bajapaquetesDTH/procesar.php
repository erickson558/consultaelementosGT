<?php
// Configuración inicial
ini_set('max_execution_time', 0);
date_default_timezone_set('America/Guatemala');

// Configuración de buffers para salida en tiempo real
header('Content-Type: text/html; charset=UTF-8');
ob_implicit_flush(true);
while (ob_get_level()) ob_end_flush();

// Función para timestamp detallado
function timestamp() {
    $microtime = explode(' ', microtime());
    $milis = str_pad((int)($microtime[0] * 1000), 3, '0', STR_PAD_LEFT);
    return "[" . date('Y-m-d H:i:s') . ".$milis]";
}

// Función para enviar mensajes al frontend
function enviarMensaje($mensaje) {
    $mensaje = str_replace(["\n", "\r", "'"], ['\\n', '', "\\'"], $mensaje);
    $mensaje = htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8');
    echo "<script>"
         . "if(parent && parent.actualizarConsola) parent.actualizarConsola('$mensaje');"
         . "</script>\n";
    ob_flush();
    flush();
}

// Función para actualizar progreso
function actualizarProgreso($progreso, $enviados, $total) {
    echo "<script>"
         . "if(parent && parent.actualizarBarraEnvio) parent.actualizarBarraEnvio($progreso);"
         . "if(parent && parent.actualizarContadores) parent.actualizarContadores($enviados, $total);"
         . "</script>\n";
    ob_flush();
    flush();
}

// NUEVA FUNCIÓN PARA GENERAR XML (ACTUALIZADA)
function generarXML($user, $pass, $smartcard, $serial, $paquete) {
    return '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:cai3="http://schemas.ericsson.com/cai3g1.2/" 
                  xmlns:dth="http://schemas.ericsson.com/ma/CA/DTHBatch/">
  <soapenv:Header>
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
      <wsse:UsernameToken>
        <wsse:Username>' . htmlspecialchars($user, ENT_QUOTES) . '</wsse:Username>
        <wsse:Password>' . htmlspecialchars($pass, ENT_QUOTES) . '</wsse:Password>
      </wsse:UsernameToken>
    </wsse:Security>
  </soapenv:Header>
  <soapenv:Body>
    <cai3:Set>
      <cai3:MOType>DTH@http://schemas.ericsson.com/ma/CA/DTHBatch/</cai3:MOType>
      <cai3:MOId>
        <dth:OrderID>' . $smartcard . '</dth:OrderID>
      </cai3:MOId>
      <cai3:MOAttributes>
        <dth:SetDTH OrderID="' . $smartcard . '">
          <dth:useCase>Disable_Package</dth:useCase>
          <dth:Devices>
            <dth:Device>
              <dth:STU_number>' . $serial . '</dth:STU_number>
              <dth:SC_uniqueAddress>' . $smartcard . '</dth:SC_uniqueAddress>
              <dth:packageId>' . $paquete . '</dth:packageId>
            </dth:Device>
          </dth:Devices>
        </dth:SetDTH>
      </cai3:MOAttributes>
    </cai3:Set>
  </soapenv:Body>
</soapenv:Envelope>';
}

// Función para enviar solicitud SOAP
function enviarSOAP($url, $xml) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: text/xml; charset=ISO-8859-1",
        "Content-Length: " . strlen($xml)
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $response = "❌ cURL Error: " . curl_error($ch);
    }
    curl_close($ch);
    return $response;
}

// Procesar solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario    = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';
    $delay      = isset($_POST['delay']) ? intval($_POST['delay']) : 1000;
    $endpoint   = (isset($_POST['endpoint_select']) && $_POST['endpoint_select'] !== 'otro')
                  ? trim($_POST['endpoint_select'])
                  : trim($_POST['endpoint']);

    // Crear directorio de logs si no existe
    if (!is_dir('logs')) mkdir('logs', 0755, true);
    $logFile = 'logs/log_' . date('Ymd_His') . '.txt';
    $log = fopen($logFile, 'w');

    // Inicializar contadores
    $total = 0; $enviados = 0; $omitidos = 0; $errores = 0;

    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
        $archivo = $_FILES['archivo']['tmp_name'];
        $datos = array_map('str_getcsv', file($archivo));
        $primera = array_map('trim', array_shift($datos));

        // Determinar índices de smartcard, serial y paquete
        $index_smartcard = 0;
        $index_serial = 1;
        $index_paquete = 2;

        // Limpieza inicial de estados
        @unlink("detener.txt");
        @unlink("pausar.txt");

        // Enviar encabezados iniciales
        enviarMensaje('🔄 Inicio: ' . date('Y-m-d H:i:s'));
        enviarMensaje('📍 Endpoint: ' . $endpoint);
        enviarMensaje('👤 Usuario: ' . $usuario);
        enviarMensaje('🕒 Delay: ' . $delay . ' ms');
        enviarMensaje('');

        fwrite($log, timestamp() . " Inicio del envío\n");
        fwrite($log, "Endpoint: $endpoint\nUsuario: $usuario\nDelay: {$delay} ms\n\n");

        $numRegistros = count($datos);
        actualizarProgreso(0, 0, $numRegistros);

        // Procesar cada registro
        for ($i = 0; $i < $numRegistros; $i++) {
            // Control de pausa/detención
            if (file_exists("detener.txt")) {
                enviarMensaje('🛑 Envío detenido por el usuario');
                fwrite($log, timestamp() . " Proceso detenido manualmente\n");
                break;
            }
            while (file_exists("pausar.txt")) {
                enviarMensaje('⏸ Envío pausado...');
                sleep(1);
            }

            $fila = $datos[$i];
            $total++;

            // Validar datos
            if (!isset($fila[$index_smartcard]) || !isset($fila[$index_serial]) || !isset($fila[$index_paquete])) {
                $omitidos++;
                continue;
            }

            $smartcard = trim($fila[$index_smartcard]);
            $serial = trim($fila[$index_serial]);
            $paquete = trim($fila[$index_paquete]);

            if ($smartcard === '' || $paquete === '') {
                enviarMensaje('⚠️ [' . $i . '] Saltado: Smartcard o Paquete vacío');
                fwrite($log, timestamp() . " [$i] Saltado: Smartcard o Paquete vacío\n");
                $omitidos++;
                continue;
            }

            // Generar XML y registrarlo en el log
            $xml = generarXML($usuario, $contrasena, $smartcard, $serial, $paquete);
            fwrite($log, timestamp() . " [$i] XML generado:\n" . $xml . "\n\n");

            // Enviar el mensaje al frontend
            enviarMensaje('➡️ [' . $i . '] Enviando: OrderID=' . $smartcard . ' | Serial=' . $serial . ' | Paquete=' . $paquete . ' - ' . timestamp());

            // Enviar SOAP
            $inicio = microtime(true);
            $respuesta = enviarSOAP($endpoint, $xml);
            $duracion = round(microtime(true) - $inicio, 2);

            // Registrar en log resultado
            if (strpos($respuesta, 'Fault') !== false || strpos($respuesta, 'error') !== false) {
                $errores++;
                enviarMensaje('❌ [' . $i . '] Error en respuesta - ' . timestamp());
                fwrite($log, timestamp() . " [$i] Error en respuesta\n$respuesta\n\n");
            } else {
                $enviados++;
                enviarMensaje('✅ [' . $i . '] Envío exitoso - Duración: ' . $duracion . 's - ' . timestamp());
                fwrite($log, timestamp() . " [$i] Envío exitoso - Duración: {$duracion}s\n\n");
            }

            fwrite($log, "----------------------------------------\n\n");

            // Actualizar progreso
            $progreso = round((($i+1) / $numRegistros) * 100);
            actualizarProgreso($progreso, $enviados, $numRegistros);

            // Pausa entre registros
            usleep($delay * 1000);
        }

        // Resumen final
        enviarMensaje("\n🎯 Resumen:");
        enviarMensaje("📄 Total registros: " . $total);
        enviarMensaje("📤 Enviados OK: " . $enviados);
        enviarMensaje("⚠️ Omitidos: " . $omitidos);
        enviarMensaje("❌ Errores: " . $errores);
        enviarMensaje("🕓 Fin: " . date('Y-m-d H:i:s'));
        enviarMensaje('📁 <a href="' . $logFile . '" target="_blank">Descargar log completo</a>');

        fwrite($log, timestamp() . " Resumen del proceso:\n");
        fwrite($log, "Total registros: $total\n");
        fwrite($log, "Enviados exitosos: $enviados\n");
        fwrite($log, "Registros omitidos: $omitidos\n");
        fwrite($log, "Errores detectados: $errores\n");
        fwrite($log, timestamp() . " Proceso finalizado\n");
        fclose($log);

        echo "<script>"
           . "if(parent && parent.finalizarBarra) parent.finalizarBarra();"
           . "if(parent && parent.actualizarLogFileName) parent.actualizarLogFileName('$logFile');"
           . "</script>";
    } else {
        enviarMensaje('❌ Error al subir el archivo');
        echo "<script>if(parent && parent.showToast) parent.showToast('Error al subir el archivo', 'error');</script>";
    }
}

// Asegurar que todo el contenido se envíe
ob_flush();
flush();
exit;
?>