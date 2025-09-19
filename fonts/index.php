<?php
/**
 * index.php — Portal con buscador estable + efectos, animaciones, sombreados,
 * y tipografía San-Andreas en el encabezado.
 * Compatible con PHP 5.4.31
 */

$sites = array(
    array('name'=>'Dump Cablemodems Axess','url'=>'http://172.16.68.252:888/monitoreos/dump_axess/index.php','desc'=>'Monitoreo de Dump Axess en tiempo real'),
    array('name'=>'Crear Usuarios Dump','url'=>'http://172.16.68.252:888/monitoreos/usuariosdump/index.php','desc'=>'Listado y gestión de usuarios Dump'),
    array('name'=>'Comandos EDA','url'=>'http://172.16.68.252:888/monitoreos/comandos_eda.php','desc'=>'Panel de comandos EDA e historial'),
    array('name'=>'Verificador IMSISGT','url'=>'http://172.16.68.252:888/monitoreos/imsisgt/verificador.php','desc'=>'Herramienta de verificación IMSISGT'),
    array('name'=>'ProcLog Analyzer CENAM Timeout','url'=>'http://172.16.68.252:888/monitoreos/proclogs/progloganalizer.php','desc'=>'Analizador de logs de ProgLog timeout'),
    array('name'=>'ProcLog Filter CENAM','url'=>'http://172.16.68.252:888/monitoreos/proclogfilter/proclogfilter.php','desc'=>'Analizador de logs ProcLog (server-side)'),
    array('name'=>'VoLTE Analyzer CENAM','url'=>'http://172.16.68.252:888/monitoreos/consultaelementosGT/voltanalizer.php','desc'=>'Inspector de elementos VoLTE'),
    array('name'=>'Monitor de Host','url'=>'http://172.16.68.252:5000/','desc'=>'Dashboard de monitor de host'),
    array('name'=>'Alarms ARH Log Analyzer','url'=>'http://172.16.68.252:888/monitoreos/arh/alarmloganalizer.php','desc'=>'Análisis de logs de alarmas ARH'),
    array('name'=>'Crear Velocidades AXESS','url'=>'http://172.16.68.252:5555/','desc'=>'Generador de perfiles de velocidad AXESS'),
    array('name'=>'Text Compare','url'=>'http://172.16.68.252:888/monitoreos/textcompare/','desc'=>'Comparador de textos en línea'),
    array('name'=>'Abrir Casos ITS','url'=>'https://helpdesk.itsinfocom.com/front/ticket.php','desc'=>'Portal de tickets ITSINFOCOM'),
    array('name'=>'Monitor de Tquerys GAIA','url'=>'http://172.16.68.252:888/monitoreos/tquerys/monitoreotquerys.php','desc'=>'Monitoreo T-Querys GAIA'),
    array('name'=>'Crear Dump Axess Velocidades CENAM','url'=>'http://172.16.68.252:888/monitoreos/axess/dumpvelocidadesaxess.php','desc'=>'Dump Axess Velocidades'),
    array('name'=>'Envío Masivo Bajas HFC TV CENAM','url'=>'http://172.16.68.252:888/monitoreos/bajaclientesHFCTVCENAM/','desc'=>'Baja HFC TV CENAM'),
    array('name'=>'Envío Masivo Bajas HFC Internet CENAM','url'=>'http://172.16.68.252:888/monitoreos/bajaHFCInternet/','desc'=>'Bajas Internet HFC CENAM'),
    array('name'=>'Envío Masivo Bajas HFC Voz CENAM','url'=>'http://172.16.68.252:888/monitoreos/bajaHFCtelefonia/','desc'=>'Bajas Voz HFC CENAM'),
    array('name'=>'Envío Bajas HFC Voz e Internet CENAM','url'=>'http://172.16.68.252:888/monitoreos/bajaHFCtelefoniainternet/','desc'=>'Bajas Voz e Internet HFC CENAM'),
    array('name'=>'Envío Bajas Clientes DTH TV CENAM','url'=>'http://172.16.68.252:888/monitoreos/bajaclientesDTH/','desc'=>'Bajas DTH TV clientes CENAM'),
    array('name'=>'Envío Bajas de paquetes DTH CENAM','url'=>'http://172.16.68.252:888/monitoreos/bajapaquetesDTH/','desc'=>'Bajas paquetes DTH TV CENAM'),
    array('name'=>'Password Generator','url'=>'http://172.16.68.252:888/monitoreos/passwordgenerator/','desc'=>'Generar contraseñas aleatorias'),
    array('name'=>'Analizador SM entre Ambientes','url'=>'http://172.16.68.252:888/monitoreos/compararSM/','desc'=>'Analizador de versiones Service Model EDA'),
    array('name'=>'Activar VoLTE Prepago/Postpago SV STBY','url'=>'http://172.16.68.252:888/monitoreos/volteprepostSV/','desc'=>'Activar VOLTE Prepago/Postpago EDA SV'),
    array('name'=>'Crear en FNR con PT3 EDA GT','url'=>'http://172.16.68.252:888/monitoreos/createfnr3GT/','desc'=>'Crear FNR PT3 EDA GT'),
    array('name'=>'Crear Prepagos EDA GT','url'=>'http://172.16.68.252:888/monitoreos/crearprepagoGT/','desc'=>'Crear prepago GT'),
    array('name'=>'Agregar Pool CMTS AXESS','url'=>'http://172.16.68.252:888/monitoreos/addpoolCMAXESS/','desc'=>'Agregar rangos IPs CMTS'),
    array('name'=>'Usecase AXESS HFC CENAM','url'=>'http://172.16.68.252:888/monitoreos/usecaseAXESSCENAM/','desc'=>'Suspensión, reactivación y cambio de velocidad'),
    array('name'=>'Activar VoLTE Prepago/Postpago NI STBY','url'=>'http://172.16.68.252:888/monitoreos/volteprepostNI/','desc'=>'Activar VOLTE NI STBY'),
    array('name'=>'Bloqueo Servicios ADD 92 GT / NI STBY','url'=>'http://172.16.68.252:888/monitoreos/bloquearmovilGT/','desc'=>'Bloqueo Servicios GT add service 92'),
    array('name'=>'MultiFilter Proclog CENAM','url'=>'http://172.16.68.252:888/monitoreos/multifilterproclog/','desc'=>'Filtrar varios campos ProcLog'),
    array('name'=>'Reset VLR GT Masivo','url'=>'http://172.16.68.252:888/monitoreos/resetVLRGT/','desc'=>'Reset VLR GT masivo'),
    array('name'=>'Resultado de Consulta Masiva GT','url'=>'http://172.16.68.252:888/monitoreos/consultaHLRGTresultado/','desc'=>'Resultado de Consulta Masiva GT'),
    array('name'=>'SM Instalados EDA CENAM','url'=>'http://172.16.68.252:888/monitoreos/monitorEDACENAM/','desc'=>'Ver SM instalados por EDA')
);

