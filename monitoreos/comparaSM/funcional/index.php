<?php
// Función para remover códigos ANSI - Compatible con PHP 5.4
function removeAnsi($str) {
    return preg_replace('/\e\[[0-9;]*m/', '', $str);
}

// Lista de componentes QUE SÍ DEBEN INCLUIRSE
$componentesAIncluir = array(
    'DeleteApnHSS', 'DTHBatchServiceModel', 'DSLAM_GUATEMALA', 'DTHBatchSync', 'DTHSync',
    'DTHServiceModel', 'HuaweiDecisionServiceModel', 'MobilePrepaidGuatemalaBATCH', 
    'SM-MobilePrepaidCRMXT', 'SM-MobilePrepaidBATCHXT', 'SM-MobileYokofonXT', 'DSLAM_ALCATEL',
    'DSLAM_NOKIA', 'SM-AMXClaroMobileProvisioning', 'AMXClaroHFCTVGT', 'AMXClaroHFCTVGTSync',
    'SM-IPTVHFCGuatemala', 'SM-AMXClaroGuatemalaIPTV', 'SM-AMXClaroFTTHU2000VoiceGuatemala',
    'SM-SWITCHPOTS_GUATEMALA', 'HuaweiExecutionServiceModel', 'SM-IMS_GUATEMALA', 
    'U2000_GUATEMALA', 'SM-HFCVoiceGuatemalaServiceModel', 'HFCInternetServiceModelAsync',
    'HFCInternetServiceModel', 'AMXCLAROADSLPOTS_GUATEMALA', 'AMXClaroFTTHU2000InternetGuatemala',
    'ClaroXTPrepaidBatchMasterNoSV', 'MobilePrepaidGuatemalaBATCH_HW_vUDB', 
    'SM-AMXClaroDecisionMobileGT_Layered', 'SM-MobilePrepaidGuatemalaCRM', 
    'SM-AMXClaroMobileProvision_HW_vUDB', 'SM-AMXClaroMobileGT', 'SM-MobilePrepaidGTCRM_HW_vUDB',
    'ClaroXTPrepaidCRMMasterNoSV', 'SM-AMXClaroMobileGT_HW_vUDB', 
    'SM-AMXClaroDecisionMobileGT_HW_vUDB', 'ClaroXTPostpaidMasterNoSV', 
    'JDV-HuaweiENS-Resource-Provisioning', 'JDV-GEMOTA-Service-Provisioning', 
    'JDV-U2000-Service-Provisioning', 'JDV-SiemensV15-Service-Provisioning', 
    'JDV-SiemensV15-Resource-Provisioning', 'JDV-PrimaryNodesNotifier-Service-Provisioning',
    'JDV-PrimaryNodesNotifier-Resource-Provisioning', 'JDV-PCRF-Service-Provisioning',
    'JDV-PCRF-Resource-Provisioning', 'JDV-HuaweiU2000-Service-Provisioning', 
    'JDV-HuaweiHSS-Service-Provisioning', 'JDV-HuaweiHSS-Resource-Provisioning',
    'JDV-HuaweiENS-Service-Provisioning', 'JDV-HuaweiDRA-Service-Provisioning',
    'JDV-HuaweiDRA-Resource-Provisioning', 'JDV-HuaweiAGCF-Service-Provisioning',
    'JDV-HSSHUAWEIXT-Service-Provisioning', 'JDV-HSSHUAWEIXT-Resource-Provisioning',
    'JDV-HFCVoiceGuatemala-Provisioning', 'JDV-EricssonAPZ212-Service-Provisioning',
    'JDV-EricssonAPZ212-Resource-Provisioning', 'JDV-DAC-Service-Provisioning',
    'JDV-DAC-Resource-Provisioning', 'JDV-CASNagra-Service-Provisioning',
    'JDV-CASNagra-Resource-Provisioning', 'JDV-CASNagraBatch-Service-Provisioning',
    'JDV-CASNagraBatch-Resource-Provisioning', 'JDV-BBRIM-Service-Provisioning',
    'JDV-BBRIM-Resource-Provisioning', 'JDV-AxessInternet-Resource-Provisioning',
    'JDV-AMCO-Service-Provisioning', 'JDV-AlcatelUT100-Service-Provisioning',
    'JCA-U2000', 'JCA-SiemensV15', 'JCA-PrimaryNodesNotifier', 'JCA-HuaweiHSS',
    'JCA-HuaweiENS', 'JCA-HSSHUAWEIXT', 'JCA-GEMOTA', 'JCA-BBRIM', 'JCA-AxessInternet',
    'JCA-AlcatelUT100', 'JCA-CASNagraLink', 'JCA-CASNagraBatchLink', 'JCA-CAI3G12SOM',
    'JDV-GEMOTA-Resource-Provisioning', 'JDV-ParameterStructurator-Provisioning',
    'JDV-HuaweiATS-Service-Provisioning', 'JDV-SMExecutor-Provisioning',
    'JDV-AxessInternet-Service-Provisioning', 'JCA-SMExecution', 'JCA-AMCO',
    'JCA-DSLAM-Alcatel', 'JCA-DSLAM-ISAM-NOKIA', 'JDV-DSLAM-Alcatel-Service-Provisioning',
    'JDV-DSLAM-ISAM-NOKIA-Service-Provisioning', 'JDV-DSConfigurationSupportGuatemala-Provisioning',
    'JDV-DSLAM-ISAM-NOKIA-Resource-Provisioning', 'JDV-DSLAM-Alcatel-Resource-Provisioning',
    'JDV-U2000-Resource-Provisioning', 'JCA-PCRF', 'UDC-Application', 'JCA-HuaweiATS',
    'JCA-HuaweiDRA', 'JDV-Cai3GSom-Provisioning', 'JCA-MotorolaDAC',
    'JDV-APIGenericHub-Service-Provisioning', 'JDV-APIGenericHub-Resource-Provisioning',
    'JCA-APIGenericHub', 'JDV-APIGenericHubMO-Service-Provisioning',
    'JDV-APIGenericHubMO-Resource-Provisioning', 'JCA-APIGenericHubMO',
    'JDV-HuaweiATS-Resource-Provisioning', 'JDV-HuaweiU2000-Resource-Provisioning',
    'JCA-HuaweiU2000', 'JDV-AMCO-Resource-Provisioning',
    'JDV-SiemensV15gtl-Service-Provisioning', 'JDV-SiemensV15gtl-Resource-Provisioning',
    'JDV-AlcatelUT100-Resource-Provisioning', 'JCA-FNR', 'JCA-SiemensV15gtl',
    'JCA-EricssonAPZ212', 'JCA-HuaweiAGCFLink', 'JDV-HuaweiAGCF-Resource-Provisioning',
    'JDV-HuaweiHLR-Service-Provisioning', 'JDV-HuaweiHLR-Resource-Provisioning',
    'JCA-HuaweiHLR', 'JDV-FNR-Service-Provisioning', 'JDV-FNR-Resource-Provisioning'
);

