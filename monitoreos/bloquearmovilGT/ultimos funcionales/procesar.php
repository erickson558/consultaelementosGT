<?php
ini_set('max_execution_time', 0);
date_default_timezone_set('America/Guatemala');

header('Content-Type: text/html; charset=UTF-8');
ob_implicit_flush(true);
while (ob_get_level()) ob_end_flush();

function timestamp() {
    $microtime = explode(' ', microtime());
    $milis = str_pad((int)($microtime[0] * 1000), 3, '0', STR_PAD_LEFT);
    return "[" . date('Y-m-d H:i:s') . ".$milis]";
}

function enviarMensaje($mensaje) {
    $mensaje = str_replace(["\n", "\r", "'"], ['\\n', '', "\\'"], $mensaje);
    $mensaje = htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8');
    echo "<script>if(parent && parent.actualizarConsola) parent.actualizarConsola('$mensaje');</script>\n";
    ob_flush(); flush();
}

function actualizarProgreso($progreso, $enviados, $total) {
    echo "<script>
        if(parent && parent.actualizarBarraEnvio) parent.actualizarBarraEnvio($progreso);
        if(parent && parent.actualizarContadores) parent.actualizarContadores($enviados, $total);
    </script>\n";
    ob_flush(); flush();
}

function generarXML($user, $pass, $msisdn, $imsi) {
    return '<?xml version="1.0" encoding="ISO-8859-1"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
    xmlns:add="http://www.w3.org/2005/08/addressing"
    xmlns:amx="http://schemas.ericsson.com/ma/CA/AMXCAPreDualCoreXT/"
    xmlns:asy="http://schemas.ericsson.com/async/"
    xmlns:cai3="http://schemas.ericsson.com/cai3g1.2/">
  <soapenv:Header>
    <add:Security>
      <add:UsernameToken>
        <add:Username>' . htmlspecialchars($user, ENT_QUOTES) . '</add:Username>
        <add:Password>' . htmlspecialchars($pass, ENT_QUOTES) . '</add:Password>
      </add:UsernameToken>
    </add:Security>
    <cai3:Context>345929</cai3:Context>
  </soapenv:Header>
  <soapenv:Body>
    <cai3:Create>
      <cai3:MOType>PrepaidMobile@http://schemas.ericsson.com/ma/CA/AMXCAPreDualCoreXT/</cai3:MOType>
      <cai3:MOId>
        <amx:OrderId>' . $msisdn . '</amx:OrderId>
        <amx:msisdn>' . $msisdn . '</amx:msisdn>
        <amx:imsi>' . $imsi . '</amx:imsi>
      </cai3:MOId>
      <cai3:MOAttributes>
        <amx:CreatePrepaidMobile imsi="' . $imsi . '" msisdn="' . $msisdn . '">
          <amx:OperationID>ACTIVATION</amx:OperationID>
          <amx:msisdn>' . $msisdn . '</amx:msisdn>
          <amx:imsi>' . $imsi . '</amx:imsi>
          <amx:tplId>26</amx:tplId>
        </amx:CreatePrepaidMobile>
      </cai3:MOAttributes>
    </cai3:Create>
  </soapenv:Body>
</soapenv:Envelope>';
}

function enviarXML($url, $xml) {
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';
    $pais = isset($_POST['pais']) ? trim($_POST['pais']) : 'Guatemala';
    $delay = isset($_POST['delay']) ? intval($_POST['delay']) : 1000;
    // Validación: solo permitir 1000, 2000 o 3000 ms desde el combo
    $allowed_delays = array(1000, 2000, 3000);
    if (!in_array($delay, $allowed_delays, true)) { $delay = 1000; }

    $endpoint = isset($_POST['endpoint']) ? trim($_POST['endpoint']) : '';

    if (!is_dir('logs')) mkdir('logs', 0755, true);
    $logFile = 'logs/log_' . date('Ymd_His') . '.txt';
    $log = fopen($logFile, 'w');

    $total = 0; $enviados = 0; $omitidos = 0; $errores = 0;

    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
        $archivo = $_FILES['archivo']['tmp_name'];
        $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $numRegistros = count($lineas);
        actualizarProgreso(0, 0, $numRegistros);

        @unlink("detener.txt");
        @unlink("pausar.txt");

        enviarMensaje('🔄 Inicio: ' . date('Y-m-d H:i:s'));
        enviarMensaje('⏱ Delay efectivo: ' . $delay . ' ms');
        enviarMensaje('🌐 Endpoint: ' . htmlspecialchars($endpoint, ENT_QUOTES, 'UTF-8'));

        for ($i = 0; $i < $numRegistros; $i++) {
            if (file_exists("detener.txt")) break;
            while (file_exists("pausar.txt")) {
                enviarMensaje('⏸ Envío pausado...');
                sleep(1);
            }

            $total++;
            $partes = explode(',', $lineas[$i]);
            if (count($partes) < 2) {
                enviarMensaje("⚠️ [$i] Saltado: formato incorrecto");
                $omitidos++;
                continue;
            }

            $msisdn = trim($partes[0]);
            $imsi = trim($partes[1]);

            if ($msisdn === '' || $imsi === '') {
                enviarMensaje("⚠️ [$i] Saltado: msisdn o imsi vacío");
                $omitidos++;
                continue;
            }

            $xml = generarXML($usuario, $contrasena, $msisdn, $imsi);
            $respuesta = enviarXML($endpoint, $xml);

            if (strpos($respuesta, 'Fault') !== false || strpos($respuesta, 'error') !== false) {
                $errores++;
                enviarMensaje("❌ [$i] Error en respuesta");
            } else {
                $enviados++;
                enviarMensaje("✅ [$i] Enviado: msisdn=$msisdn / imsi=$imsi");
            }

            $progreso = round((($i + 1) / $numRegistros) * 100);
            actualizarProgreso($progreso, $enviados, $numRegistros);
            usleep($delay * 1000);
        }

        enviarMensaje("📄 Total: $total | 📤 Enviados: $enviados | ⚠️ Omitidos: $omitidos | ❌ Errores: $errores");
        enviarMensaje("🕓 Fin: " . date('Y-m-d H:i:s'));
        echo "<script>
            if(parent && parent.finalizarBarra) parent.finalizarBarra();
            if(parent && parent.actualizarLogFileName) parent.actualizarLogFileName('$logFile');
        </script>";
        fclose($log);
    } else {
        enviarMensaje('❌ Error al subir archivo');
    }
    exit;
}
