<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Envío Masivo SOAP - Activar VoLTE Prepago/Postpago SV</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #4e73df;
      --success-color: #1cc88a;
      --warning-color: #f6c23e;
      --danger-color: #e74a3b;
      --body-bg: #212529;
      --card-bg: #2c3034;
      --text-color: #f8f9fa;
      --border-color: #495057;
      --console-bg: #1a1a1a;
      --input-bg: #343a40;
      --progress-bg: #343a40;
    }

    [data-bs-theme="light"] {
      --body-bg: #f8f9fa;
      --card-bg: #ffffff;
      --text-color: #212529;
      --border-color: #dee2e6;
      --console-bg: #f1f1f1;
      --input-bg: #ffffff;
      --progress-bg: #e9ecef;
    }

    body {
      background-color: var(--body-bg);
      color: var(--text-color);
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .card {
      background-color: var(--card-bg);
      border-color: var(--border-color);
    }

    .card-header {
      border-bottom-color: var(--border-color);
    }

    .form-control, .form-select {
      background-color: var(--input-bg);
      color: var(--text-color);
      border-color: var(--border-color);
    }

    .form-control:focus, .form-select:focus {
      background-color: var(--input-bg);
      color: var(--text-color);
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }

    #console-output {
      background-color: var(--console-bg);
      color: var(--text-color);
      border: 1px solid var(--border-color);
      height: 200px;
      overflow-y: auto;
      padding: 10px;
      border-radius: 4px;
      font-family: monospace;
      font-size: 14px;
      white-space: pre-wrap;
    }

    .console-message {
      padding: 3px 0;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .console-error { color: #ff6b6b; }
    .console-warning { color: #ffd43b; }
    .console-success { color: #51cf66; }
    .console-info { color: #339af0; }

    #progress-container {
      background-color: var(--progress-bg);
      border: 1px solid var(--border-color);
      height: 30px;
      border-radius: 4px;
      overflow: hidden;
      margin-bottom: 10px;
    }

    #progress-bar {
      height: 100%;
      background-color: var(--primary-color);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      transition: width 0.5s ease;
    }

    .theme-toggle {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: var(--primary-color);
      color: white;
      border: none;
      cursor: pointer;
      z-index: 1000;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      transition: all 0.3s ease;
    }

    .theme-toggle:hover {
      transform: scale(1.1);
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }

    #estimated-time-value {
      font-weight: bold;
      margin-left: 5px;
    }

    .estimated-time {
      margin-bottom: 15px;
      font-size: 14px;
    }

    .btn-action {
      min-width: 90px;
    }

    .input-group button#togglePassword {
      background-color: var(--input-bg);
      border-color: var(--border-color);
      color: var(--text-color);
    }

    .input-group button#togglePassword:hover {
      background-color: var(--primary-color);
      color: white;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card shadow-lg mb-5">
          <div class="card-header bg-primary text-white py-3">
            <h3 class="mb-0"><i class="fas fa-satellite-dish me-2"></i>Envío Masivo SOAP - Activar VoLTE Prepago/Postpago El Salvador</h3>
          </div>
          <div class="card-body p-4">
            <form id="formulario" action="procesar.php" method="POST" enctype="multipart/form-data" target="iframe_oculto">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-bold">1. Endpoint SOAP:</label>
                  <select name="endpoint" id="endpoint_select" class="form-select" required>
                    <option value="">Seleccione un endpoint</option>
                    <option value="http://172.20.11.105:8080/CAI3G1.2/services/CAI3G1.2/async" selected>EDA STBY Standby - El Salvador</option>
                    <option value="http://172.24.80.134:8080/CAI3G1.2/services/CAI3G1.2/async">EDANI Producción- El Salvador</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-bold">2. Usuario SOAP:</label>
                  <input type="text" name="usuario" class="form-control" required placeholder="Ej: TuUsuario">
                  <label class="form-label fw-bold mt-2">3. Contraseña SOAP:</label>
                  <div class="input-group">
                    <input type="password" name="contrasena" id="contrasena" class="form-control" required placeholder="Contraseña SOAP">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-bold">4. Seleccione el país:</label>
                  <select name="pais" id="pais_select" class="form-select">
                    <option value="El Salvador" selected>El Salvador</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-bold">5. Tipo de Servicio:</label>
                  <select name="tipo_servicio" id="tipo_servicio" class="form-select" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="prepago">Prepago</option>
                    <option value="postpago">Postpago</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-bold">6. Seleccione un Archivo (.csv,.txt):</label>
                  <input type="file" name="archivo" class="form-control" required accept=".csv,.txt">
                  <small class="text-warning form-label fw-bold">Formato requerido: Número,Imsi (Sin Encabezado)</small>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-bold">Tiempo entre peticiones (ms):</label>
                  <select name="delay" class="form-select" onchange="actualizarDelay(this.value)">
                    <option value="1000" selected>1,000 ms</option>
                    <option value="3000">3,000 ms</option>
                    <option value="5000">5,000 ms</option>
                  </select>
                  <small class="text-muted">Retraso entre llamadas SOAP.</small>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-bold">Estado actual:</label>
                  <div id="status-display" class="badge bg-secondary">Inactivo</div>
                </div>
              </div>

              <div class="mt-4 text-center">
                <button type="button" onclick="iniciarEnvio()" id="main-start-btn" class="btn btn-success btn-lg">
                  <i class="fas fa-play-circle me-2"></i>Iniciar Envío Masivo
                </button>
              </div>
            </form>
          </div>
        </div>

        <div class="card shadow mb-4" id="progress-card" style="display: none;">
          <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Progreso del Envío</h6>
            <div>
              <button type="button" onclick="pausarEnvio()" id="pause-btn" class="btn btn-warning btn-sm me-2 btn-action">
                <i class="fas fa-pause me-1"></i>Pausar
              </button>
              <button type="button" onclick="reanudarEnvio()" id="resume-btn" class="btn btn-success btn-sm me-2 btn-action" style="display:none;">
                <i class="fas fa-play me-1"></i>Reanudar
              </button>
              <button type="button" onclick="confirmarDetener()" class="btn btn-danger btn-sm btn-action">
                <i class="fas fa-stop me-1"></i>Detener
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
              <span id="progress-text">0% completado</span>
              <span id="counters">0/0 registros</span>
            </div>
            <div id="progress-container" class="mb-3">
              <div id="progress-bar"></div>
            </div>
            <div id="estimated-completion" class="estimated-time">
              <span id="estimated-time-label">Finalización estimada:</span>
              <span id="estimated-time-value" class="fw-bold">--/--/---- --:--:--</span>
            </div>
            <div id="console-output"></div>
            <div class="mt-3 text-center">
              <button type="button" onclick="descargarLog()" id="download-btn" class="btn btn-primary" style="display:none;">
                <i class="fas fa-download me-1"></i>Descargar Log Completo
              </button>
            </div>
          </div>
        </div>

        <iframe name="iframe_oculto" id="iframe_oculto" style="display:none;"></iframe>
      </div>
    </div>
  </div>

  <button class="theme-toggle" onclick="toggleTheme()" data-bs-toggle="tooltip" data-bs-placement="right" title="Cambiar tema">
    <i class="fas fa-moon"></i>
  </button>

  <div class="toast-container position-fixed top-0 end-0 p-3"></div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Variables globales
    let estadoEnvio = 'inactivo';
    let logFileName = '';
    let totalRegistros = 0;
    let delayMs = 3000;
    let intervaloEstimado;
    let estadoIntervalo;

    function toggleTheme() {
      const html = document.documentElement;
      const themeIcon = document.querySelector('.theme-toggle i');
      
      if (html.getAttribute('data-bs-theme') === 'dark') {
        html.setAttribute('data-bs-theme', 'light');
        themeIcon.classList.replace('fa-moon', 'fa-sun');
        localStorage.setItem('theme', 'light');
      } else {
        html.setAttribute('data-bs-theme', 'dark');
        themeIcon.classList.replace('fa-sun', 'fa-moon');
        localStorage.setItem('theme', 'dark');
      }
    }

    function setupPasswordToggle() {
      const togglePassword = document.getElementById('togglePassword');
      const passwordInput = document.getElementById('contrasena');
      
      if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
          const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordInput.setAttribute('type', type);
          
          const icon = this.querySelector('i');
          if (type === 'password') {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
          } else {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
          }
        });
      }
    }

    function showToast(message, type = 'info', duration = 5000) {
      const toastContainer = document.querySelector('.toast-container');
      const toastId = 'toast-' + Date.now();
      
      const toastEl = document.createElement('div');
      toastEl.id = toastId;
      toastEl.className = `toast bg-${type === 'info' ? 'primary' : type} text-white`;
      toastEl.setAttribute('role', 'alert');
      toastEl.setAttribute('aria-live', 'assertive');
      toastEl.setAttribute('aria-atomic', 'true');
      
      toastEl.innerHTML = `
        <div class="toast-header">
          <strong class="me-auto">Notificación</strong>
          <small>Ahora</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">${message}</div>
      `;
      
      toastContainer.appendChild(toastEl);
      const bsToast = new bootstrap.Toast(toastEl);
      bsToast.show();
      
      setTimeout(() => bsToast.hide(), duration);
      toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    }

    function actualizarConsola(mensaje) {
      const consoleOutput = document.getElementById('console-output');
      const messageEl = document.createElement('div');
      
      messageEl.textContent = mensaje;
      
      if (mensaje.includes('❌') || mensaje.includes('Error')) {
        messageEl.className = 'console-message console-error';
      } else if (mensaje.includes('⚠️') || mensaje.includes('Saltado')) {
        messageEl.className = 'console-message console-warning';
      } else if (mensaje.includes('✅') || mensaje.includes('OK')) {
        messageEl.className = 'console-message console-success';
      } else {
        messageEl.className = 'console-message console-info';
      }
      
      consoleOutput.appendChild(messageEl);
      consoleOutput.scrollTop = consoleOutput.scrollHeight;
    }

    function actualizarBarraEnvio(progreso) {
      const progressBar = document.getElementById('progress-bar');
      progressBar.style.width = progreso + '%';
      progressBar.textContent = progreso + '%';
      document.getElementById('progress-text').textContent = progreso + '% completado';
      calcularTiempoFinalizacion();
    }

    function actualizarContadores(enviados, total) {
      document.getElementById('counters').textContent = enviados + '/' + total + ' registros';
      totalRegistros = total;
      calcularTiempoFinalizacion();
    }

    function finalizarBarra() {
      actualizarBarraEnvio(100);
      showToast('Proceso completado', 'success');
      clearInterval(intervaloEstimado);
      clearInterval(estadoIntervalo);
      
      estadoEnvio = 'inactivo';
      actualizarEstadoUI();
      
      document.getElementById('download-btn').style.display = 'inline-block';
    }

    function calcularTiempoFinalizacion() {
      if (totalRegistros === 0) return;
      
      const progreso = parseInt(document.getElementById('progress-bar').style.width) || 0;
      const registrosRestantes = totalRegistros - (totalRegistros * (progreso / 100));
      const tiempoRestanteMs = registrosRestantes * delayMs;
      
      if (tiempoRestanteMs > 0) {
        const fechaFinalizacion = new Date(Date.now() + tiempoRestanteMs);
        const formatoFecha = fechaFinalizacion.toLocaleString('es-ES');
        document.getElementById('estimated-time-value').textContent = formatoFecha;
      }
    }

    function actualizarDelay(valor) {
      const allowedDelays = [1000, 2000, 3000, 5000];
      delayMs = allowedDelays.includes(parseInt(valor)) ? parseInt(valor) : 3000;
      calcularTiempoFinalizacion();
    }

    function iniciarEnvio() {
    const archivoInput = document.querySelector('input[name="archivo"]');
    const formulario = document.getElementById('formulario');
    
    // Validación de campos
    const usuario = document.querySelector('input[name="usuario"]').value.trim();
    const contrasena = document.querySelector('input[name="contrasena"]').value.trim();
    const endpoint = document.getElementById('endpoint_select').value.trim();
    const tipoServicio = document.getElementById('tipo_servicio').value.trim();
    
    if (!usuario || !contrasena || !endpoint || !tipoServicio) {
        showToast("Debe completar todos los campos requeridos", "error");
        return;
    }
    
    if (!archivoInput.files || archivoInput.files.length === 0) {
        showToast("Debe seleccionar un archivo para procesar", "error");
        return;
    }
    
    // Mostrar mensaje de depuración
    console.log("Preparando para enviar...");
    
    fetch('control.php?accion=limpiar')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta de control.php');
            }
            return response.text();
        })
        .then(text => {
            console.log("Respuesta de control.php:", text);
            document.getElementById('progress-card').style.display = 'block';
            document.getElementById('progress-bar').style.width = '0%';
            document.getElementById('progress-text').textContent = '0% completado';
            document.getElementById('counters').textContent = '0/0 registros';
            document.getElementById('console-output').innerHTML = '';
            document.getElementById('download-btn').style.display = 'none';
            
            console.log("Enviando formulario...");
            formulario.submit();
            
            estadoEnvio = 'activo';
            actualizarEstadoUI();
            
            estadoIntervalo = setInterval(verificarEstado, 2000);
            intervaloEstimado = setInterval(calcularTiempoFinalizacion, 1000);
        })
        .catch(err => {
            console.error("Error en iniciarEnvio:", err);
            showToast('Error al iniciar el envío: ' + err.message, 'error');
        });
}

    function descargarLog() {
      if (logFileName) {
        const link = document.createElement('a');
        link.href = logFileName;
        link.download = logFileName.split('/').pop();
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      } else {
        showToast('No hay archivo de log disponible', 'warning');
      }
    }

    function actualizarLogFileName(nombreArchivo) {
      logFileName = nombreArchivo;
      document.getElementById('download-btn').style.display = 'inline-block';
    }

    function actualizarTotalRegistros(total) {
      totalRegistros = total;
      calcularTiempoFinalizacion();
    }

    function actualizarEstadoUI() {
      const statusDisplay = document.getElementById('status-display');
      const pauseBtn = document.getElementById('pause-btn');
      const resumeBtn = document.getElementById('resume-btn');
      const mainStartBtn = document.getElementById('main-start-btn');
      
      switch(estadoEnvio) {
        case 'activo':
          statusDisplay.className = 'badge bg-primary';
          statusDisplay.textContent = 'Activo';
          pauseBtn.style.display = 'inline-block';
          resumeBtn.style.display = 'none';
          mainStartBtn.disabled = true;
          mainStartBtn.innerHTML = '<i class="fas fa-sync-alt fa-spin me-2"></i>Enviando...';
          break;
          
        case 'pausado':
          statusDisplay.className = 'badge bg-warning text-dark';
          statusDisplay.textContent = 'Pausado';
          pauseBtn.style.display = 'none';
          resumeBtn.style.display = 'inline-block';
          mainStartBtn.disabled = true;
          mainStartBtn.innerHTML = '<i class="fas fa-pause me-2"></i>Envio Pausado';
          break;
          
        case 'inactivo':
          statusDisplay.className = 'badge bg-secondary';
          statusDisplay.textContent = 'Inactivo';
          pauseBtn.style.display = 'none';
          resumeBtn.style.display = 'none';
          mainStartBtn.disabled = false;
          mainStartBtn.innerHTML = '<i class="fas fa-play-circle me-2"></i>Iniciar Envío Masivo';
          break;
      }
    }

    function pausarEnvio() {
      fetch('control.php?accion=pausar')
        .then(response => response.text())
        .then(msg => {
          showToast(msg, 'warning');
          estadoEnvio = 'pausado';
          actualizarEstadoUI();
        })
        .catch(err => showToast('Error al pausar: ' + err, 'error'));
    }
    
    function reanudarEnvio() {
      fetch('control.php?accion=reanudar')
        .then(response => response.text())
        .then(msg => {
          showToast(msg, 'success');
          estadoEnvio = 'activo';
          actualizarEstadoUI();
        })
        .catch(err => showToast('Error al reanudar: ' + err, 'error'));
    }
    
    function confirmarDetener() {
      const confirmToast = document.createElement('div');
      confirmToast.className = 'toast bg-dark text-white';
      confirmToast.setAttribute('role', 'alert');
      confirmToast.setAttribute('aria-live', 'assertive');
      confirmToast.setAttribute('aria-atomic', 'true');
      
      confirmToast.innerHTML = `
        <div class="toast-body">
          <p>¿Está seguro de detener el envío actual?</p>
          <div class="mt-2 pt-2 border-top d-flex justify-content-end">
            <button type="button" class="btn btn-sm btn-outline-light me-2" data-bs-dismiss="toast">Cancelar</button>
            <button type="button" class="btn btn-sm btn-danger" id="confirm-stop">Detener</button>
          </div>
        </div>
      `;
      
      document.querySelector('.toast-container').appendChild(confirmToast);
      const bsToast = new bootstrap.Toast(confirmToast);
      bsToast.show();
      
      confirmToast.addEventListener('hidden.bs.toast', () => {
        confirmToast.remove();
      });
      
      document.getElementById('confirm-stop').addEventListener('click', function() {
        bsToast.hide();
        detenerEnvio();
      });
    }
    
    function detenerEnvio() {
      fetch('control.php?accion=detener')
        .then(response => response.text())
        .then(msg => {
          showToast(msg, 'error');
          estadoEnvio = 'inactivo';
          actualizarEstadoUI();
          clearInterval(intervaloEstimado);
          clearInterval(estadoIntervalo);
          document.getElementById('iframe_oculto').src = 'about:blank';
        })
        .catch(err => showToast('Error al detener: ' + err, 'error'));
    }
    
    function verificarEstado() {
      fetch('control.php?accion=estado')
        .then(response => response.text())
        .then(estado => {
          if (estado === 'detenido') {
            estadoEnvio = 'inactivo';
            actualizarEstadoUI();
            clearInterval(intervaloEstimado);
            clearInterval(estadoIntervalo);
          }
        })
        .catch(err => console.error('Error al verificar estado:', err));
    }
    
    document.addEventListener('DOMContentLoaded', function() {
      const savedTheme = localStorage.getItem('theme') || 'dark';
      const html = document.documentElement;
      const themeIcon = document.querySelector('.theme-toggle i');
      
      html.setAttribute('data-bs-theme', savedTheme);
      
      if (savedTheme === 'light') {
        themeIcon.classList.replace('fa-moon', 'fa-sun');
      } else {
        themeIcon.classList.replace('fa-sun', 'fa-moon');
      }
      
      new bootstrap.Tooltip(document.querySelector('[data-bs-toggle="tooltip"]'));
      
      const delaySelect = document.querySelector('select[name="delay"]');
      if (delaySelect) delayMs = parseInt(delaySelect.value);
      
      document.getElementById('progress-bar').style.width = '0%';
      
      setupPasswordToggle();
    });

    window.actualizarConsola = actualizarConsola;
    window.actualizarBarraEnvio = actualizarBarraEnvio;
    window.actualizarContadores = actualizarContadores;
    window.finalizarBarra = finalizarBarra;
    window.actualizarTotalRegistros = actualizarTotalRegistros;
    window.actualizarLogFileName = actualizarLogFileName;
    window.toggleTheme = toggleTheme;
  </script>
</body>
</html>