// Función para normalizar nombres - Compatible con PHP 5.4
function normalizarNombre($nombre) {
    $nombre = trim($nombre);
    // Eliminar prefijos problemáticos
    if (strpos($nombre, '-> ') === 0) {
        $nombre = substr($nombre, 3);
    }
    if (strpos($nombre, 'SM-') === 0) {
        $nombre = substr($nombre, 3);
    }
    return trim($nombre);
}

// Función para generar nombres alternativos - Compatible con PHP 5.4
function generarAlternativas($nombre) {
    $base = normalizarNombre($nombre);
    return array(
        $base,
        "SM-$base",
        "-> $base",
        "-> SM-$base"
    );
}

// Función para comparar versiones y determinar cuál es mayor - Compatible con PHP 5.4
function compararVersiones($v1, $v2) {
    // Limpiar versiones
    $v1 = preg_replace('/[^0-9.]/', '', $v1);
    $v2 = preg_replace('/[^0-9.]/', '', $v2);
    
    $parts1 = explode('.', $v1);
    $parts2 = explode('.', $v2);
    
    $maxLength = max(count($parts1), count($parts2));
    
    for ($i = 0; $i < $maxLength; $i++) {
        $part1 = isset($parts1[$i]) ? $parts1[$i] : 0;
        $part2 = isset($parts2[$i]) ? $parts2[$i] : 0;
        
        if ($part1 > $part2) return 1; // v1 > v2
        if ($part1 < $part2) return -1; // v1 < v2
    }
    
    return 0; // iguales
}