$aos = array('fade-up','zoom-in','flip-left','fade-right','fade-down','flip-up');
?><!doctype html>
<html lang="es">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Portal CENAM</title>

<!-- Tipografía San-Andreas (coloca estos archivos en la misma carpeta si los tienes) -->
<style>
/* Importar tipografía desde carpeta fonts */
@font-face {
  font-family: 'SanAndreasGTA';
  src: url('fonts/san-andreas.ttf') format('truetype');
  font-weight: normal;
  font-style: normal;
  font-display: swap;
}

/* Usar la fuente en el título */
.portal-title {
  font-family: 'SanAndreasGTA', serif;
  font-size: 3.2rem;
  text-align: center;
  margin: 22px 0 4px;
  color: #0b0e14;
  -webkit-text-stroke: 3px #fff;
  text-shadow: 0 0 1px #000, 0 0 6px rgba(0,0,0,.9);
  letter-spacing: .5px;
  animation: animate__bounceInDown 1s both;
}
</style>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">

<style>
  :root{
    --bg-1:#0f131a;          /* fondo oscuro */
    --card:#1c212e;          /* fondo card */
    --muted:#b8c3d0;         /* texto muted */
    --url:#9fb0c6;           /* url color */
    --accent:#6a5acd;        /* violeta-azulado pedido */
    --glow:rgba(106,90,205,.55);
  }
  html,body{background:radial-gradient(1200px 600px at 10% -10%,rgba(106,90,205,.15),transparent 60%),
                         radial-gradient(900px 400px at 110% 10%,rgba(0,191,255,.10),transparent 60%),
                         var(--bg-1);
            color:#eef2f7; overflow-x:hidden}
  .container{max-width:1200px}

  /* Header San-Andreas con outline */
  .portal-title{
    font-family:'SanAndreasGTA', 'San Andreas', 'Old English Text MT', serif;
    font-size:3.05rem; text-align:center; margin:22px 0 4px;
    color:#0b0e14;
    -webkit-text-stroke:3px #fff;
    text-shadow:0 0 1px #000, 0 0 6px rgba(0,0,0,.9);
    letter-spacing:.5px;
    animation: animate__bounceInDown 1s both;
  }
  .subheader{ text-align:center; color:#d8deea; margin-bottom:14px; text-shadow:0 0 6px rgba(0,0,0,.55); }

  /* Buscador sticky + glass */
  .search-wrap{
    position:sticky; top:0; z-index:1030;
    background:linear-gradient(180deg, rgba(15,19,26,.92), rgba(15,19,26,.85));
    backdrop-filter: blur(6px);
    border-bottom:1px solid rgba(255,255,255,.06);
    box-shadow:0 8px 22px -16px rgba(0,0,0,.8);
    padding:12px 0 14px;
  }
  .input-group .input-group-text{ background:#171c27; color:#b6c3d8; border-color:#2a3145 }
  .form-control{ background:#121722; color:#e6edf3; border-color:#2a3145 }
  .form-control:focus{ border-color:var(--accent); box-shadow:0 0 0 .2rem rgba(106,90,205,.3) }
  .btn-clear{ border-color:var(--accent); color:#e8e3ff }
  .btn-clear:hover{ background:var(--accent); color:#fff }
  .match-count{ color:#9fb0c6; font-size:.9rem; margin-top:6px }

  /* Cards con sombras y animaciones */
  .card-portal{
    background:linear-gradient(180deg, rgba(28,33,46,.98), rgba(28,33,46,.94));
    border:1px solid rgba(255,255,255,.06);
    box-shadow:0 18px 48px -18px rgba(0,0,0,.9);
    transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease, filter .22s ease;
    will-change: transform, box-shadow;
    overflow:hidden;
  }
  .card-portal:hover{
    transform: translateY(-6px);
    box-shadow: 0 28px 70px -24px rgba(0,0,0,.95);
    border-color: rgba(255,255,255,.12);
  }
  .card-title{ color:#fff; font-weight:800; font-size:1.04rem; letter-spacing:.2px }
  .card-url{ color:var(--url); font-size:.85rem; word-break: break-all; }
  .card-desc{ color:var(--muted); font-size:.92rem; }
  .btn-go{ border-color:var(--accent); color:#efeaff; font-weight:600 }
  .btn-go:hover{ background:var(--accent); color:#fff }

  /* Resaltado DE COINCIDENCIA sin tocar innerHTML */
  .col.matched .card-portal{
    border-color: var(--accent);
    box-shadow: 0 0 0 1px var(--accent), 0 18px 48px -18px rgba(106,90,205,.6);
    animation: pulseGlow .9s ease-out 1;
  }
  @keyframes pulseGlow{
    0%{ filter: drop-shadow(0 0 0 rgba(106,90,205,0)); }
    50%{ filter: drop-shadow(0 0 16px var(--glow)); }
    100%{ filter: drop-shadow(0 0 0 rgba(106,90,205,0)); }
  }

  /* Micro “ribbon” decorativo para matches */
  .col.matched .ribbon{
    position:absolute; top:8px; right:-42px; transform:rotate(35deg);
    background:linear-gradient(90deg, var(--accent), #00bfff);
    color:#fff; padding:2px 48px; font-size:.72rem; opacity:.85; box-shadow:0 6px 14px -6px rgba(0,0,0,.7)
  }
  .ribbon{ display:none }
  .col.matched .ribbon{ display:block }

  /* Partículas de fondo (sutiles) */
  #particles-js{ position:fixed; inset:0; z-index:-2; opacity:.45 }
  #overlay{ position:fixed; inset:0; z-index:-1; background: radial-gradient(900px 360px at 95% 0%, rgba(106,90,205,.08), transparent 50%) }

</style>
</head>
<body>

<div id="particles-js"></div>
<div id="overlay"></div>

<h1 class="portal-title">Syn’s Portal</h1>
<p class="subheader">Busca por nombre, URL o descripción. Las coincidencias “brillan”.</p>

<!-- Buscador sticky -->
<div class="search-wrap">
  <div class="container">
    <div class="row justify-content-center g-2">
      <div class="col-12 col-md-9">
        <div class="input-group input-group-lg">
          <span class="input-group-text">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M21.53 20.47l-4.66-4.66A7.92 7.92 0 0018 10a8 8 0 10-8 8 7.92 7.92 0 005.81-1.13l4.66 4.66a.75.75 0 101.06-1.06zM4.5 10a5.5 5.5 0 1111 0 5.5 5.5 0 01-11 0z"/></svg>
          </span>
          <input id="search" type="text" class="form-control" placeholder="Buscar...">
          <button id="clearBtn" class="btn btn-clear" type="button">Limpiar</button>
        </div>
        <div class="match-count" id="matchCount">Mostrando todos</div>
      </div>
    </div>
  </div>
</div>

<main class="container my-4">
  <!-- Grid responsive con alturas iguales -->
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="cards">
    <?php
    $i = 0;
    foreach ($sites as $site):
      $i++;
      $name = htmlspecialchars($site['name'], ENT_QUOTES, 'UTF-8');
      $url  = htmlspecialchars($site['url'], ENT_QUOTES, 'UTF-8');
      $desc = htmlspecialchars($site['desc'], ENT_QUOTES, 'UTF-8');
      $aosType = $aos[$i % count($aos)];
    ?>
    <div class="col"
         data-name="<?php echo strtolower($name); ?>"
         data-url="<?php echo strtolower($url); ?>"
         data-desc="<?php echo strtolower($desc); ?>"
         data-aos="<?php echo $aosType; ?>"
         data-aos-delay="<?php echo ($i % 6) * 90; ?>">
      <div class="position-relative card h-100 card-portal">
        <div class="ribbon">MATCH</div>
        <div class="card-body d-flex flex-column">
          <h5 class="card-title mb-2"><?php echo $name; ?></h5>
          <div class="mb-2 card-url"><?php echo $url; ?></div>
          <div class="card-desc mb-3"><?php echo $desc; ?></div>
          <div class="mt-auto">
            <a class="btn btn-sm btn-go" href="<?php echo $url; ?>" target="_blank" rel="noopener">Ir al sitio</a>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</main>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  // Partículas (suaves)
  if (window.particlesJS) {
    particlesJS('particles-js',{
      particles:{ number:{value:55}, color:{value:'#6a5acd'},
        shape:{type:'circle'}, opacity:{value:0.20},
        size:{value:2.5, random:true},
        line_linked:{enable:true, distance:150, color:'#6a5acd', opacity:0.12, width:1},
        move:{enable:true, speed:1.4}
      },
      interactivity:{events:{onhover:{enable:true, mode:'repulse'}}},
      retina_detect:true
    });
  }
  if (typeof AOS !== 'undefined') { AOS.init({ duration:800, easing:'ease-out', once:true, offset:80 }); }

  // Buscador: no toca innerHTML, solo data-*; añade clase 'matched' y cuenta
  (function(){
    var input = document.getElementById('search');
    var clearBtn = document.getElementById('clearBtn');
    var cards = document.querySelectorAll('#cards .col');
    var matchCount = document.getElementById('matchCount');

    function norm(s){ return (s||'').toString().toLowerCase(); }

    function apply(){
      var q = norm(input.value);
      var shown = 0;
      for (var i=0;i<cards.length;i++){
        var el = cards[i];
        el.classList.remove('matched');
        if (!q){
          el.style.display = '';
          shown++;
          continue;
        }
        var n = el.getAttribute('data-name');
        var u = el.getAttribute('data-url');
        var d = el.getAttribute('data-desc');
        var ok = (n && n.indexOf(q)>-1) || (u && u.indexOf(q)>-1) || (d && d.indexOf(q)>-1);
        el.style.display = ok ? '' : 'none';
        if (ok){ el.classList.add('matched'); shown++; }
      }
      if (matchCount){
        matchCount.textContent = q ? (shown + ' coincidencia' + (shown===1?'':'s')) : 'Mostrando todos';
      }
    }
    if (input){ input.addEventListener('input', apply, false); }
    if (clearBtn){
      clearBtn.addEventListener('click', function(){ input.value=''; apply(); input.focus(); }, false);
    }
  })();
</script>

</body>
</html>