// Función para procesar los archivos - Compatible con PHP 5.4
function procesarArchivo($contenido) {
    $modulos = array();
    $lineas = explode("\n", $contenido);
    $hostLength = 15;   // Longitud fija de la columna Host
    $moduleLength = 47; // Longitud fija de la columna Module
    $versionLength = 30; // Longitud ampliada para versiones

    foreach ($lineas as $linea) {
        $linea = removeAnsi($linea);
        $linea = rtrim($linea); // Eliminar espacios en blanco al final
        
        // Saltar líneas no relevantes
        if (empty($linea) || 
            strpos($linea, 'Host') === 0 || 
            strpos($linea, '====') === 0 ||
            strpos($linea, 'Status') !== false) {
            continue;
        }

        // Determinar si es módulo principal o submódulo
        $esSubmodulo = (strpos($linea, '->') === 0);
        $offset = $esSubmodulo ? $hostLength + 2 : $hostLength;
        $length = $esSubmodulo ? $moduleLength - 2 : $moduleLength;
        
        if (strlen($linea) >= ($hostLength + $moduleLength)) {
            $modulePart = trim(substr($linea, $offset, $length));
            $versionPart = trim(substr($linea, $hostLength + $moduleLength, $versionLength));
            
            if ($modulePart !== '' && $versionPart !== '') {
                $modulos[$modulePart] = $versionPart;
            }
        }
    }
    return $modulos;
}

// Función para comparar los componentes - Compatible con PHP 5.4
function compararComponentes($modulosProd, $modulosStby, $componentesAIncluir) {
    $resultado = array(
        'diferentes' => array(),
        'faltantes_prod' => array(),
        'faltantes_stby' => array()
    );

    // Componentes normalizados a buscar
    $componentesBusqueda = array();
    foreach ($componentesAIncluir as $componente) {
        $normalizado = normalizarNombre($componente);
        $componentesBusqueda[$normalizado] = $componente;
    }

    // Buscar componentes de PROD en STBY
    foreach ($modulosProd as $nombreProd => $versionProd) {
        $normalizado = normalizarNombre($nombreProd);
        
        // Verificar si es un componente que nos interesa
        if (!isset($componentesBusqueda[$normalizado])) {
            continue;
        }

        $encontrado = false;
        $alternativas = generarAlternativas($nombreProd);
        
        foreach ($alternativas as $alt) {
            if (isset($modulosStby[$alt])) {
                $versionStby = $modulosStby[$alt];
                
                // Comparar versiones
                $comparacion = compararVersiones($versionProd, $versionStby);
                $masAlta = '';
                
                if ($comparacion > 0) {
                    $masAlta = 'PROD';
                } elseif ($comparacion < 0) {
                    $masAlta = 'STBY';
                } else {
                    $masAlta = 'IGUAL';
                }
                
                if ($comparacion !== 0) {
                    $resultado['diferentes'][$componentesBusqueda[$normalizado]] = array(
                        'PROD' => $versionProd,
                        'STBY' => $versionStby,
                        'nombre_stby' => $alt,
                        'mas_alta' => $masAlta
                    );
                }
                $encontrado = true;
                break;
            }
        }
        
        if (!$encontrado) {
            $resultado['faltantes_stby'][$componentesBusqueda[$normalizado]] = $versionProd;
        }
    }

    // Buscar componentes faltantes en PROD
    foreach ($componentesAIncluir as $componente) {
        $normalizado = normalizarNombre($componente);
        $encontrado = false;
        
        foreach ($modulosProd as $nombreProd => $versionProd) {
            if (normalizarNombre($nombreProd) === $normalizado) {
                $encontrado = true;
                break;
            }
        }
        
        if (!$encontrado) {
            $resultado['faltantes_prod'][] = $componente;
        }
    }

    return $resultado;
}

// Procesamiento principal
$resultado = array();
$debugInfo = array('prod' => array(), 'stby' => array());

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_prod']) && isset($_FILES['archivo_stby'])) {
    $contenidoProd = file_get_contents($_FILES['archivo_prod']['tmp_name']);
    $contenidoStby = file_get_contents($_FILES['archivo_stby']['tmp_name']);
    
    $modulosProd = procesarArchivo($contenidoProd);
    $modulosStby = procesarArchivo($contenidoStby);
    
    // Guardar información de depuración
    $debugInfo['prod'] = array(
        'total_modulos' => count($modulosProd),
        'modulos_encontrados' => array_keys($modulosProd)
    );
    
    $debugInfo['stby'] = array(
        'total_modulos' => count($modulosStby),
        'modulos_encontrados' => array_keys($modulosStby)
    );
    
    $resultado = compararComponentes($modulosProd, $modulosStby, $componentesAIncluir);
}

// Configurar codificación UTF-8
header('Content-Type: text/html; charset=UTF-8');
?>
<?php
// ... (todo el código PHP anterior permanece igual) ...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparador PROD vs STBY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --primary-dark: #2980b9;
            --secondary-color: #2c3e50;
            --success-color: #27ae60;
            --success-dark: #219653;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #3498db;
            --dark-bg: #121826;
            --darker-bg: #0d1119;
            --card-bg: #1a2236;
            --card-border: #25304a;
            --text-primary: #e0e7ff;
            --text-secondary: #a0aec0;
            --text-muted: #718096;
            --table-header: #1e2a47;
            --table-row: #1a2236;
            --table-row-hover: #212d4d;
            --input-bg: #1e2a47;
            --input-border: #2d3b57;
        }
        
        body {
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding-bottom: 50px;
            color: var(--text-primary);
        }
        
        .header {
            background: linear-gradient(to right, var(--secondary-color), var(--primary-dark));
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            border-bottom: 1px solid rgba(52, 152, 219, 0.3);
        }
        
        .card {
            border-radius: 10px;
            border: 1px solid var(--card-border);
            background-color: var(--card-bg);
            margin-bottom: 25px;
            transition: all 0.3s ease;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0,0,0,0.25);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.35);
            border-color: rgba(52, 152, 219, 0.5);
        }
        
        .card-header {
            background: linear-gradient(to right, var(--primary-dark), #1e3a5f);
            color: white;
            font-weight: 600;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
            border-bottom: 1px solid var(--card-border);
        }
        
        .table th {
            background-color: var(--table-header);
            color: var(--text-primary);
            font-weight: 600;
            border-bottom: 1px solid var(--card-border);
        }
        
        .table td {
            background-color: var(--table-row);
            color: var(--text-primary);
            border-top: 1px solid var(--card-border);
        }
        
        .table-hover tbody tr:hover {
            background-color: var(--table-row-hover);
        }
        
        .badge-prod {
            background-color: var(--primary-dark);
            color: white;
        }
        
        .badge-stby {
            background-color: var(--success-dark);
            color: white;
        }
        
        .badge-equal {
            background-color: #4a5568;
            color: white;
        }
        
        .fade-in {
            opacity: 0;
            animation: fadeIn 0.8s ease forwards;
        }
        
        .slide-in {
            opacity: 0;
            transform: translateY(20px);
            animation: slideIn 0.6s ease forwards;
        }
        
        .version-higher {
            font-weight: bold;
            position: relative;
            color: white;
        }
        
        .version-higher::after {
            content: "↑";
            margin-left: 5px;
            color: var(--success-color);
        }
        
        .summary-card {
            background: linear-gradient(to right, var(--card-bg), var(--darker-bg));
            border-left: 4px solid var(--primary-color);
            color: var(--text-primary);
        }
        
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 5px;
        }
        
        .status-prod {
            background-color: var(--primary-color);
        }
        
        .status-stby {
            background-color: var(--success-color);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(52, 152, 219, 0); }
            100% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0); }
        }
        
        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 100;
            box-shadow: 0 6px 15px rgba(0,0,0,0.4);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            background: linear-gradient(to right, var(--primary-dark), var(--secondary-color));
            color: white;
            border: 1px solid rgba(52, 152, 219, 0.3);
        }
        
        .floating-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.5);
        }
        
        .highlight-row {
            animation: highlight 1.5s ease;
        }
        
        @keyframes highlight {
            0% { background-color: rgba(243, 156, 18, 0.3); }
            100% { background-color: transparent; }
        }
        
        .filter-container {
            margin-bottom: 15px;
            background: var(--darker-bg);
            padding: 15px;
            border-radius: 8px;
            border: 1px solid var(--card-border);
        }
        
        .filter-btn {
            margin: 0 5px;
            transition: all 0.3s ease;
            background-color: var(--table-header);
            color: var(--text-primary);
            border: 1px solid var(--card-border);
        }
        
        .filter-btn:hover {
            background-color: var(--primary-dark);
        }
        
        .filter-btn.active {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(52, 152, 219, 0.3);
            background-color: var(--primary-dark);
            border-color: var(--primary-color);
        }
        
        .badge-counter {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .btn-filter-container {
            position: relative;
            display: inline-block;
            margin: 0 5px;
        }
        
        .alert-info {
            background-color: rgba(41, 128, 185, 0.15);
            border: 1px solid rgba(52, 152, 219, 0.3);
            color: var(--text-primary);
        }
        
        .alert-success {
            background-color: rgba(33, 150, 83, 0.15);
            border: 1px solid rgba(39, 174, 96, 0.3);
            color: var(--text-primary);
        }
        
        .alert-warning {
            background-color: rgba(243, 156, 18, 0.15);
            border: 1px solid rgba(245, 176, 65, 0.3);
            color: var(--text-primary);
        }
        
        .alert-danger {
            background-color: rgba(231, 76, 60, 0.15);
            border: 1px solid rgba(235, 107, 94, 0.3);
            color: var(--text-primary);
        }
        
        .form-control {
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            color: var(--text-primary);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .panel {
            background-color: var(--card-bg);
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            border: 1px solid var(--card-border);
        }
        
        .panel-body {
            padding: 15px;
        }
        
        .panel-primary {
            border-top: 3px solid var(--primary-color);
        }
        
        .panel-info {
            border-top: 3px solid var(--primary-color);
        }
        
        .panel-warning {
            border-top: 3px solid var(--warning-color);
        }
        
        .panel-danger {
            border-top: 3px solid var(--danger-color);
        }
        
        .btn-primary {
            background: linear-gradient(to right, var(--primary-dark), var(--secondary-color));
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .btn-primary:hover {
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
        }
        
        .input-group-addon {
            background-color: var(--table-header);
            border: 1px solid var(--input-border);
            color: var(--text-secondary);
        }
        
        .label {
            padding: 5px 10px;
            border-radius: 4px;
        }
        
        .label-danger {
            background-color: var(--danger-color);
        }
        
        .footer {
            color: var(--text-secondary);
            padding: 20px 0;
            margin-top: 40px;
            border-top: 1px solid var(--card-border);
        }
        
        /* Neumorphic effect for cards */
        .card {
            position: relative;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, var(--primary-dark), var(--info-color));
            opacity: 0.7;
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--darker-bg);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-dark);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
        
        /* Glow effect for important elements */
        .card-header, .floating-btn {
            box-shadow: 0 0 15px rgba(52, 152, 219, 0.2);
        }
        
        .badge-prod, .badge-stby {
            box-shadow: 0 0 10px rgba(52, 152, 219, 0.3);
        }
        
        /* Table styling */
        .table {
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid var(--card-border);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table th, .table td {
            padding: 12px 15px;
        }
        
        /* File upload styling */
        .file-upload-container {
            border: 2px dashed var(--card-border);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .file-upload-container:hover {
            border-color: var(--primary-color);
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .file-upload-container i {
            font-size: 48px;
            color: var(--primary-color);
            margin-bottom: 15px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="header text-center">
        <div class="container">
            <h1 class="animate"><i class="fa fa-exchange fa-fw"></i> Comparador PROD vs STBY</h1>
            <p class="lead">Analiza las diferencias de versiones entre entornos de producción y respaldo</p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="card slide-in">
                    <div class="card-header">
                        <i class="fa fa-info-circle fa-fw"></i> Información del Sistema
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fa fa-lightbulb-o fa-2x pull-left"></i>
                            <strong>PROD como referencia principal:</strong> Comparación de versiones entre entorno de producción (PROD) y respaldo (STBY). 
                            Se analizan <?php echo count($componentesAIncluir); ?> componentes específicos.
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card summary-card">
                                    <div class="card-body">
                                        <h5><i class="fa fa-server fa-fw"></i> Entorno PROD</h5>
                                        <p class="text-muted">Entorno de producción principal</p>
                                        <div class="d-flex align-items-center">
                                            <span class="status-indicator status-prod"></span>
                                            <span>Versiones más recientes</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card summary-card">
                                    <div class="card-body">
                                        <h5><i class="fa fa-database fa-fw"></i> Entorno STBY</h5>
                                        <p class="text-muted">Entorno de respaldo (standby)</p>
                                        <div class="d-flex align-items-center">
                                            <span class="status-indicator status-stby"></span>
                                            <span>Entorno de contingencia</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card slide-in">
                    <div class="card-header">
                        <i class="fa fa-upload fa-fw"></i> Subir Archivos
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="file-upload-container">
                                        <i class="fa fa-cloud-upload"></i>
                                        <h4>Archivo PROD</h4>
                                        <p class="text-muted">Selecciona el archivo de producción</p>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="file" class="form-control" name="archivo_prod" id="archivo_prod" required>
                                                <span class="input-group-addon"><i class="fa fa-file-code-o"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="file-upload-container">
                                        <i class="fa fa-cloud-download"></i>
                                        <h4>Archivo STBY</h4>
                                        <p class="text-muted">Selecciona el archivo de respaldo</p>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="file" class="form-control" name="archivo_stby" id="archivo_stby" required>
                                                <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <button class="btn btn-primary btn-lg" type="submit">
                                    <i class="fa fa-play-circle fa-fw"></i> Comparar Versiones
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <?php if (!empty($resultado)): ?>
                    <!-- Resumen Estadístico -->
                    <div class="card fade-in">
                        <div class="card-header">
                            <i class="fa fa-bar-chart fa-fw"></i> Resumen de Comparación
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="panel panel-primary text-center">
                                        <div class="panel-body">
                                            <h3><i class="fa fa-cubes fa-2x"></i></h3>
                                            <h3><?php echo count($componentesAIncluir); ?></h3>
                                            <p class="text-muted">Componentes Analizados</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="panel panel-info text-center">
                                        <div class="panel-body">
                                            <h3><i class="fa fa-exclamation-triangle fa-2x"></i></h3>
                                            <h3><?php echo count($resultado['diferentes']); ?></h3>
                                            <p class="text-muted">Diferencias Encontradas</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="panel panel-warning text-center">
                                        <div class="panel-body">
                                            <h3><i class="fa fa-search-minus fa-2x"></i></h3>
                                            <h3><?php echo count($resultado['faltantes_stby']); ?></h3>
                                            <p class="text-muted">Faltantes en STBY</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="panel panel-danger text-center">
                                        <div class="panel-body">
                                            <h3><i class="fa fa-times-circle fa-2x"></i></h3>
                                            <h3><?php echo count($resultado['faltantes_prod']); ?></h3>
                                            <p class="text-muted">Faltantes en PROD</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Componentes con versiones diferentes -->
                    <div class="card fade-in">
                        <div class="card-header">
                            <i class="fa fa-code-fork fa-fw"></i> Componentes con Versiones Diferentes
                            <span class="badge pull-right"><?php echo count($resultado['diferentes']); ?></span>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($resultado['diferentes'])): ?>
                                <!-- Filtro de ambientes -->
                                <div class="filter-container">
                                    <h4>Filtrar por ambiente con versión más alta:</h4>
                                    <div class="text-center">
                                        <div class="btn-filter-container">
                                            <button class="btn btn-default filter-btn active" data-filter="all">
                                                <i class="fa fa-globe fa-fw"></i> Todos
                                            </button>
                                        </div>
                                        
                                        <div class="btn-filter-container">
                                            <button class="btn btn-primary filter-btn" data-filter="prod">
                                                <i class="fa fa-server fa-fw"></i> PROD
                                                <span class="badge-counter" id="counter-prod">
                                                    <?php 
                                                    $count_prod = 0;
                                                    foreach ($resultado['diferentes'] as $detalles) {
                                                        if ($detalles['mas_alta'] === 'PROD') $count_prod++;
                                                    }
                                                    echo $count_prod;
                                                    ?>
                                                </span>
                                            </button>
                                        </div>
                                        
                                        <div class="btn-filter-container">
                                            <button class="btn btn-success filter-btn" data-filter="stby">
                                                <i class="fa fa-database fa-fw"></i> STBY
                                                <span class="badge-counter" id="counter-stby">
                                                    <?php 
                                                    $count_stby = 0;
                                                    foreach ($resultado['diferentes'] as $detalles) {
                                                        if ($detalles['mas_alta'] === 'STBY') $count_stby++;
                                                    }
                                                    echo $count_stby;
                                                    ?>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover" id="tabla-diferencias">
                                        <thead>
                                            <tr>
                                                <th>Componente</th>
                                                <th class="text-center">Versión PROD</th>
                                                <th class="text-center">Versión STBY</th>
                                                <th>Ambiente más Actual</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($resultado['diferentes'] as $componente => $detalles): ?>
                                                <tr class="highlight-row" data-ambiente="<?php echo strtolower($detalles['mas_alta']); ?>">
                                                    <td><strong><?php echo htmlspecialchars($componente, ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                                    <td class="text-center <?php echo $detalles['mas_alta'] === 'PROD' ? 'version-higher' : ''; ?>">
                                                        <?php echo htmlspecialchars($detalles['PROD'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </td>
                                                    <td class="text-center <?php echo $detalles['mas_alta'] === 'STBY' ? 'version-higher' : ''; ?>">
                                                        <?php echo htmlspecialchars($detalles['STBY'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($detalles['mas_alta'] === 'PROD'): ?>
                                                            <span class="badge badge-prod">
                                                                <i class="fa fa-server fa-fw"></i> PROD
                                                            </span>
                                                        <?php elseif ($detalles['mas_alta'] === 'STBY'): ?>
                                                            <span class="badge badge-stby">
                                                                <i class="fa fa-database fa-fw"></i> STBY
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge badge-equal">
                                                                <i class="fa fa-equals fa-fw"></i> IGUALES
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success text-center">
                                    <i class="fa fa-check-circle fa-3x"></i>
                                    <h3>Todos los componentes comunes tienen la misma versión en ambos entornos</h3>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Componentes faltantes en STBY -->
                    <div class="card fade-in">
                        <div class="card-header">
                            <i class="fa fa-exclamation-triangle fa-fw"></i> Componentes Faltantes en STBY
                            <span class="badge pull-right"><?php echo count($resultado['faltantes_stby']); ?></span>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($resultado['faltantes_stby'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Componente</th>
                                                <th>Versión en PROD</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($resultado['faltantes_stby'] as $componente => $version): ?>
                                                <tr class="highlight-row">
                                                    <td><strong><?php echo htmlspecialchars($componente, ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                                    <td><?php echo htmlspecialchars($version, ENT_QUOTES, 'UTF-8'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success text-center">
                                    <i class="fa fa-check-circle fa-3x"></i>
                                    <h3>Todos los componentes de PROD están presentes en STBY</h3>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Componentes faltantes en PROD -->
                    <div class="card fade-in">
                        <div class="card-header">
                            <i class="fa fa-search-minus fa-fw"></i> Componentes de la Lista Faltantes en PROD
                            <span class="badge pull-right"><?php echo count($resultado['faltantes_prod']); ?></span>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($resultado['faltantes_prod'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Componente</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($resultado['faltantes_prod'] as $componente): ?>
                                                <tr class="highlight-row">
                                                    <td><strong><?php echo htmlspecialchars($componente, ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                                    <td><span class="label label-danger">No encontrado</span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success text-center">
                                    <i class="fa fa-check-circle fa-3x"></i>
                                    <h3>Todos los componentes de la lista están presentes en PROD</h3>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <a href="#" class="floating-btn">
        <i class="fa fa-arrow-up"></i>
    </a>

    <footer class="footer mt-5">
        <div class="container text-center">
            <p class="text-muted">Comparador PROD vs STBY &copy; <?php echo date('Y'); ?> - Todos los derechos reservados</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Botón flotante para volver arriba
            $('.floating-btn').click(function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, 800);
                return false;
            });
            
            // Animaciones al aparecer
            $('.animate').each(function(i) {
                $(this).delay(i * 200).animate({opacity: 1}, 800);
            });
            
            // Animación para elementos que entran al hacer scroll
            $(window).scroll(function() {
                $('.fade-in, .slide-in').each(function() {
                    var elementPos = $(this).offset().top;
                    var scrollPos = $(window).scrollTop();
                    var windowHeight = $(window).height();
                    
                    if (elementPos < scrollPos + windowHeight - 50 && !$(this).hasClass('animated')) {
                        $(this).addClass('animated');
                        if ($(this).hasClass('fade-in')) {
                            $(this).css('animation', 'fadeIn 0.8s ease forwards');
                        }
                        if ($(this).hasClass('slide-in')) {
                            $(this).css('animation', 'slideIn 0.6s ease forwards');
                        }
                    }
                });
            }).scroll();
            
            // Resaltar filas con animación
            $('.highlight-row').each(function(i) {
                var $row = $(this);
                setTimeout(function() {
                    $row.addClass('highlight-row');
                    setTimeout(function() {
                        $row.removeClass('highlight-row');
                    }, 1500);
                }, i * 200);
            });
            
            // Filtro de ambientes
            $('.filter-btn').click(function() {
                // Quitar activo de todos los botones
                $('.filter-btn').removeClass('active');
                
                // Activar el botón clickeado
                $(this).addClass('active');
                
                // Obtener el filtro seleccionado
                var filter = $(this).data('filter');
                
                // Mostrar todas las filas primero
                $('#tabla-diferencias tbody tr').show();
                
                // Aplicar filtro
                if (filter !== 'all') {
                    $('#tabla-diferencias tbody tr').each(function() {
                        var ambiente = $(this).data('ambiente');
                        if (ambiente !== filter) {
                            $(this).hide();
                        }
                    });
                }
            });
            
            // Agregar efecto hover a las tarjetas
            $('.card').hover(
                function() {
                    $(this).css('transform', 'translateY(-5px)');
                },
                function() {
                    $(this).css('transform', 'translateY(0)');
                }
            );
            
            // Agregar efecto hover a los botones de filtro
            $('.filter-btn').hover(
                function() {
                    if (!$(this).hasClass('active')) {
                        $(this).css('background-color', '#25304a');
                    }
                },
                function() {
                    if (!$(this).hasClass('active')) {
                        $(this).css('background-color', 'var(--table-header)');
                    }
                }
            );
        });
    </script>
</body>
</html>