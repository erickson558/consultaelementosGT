<?php
/**
 * Portal con animaciones de carga, hover y click sin perder funcionalidad.
 * PHP 5.4 compatible (JS ES5).
 */
$sites = array(
  array('name'=>'Dump Cablemodems Axess','url'=>'http://172.16.68.252:888/monitoreos/dump_axess/index.php','desc'=>'Monitoreo de Dump Axess en tiempo real'),
  array('name'=>'Crear Usuarios Dump','url'=>'http://172.16.68.252:888/monitoreos/usuariosdump/index.php','desc'=>'Listado y gestión de usuarios Dump'),
  array('name'=>'Comandos EDA','url'=>'http://172.16.68.252:888/monitoreos/comandos_eda.php','desc'=>'Panel de comandos EDA y su historial'),
  array('name'=>'Verificador IMSISGT','url'=>'http://172.16.68.252:888/monitoreos/verificadorimsisgt/verificador.php','desc'=>'Herramienta de verificación IMSISGT'),
  array('name'=>'ProcLog Analyzer CENAM Timeout','url'=>'http://172.16.68.252:888/monitoreos/procloganalizer/progloganalizer.php','desc'=>'Analizador de logs de ProgLog timeout'),
  array('name'=>'ProcLog Filter CENAM ','url'=>'http://172.16.68.252:888/monitoreos/proclogfilter/proclogfilter.php','desc'=>'Analizador de logs de ProcLog'),
  array('name'=>'Volte Analyzer CENAM','url'=>'http://172.16.68.252:888/monitoreos/consultaelementosGT/voltanalizer.php','desc'=>'Inspector de elementos VoLTE'),
  array('name'=>'Monitor de Host','url'=>'http://172.16.68.252:5000/','desc'=>'Dashboard de monitor de host'),
  array('name'=>'Alarms ARH Log Analyzer','url'=>'http://172.16.68.252:888/monitoreos/alarmslogeda/alarmloganalizer.php','desc'=>'Análisis de logs de alarmas ARH'),
  array('name'=>'Crear Velocidades AXESS','url'=>'http://172.16.68.252:5555/','desc'=>'Generador de perfiles de velocidad AXESS'),
  array('name'=>'Text Compare','url'=>'http://172.16.68.252:888/monitoreos/textcompare/','desc'=>'Comparador de textos en línea'),
  array('name'=>'Abrir Casos ITS','url'=>'https://helpdesk.itsinfocom.com/front/ticket.php','desc'=>'Portal de tickets ITSINFOCOM'),
  array('name'=>'Monitor de Tquerys GAIA','url'=>'http://172.16.68.252:888/monitoreos/monitoreotquerys/monitoreotquerys.php','desc'=>'Monitoreo T-Querys GAIA'),
  array('name'=>'Crear Dump Axess las Velocidades CENAM','url'=>'http://172.16.68.252:888/monitoreos/dumpvelocidadesaxess/dumpvelocidadesaxess.php','desc'=>'Dump Axess Velocidades'),
  array('name'=>'Envío Masivo de Bajas HFC TV CENAM','url'=>'http://172.16.68.252:888/monitoreos/bajaclientesHFCTVCENAM/','desc'=>'Baja HFC TV CENAM'),
  array('name'=>'Envío Masivo Bajas HFC Internet CENAM','url'=>'http://172.16.68.252:888/monitoreos/bajaHFCInternet/','desc'=>'Bajas Internet HFC CENAM'),
  array('name'=>'Envío Masivo Bajas HFC Voz CENAM','url'=>'http://172.16.68.252:888/monitoreos/bajaHFCtelefonia/','desc'=>'Bajas Voz HFC CENAM'),
  array('name'=>'Envío Masivo Bajas HFC Voz e Internet CENAM','url'=>'http://172.16.68.252:888/monitoreos/bajaHFCtelefoniainternet/','desc'=>'Bajas Voz e Internet HFC CENAM'),
  array('name'=>'Envío Masivo Bajas Clientes DTH TV CENAM','url'=>'http://172.16.68.252:888/monitoreos/bajaclientesDTH/','desc'=>'Bajas DTH TV clientes CENAM'),
  array('name'=>'Envío Masivo Bajas de paquetes de TV DTH CENAM','url'=>'http://172.16.68.252:888/monitoreos/bajapaquetesDTH/','desc'=>'Bajas paquetes DTH TV CENAM'),
  array('name'=>'Generar Passwords aleatorios','url'=>'http://172.16.68.252:888/monitoreos/passwordgenerator/','desc'=>'Password Generator'),
  array('name'=>'Analizador de Service Model entre Ambientes','url'=>'http://172.16.68.252:888/monitoreos/comparaSM/','desc'=>'Analizador entre Versiones Service Model EDA'),
  array('name'=>'Activar Volte Prepago / Postpago SV Másivo STBY','url'=>'http://172.16.68.252:888/monitoreos/activarvolteprepostSV/','desc'=>'Activar Volte Prepago / Postpago EDA SV'),
  array('name'=>'Crear en FNR con PT3 EDA GT','url'=>'http://172.16.68.252:888/monitoreos/createfnr3GT/','desc'=>'Crear FNR PT3 EDA GT'),
  array('name'=>'Crear Prepagos EDA GT','url'=>'http://172.16.68.252:888/monitoreos/crearprepagoGT/','desc'=>'Crear prepago GT'),
  array('name'=>'Agregar nuevos Pool CMTS AXESS','url'=>'http://172.16.68.252:888/monitoreos/addpoolCMAXESS/','desc'=>'Agregar Rangos IPs CMTS'),
  array('name'=>'Suspensión, Reactivación, y Cambio de Velocidad HFC AXESS CENAM','url'=>'http://172.16.68.252:888/monitoreos/usecaseAXESSCENAM/','desc'=>'Usecase AXESS HFC CENAM'),
  array('name'=>'Activar VoLTE Prepago / Postpago NI STBY','url'=>'http://172.16.68.252:888/monitoreos/activarvolteprepostNI/','desc'=>'Activar VOLTE Prepaid / PostPaid NI STBY'),
  array('name'=>'Bloqueo Servicios ADD 92 GT / Postpago NI STBY','url'=>'http://172.16.68.252:888/monitoreos/bloquearmovilGT/','desc'=>'Bloqueo Servicios GT add service 92'),
  array('name'=>'MultiFilter Proclog CENAM','url'=>'http://172.16.68.252:888/monitoreos/filterproclog/','desc'=>'MultiFilter Proclog CENAM Filtar varios campos'),
  array('name'=>'Reset VLR GT Masivo','url'=>'http://172.16.68.252:888/monitoreos/resetVLRGT/','desc'=>'Reset VLR GT masivo'),
  array('name'=>'Resultado de Consulta Masiva GT','url'=>'http://172.16.68.252:888/monitoreos/consultaHLRGTresultado/','desc'=>'Resultado de Consulta Masiva GT'),
  array('name'=>'Monitor de Logs de Procesos Automáticos','url'=>'http://172.16.68.252:888/monitoreos/monitorlogs/','desc'=>'Monitor Logs automáticos'),
  array('name'=>'Ver los SM Instalados en cada EDA CENAM','url'=>'http://172.16.68.252:888/monitoreos/monitorEDACENAM/','desc'=>'Ver los SM Instalados EDA CENAM'),
  array('name'=>'Trim Duplicados para SERVICES EDA CENAM','url'=>'http://172.16.68.252:888/monitoreos/trimduplicados/','desc'=>'Trim Duplicados para services EDA CENAM'),

);
$aosTypes = array('fade-up','flip-left','zoom-in','slide-right','flip-right','fade-down');
?><!doctype html>
<html lang="es" data-theme="dark">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Syn's Animated Portal Most Wanted</title>

<!-- Font local GTA -->
<style>
@font-face{
  font-family:'SanAndreasGTA';
  src:url('fonts/san-andreas.ttf') format('truetype');
  font-weight:normal; font-style:normal; font-display:swap;
}
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

<style>
:root{
  --accent:#6a5acd; --accent2:#06b6d4;
  --bg:#0f1117; --text:#eef1f8; --muted:#a7b1c2; --card:rgba(22,26,38,.75);
  --shadow:rgba(0,0,0,.9); --url:#c5d2e3; --mark:rgba(255,238,0,.55);
  --ribbon1:#7c6aff; --ribbon2:#00d0ff; --particle:#6a5acd;
  --title-fill:#fff; --title-stroke:#000; --subtitle-fill:#fff;
  --e1:60ms; --e2:140ms; --e3:260ms;
}
html[data-theme="light"]{
  --bg:#f6f8ff; --text:#0b1121; --muted:#4b5567; --card:rgba(255,255,255,.78);
  --shadow:rgba(0,0,0,.25); --url:#2e4a86; --mark:rgba(255,210,0,.45);
  --ribbon1:#4f46e5; --ribbon2:#06b6d4; --particle:#4f46e5;
  --title-fill:#0b1121; --title-stroke:#fff; --subtitle-fill:#0b1121;
}

/* Respeto accesibilidad */
@media (prefers-reduced-motion: reduce){
  *{animation-duration:.001ms !important;animation-iteration-count:1 !important;transition-duration:0ms !important;}
}

/* ===== Fondo animado suave ===== */
body{
  margin:0; overflow-x:hidden; color:var(--text);
  background:
    radial-gradient(1200px 600px at 90% -10%, rgba(106,90,205,.18), transparent 50%),
    linear-gradient(120deg, rgba(106,90,205,.12), rgba(6,182,212,.12), rgba(106,90,205,.12));
  animation:bgShift 18s ease-in-out infinite alternate;
  font-family:'Segoe UI',system-ui,Roboto,Arial,sans-serif;
  opacity:0; transform:translateY(10px); /* estado inicial */
}
@keyframes bgShift{0%{background-position:0% 0%,0% 0%}100%{background-position:100% 0%,100% 100%}}
body.ready{ opacity:1; transform:none; transition:opacity .6s ease, transform .6s ease }

/* Partículas / overlay */
#particles-js,#overlay{position:fixed; inset:0; pointer-events:none}
#particles-js{z-index:-2}
#overlay{z-index:-1;background:
  radial-gradient(1200px 700px at 10% 110%, rgba(6,182,212,.10), transparent 50%),
  radial-gradient(1000px 420px at 100% 0%, rgba(106,90,205,.15), transparent 60%);
}

/* ===== Header mount-in ===== */
.header-wrap{perspective:1200px; opacity:0; transform:translateY(-10px)}
body.ready .header-wrap{opacity:1; transform:none; transition:opacity .6s .12s, transform .6s .12s}
h1.portal-header{
  font-family:'SanAndreasGTA',serif; text-align:center; margin:18px 0 6px; font-size:3.2rem;
  color:var(--title-fill); -webkit-text-fill-color:var(--title-fill); -webkit-text-stroke:2px var(--title-stroke);
  text-shadow:0 2px 0 rgba(0,0,0,.55), 0 6px 14px rgba(0,0,0,.35); transform:translateZ(30px);
}
p.subheader{
  font-family:'SanAndreasGTA',serif; text-align:center; margin:0 0 18px; font-size:1.05rem;
  color:var(--subtitle-fill); -webkit-text-fill-color:var(--subtitle-fill);
  text-shadow:0 1px 0 rgba(0,0,0,.35), 0 3px 10px rgba(0,0,0,.25);
  position:relative; display:inline-block; left:50%; transform:translateX(-50%);
}
p.subheader::after{
  content:""; position:absolute; inset:0; background:linear-gradient(100deg, transparent 20%, rgba(255,255,255,.35) 50%, transparent 80%);
  transform:translateX(-120%); animation:subShine 6s ease-in-out infinite; mix-blend-mode:soft-light; border-radius:6px;
}
@keyframes subShine{0%{transform:translateX(-120%)}60%{transform:translateX(120%)}100%{transform:translateX(120%)}}

/* ===== Toolbar (mount-in + hover + click) ===== */
.toolbar{
  position:sticky; top:0; z-index:1030;
  background:linear-gradient(180deg, rgba(20,22,32,.75), rgba(20,22,32,.55));
  border-bottom:1px solid rgba(255,255,255,.08); backdrop-filter:blur(10px);
  opacity:0; transform:translateY(-8px);
}
html[data-theme="light"] .toolbar{background:linear-gradient(180deg, rgba(255,255,255,.75), rgba(255,255,255,.55)); border-bottom:1px solid rgba(0,0,0,.06)}
body.ready .toolbar{opacity:1; transform:none; transition:opacity .5s .2s, transform .5s .2s}
.toolbar .form-control{background:linear-gradient(180deg,#151a26,#121624); color:#e6edf3; border:1px solid #2a3145; border-radius:12px 0 0 12px}
html[data-theme="light"] .toolbar .form-control{background:linear-gradient(180deg,#eef2f8,#e8eef9); color:#0c1020; border:1px solid #c9d3e3}
.toolbar .form-control:focus{box-shadow:0 0 0 .2rem rgba(106,90,205,.25), 0 0 20px rgba(106,90,205,.25)}
.input-group-text{border-radius:12px 0 0 12px; border:1px solid #2a3145}

/* Botonera con visibilidad en ambos temas */
.toolbar .btn{
  border-radius:12px; border-color:var(--accent); color:var(--accent);
  background:transparent; transition:transform .15s ease, background .25s ease, color .25s ease, box-shadow .25s;
}
html[data-theme="dark"] .toolbar .btn{color:#efeaff; border-color:var(--accent)}
.toolbar .btn:hover{background:var(--accent); color:#000; transform:translateY(-1px); box-shadow:0 8px 20px rgba(106,90,205,.35)}
.toolbar .btn.tap{animation:btnTap .18s ease}
@keyframes btnTap{0%{transform:scale(.98)}70%{transform:scale(1.03)}100%{transform:scale(1)}}
.toolbar .btn i{display:inline-block; transition:transform .15s ease}
.toolbar .btn:hover i{transform:translateY(-1px) scale(1.05)}

/* ===== Cards (hover + click + shine) ===== */
.card-portal{
  position:relative; background:var(--card);
  border:1px solid rgba(255,255,255,.06); border-radius:18px;
  box-shadow:0 34px 70px -30px var(--shadow);
  transition: transform .22s ease, box-shadow .25s ease, border-color .25s ease, filter .25s ease;
  will-change: transform, box-shadow, filter; overflow:hidden; backdrop-filter: blur(6px);
}
.card-portal::before{
  content:""; position:absolute; inset:-1px; border-radius:20px; z-index:0;
  background:conic-gradient(from 140deg, rgba(106,90,205,.45), rgba(6,182,212,.45), rgba(106,90,205,.45));
  filter:blur(12px); opacity:.35; transition:opacity .25s ease, filter .25s ease;
}
.card-portal::after{
  content:""; position:absolute; top:-120%; left:-50%; width:220%; height:220%;
  background:linear-gradient(120deg, transparent 45%, rgba(255,255,255,.10) 50%, transparent 55%);
  transform:rotate(8deg); transition:transform .6s ease, opacity .35s ease; opacity:0;
}
.card-portal:hover{transform:translateY(-6px) scale(1.01); box-shadow:0 50px 120px -40px var(--shadow), 0 0 12px rgba(106,90,205,.45); border-color:rgba(255,255,255,.14)}
.card-portal:hover::before{opacity:.65; filter:blur(10px)}
.card-portal:hover::after{transform:translateX(10%) rotate(8deg); opacity:1}
.card-portal.press{animation:cardPress .2s ease}
@keyframes cardPress{0%{transform:scale(.99)}70%{transform:translateY(-3px) scale(1.01)}100%{transform:translateY(-1px) scale(1)}}

.tilt{transform-style:preserve-3d}
.tilt-inner{transform:translateZ(22px)}
.card-title{font-family:'SanAndreasGTA',serif; font-size:1.25rem; letter-spacing:.2px; text-shadow:0 0 6px rgba(106,90,205,.35); color:var(--text); transition:letter-spacing .2s ease}
.card-portal:hover .card-title{letter-spacing:.6px}
.card-url{color:var(--url); font-size:.9rem; word-break:break-all; opacity:.95}
.card-desc{color:var(--muted); line-height:1.35}

/* Ribbon match */
.ribbon{display:none; position:absolute; top:12px; right:-42px; transform:rotate(35deg);
  background:linear-gradient(90deg, var(--ribbon1), var(--ribbon2)); color:#fff; padding:2px 52px; font-size:.72rem; font-weight:800;
  letter-spacing:.35px; box-shadow:0 6px 14px -6px rgba(0,0,0,.6); z-index:5; border-radius:4px}
.matched .ribbon{display:block; animation:rbVibe 2.6s ease-in-out infinite}
@keyframes rbVibe{0%,100%{transform:rotate(35deg) translateY(0)}50%{transform:rotate(35deg) translateY(-1px)}}
.matched .card-portal{box-shadow:0 0 0 1px var(--accent), 0 22px 64px -18px rgba(106,90,205,.6)}

/* Densidad */
.compact .card-body{padding:1rem !important}
.compact .card-title{font-size:1.1rem}
.compact .btn-go{padding:.45rem 1rem}

/* ===== Botón Ir al sitio (hover + click) ===== */
.btn-go{
  position:relative; display:inline-flex; align-items:center; justify-content:center; gap:.5rem;
  font-weight:900; text-transform:uppercase; letter-spacing:.25px;
  padding:.65rem 1.2rem; border-radius:.75rem; border:0;
  background:transparent; color:#fff !important; -webkit-text-fill-color:#fff;
  isolation:isolate; z-index:1; line-height:1.15; mix-blend-mode:normal;
  box-shadow:0 0 10px rgba(106,90,205,.55), 0 0 26px rgba(106,90,205,.35);
  transition:transform .15s ease, filter .2s ease;
}
.btn-go::before{
  content:""; position:absolute; inset:0; border-radius:.75rem; z-index:0;
  background:linear-gradient(135deg, var(--accent), var(--accent2));
  transition:transform .18s ease, box-shadow .18s ease, filter .18s ease, opacity .2s ease;
}
.btn-go:hover::before,.btn-go:focus::before,.btn-go:active::before{transform:translateY(-1px); box-shadow:0 10px 24px rgba(90,80,210,.35), 0 0 24px rgba(6,182,212,.25)}
.btn-go:hover,.btn-go:focus{transform:translateY(-1px) scale(1.02)}
.btn-go.tap{animation:btnTap .18s ease}
.btn-go > *, .btn-go span, .btn-go i{position:relative !important; z-index:2 !important; color:#fff !important; -webkit-text-fill-color:#fff !important}
.btn-go .ripple{position:absolute; border-radius:50%; transform:scale(0); opacity:.55; background:rgba(255,255,255,.5); mix-blend-mode:screen; pointer-events:none; z-index:1}
@keyframes rippleAnim{to{transform:scale(16); opacity:0}}

/* Resaltado del buscador */
mark{background:var(--mark); padding:0 .2rem; border-radius:.25rem}

/* To Top */
#toTop{box-shadow:0 10px 26px rgba(0,0,0,.25)}
#toTop.pulse{animation:ttPulse 1.4s ease-in-out infinite}
@keyframes ttPulse{0%,100%{transform:translateY(0); box-shadow:0 10px 26px rgba(0,0,0,.25)}50%{transform:translateY(-2px); box-shadow:0 16px 32px rgba(0,0,0,.32)}}

/* 🔧 FIX: que los brillos/overlays no bloqueen el click del enlace */
.card-portal::before,
.card-portal::after,
.btn-go::before,
.btn-go .ripple {
  pointer-events: none;
}

/* Aseguramos el contenido clickeable arriba del overlay */
.btn-go,
.btn-go > *,
.btn-go span,
.btn-go i {
  position: relative;
  z-index: 2;
}
</style>
</head>
<body>

<div id="particles-js"></div>
<div id="overlay"></div>

<div class="header-wrap">
  <h1 class="portal-header">Syn's Portal</h1>
  <p class="subheader">Haz clic en “Ir al sitio” o usa el buscador (con resaltado)</p>
</div>

<!-- Toolbar -->
<div class="toolbar py-2">
  <div class="container">
    <div class="row g-2 align-items-center">
      <div class="col-12 col-md-6">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input id="search" type="text" class="form-control" placeholder="Buscar por nombre, URL o descripción…">
          <button id="btnClear" class="btn btn-outline-primary" type="button" title="Limpiar">Limpiar</button>
        </div>
      </div>
      <div class="col-12 col-md-6 text-md-end">
        <div class="btn-group me-2" role="group" aria-label="Orden">
          <button id="btnAZ"  class="btn btn-outline-primary" type="button" title="Orden A→Z"><i class="bi bi-sort-alpha-down"></i></button>
          <button id="btnZA"  class="btn btn-outline-primary" type="button" title="Orden Z→A"><i class="bi bi-sort-alpha-up"></i></button>
        </div>
        <div class="btn-group me-2" role="group" aria-label="Densidad">
          <button id="btnComfort" class="btn btn-outline-primary" type="button">Confort</button>
          <button id="btnCompact" class="btn btn-outline-primary" type="button">Compacta</button>
        </div>
        <div class="btn-group me-2" role="group" aria-label="Tema">
          <button id="btnTheme" class="btn btn-outline-primary" type="button" title="Cambiar tema">
            <i class="bi bi-moon-stars"></i> / <i class="bi bi-brightness-high"></i>
          </button>
        </div>
        <button id="btnReload" class="btn btn-outline-primary" type="button" title="Recargar lista (AJAX)">
          <i class="bi bi-arrow-repeat"></i> Recargar (AJAX)
        </button>
      </div>
    </div>
  </div>
</div>

<main class="container my-4">
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="cards">
    <?php foreach ($sites as $i => $site):
      $type = $aosTypes[$i % count($aosTypes)];
    ?>
    <div class="col" data-aos="<?php echo $type; ?>" data-aos-delay="<?php echo 160 + $i * 75; ?>">
      <div class="card h-100 card-portal tilt position-relative">
        <div class="ribbon">MATCH</div>
        <div class="card-body p-4 d-flex flex-column tilt-inner">
          <h5 class="card-title mb-2"><?php echo htmlspecialchars($site['name'],ENT_QUOTES,'UTF-8'); ?></h5>
          <div class="card-url mb-1"><?php echo htmlspecialchars($site['url'],ENT_QUOTES,'UTF-8'); ?></div>
          <div class="card-desc mb-3"><?php echo htmlspecialchars($site['desc'],ENT_QUOTES,'UTF-8'); ?></div>
          <div class="mt-auto">
            <a class="btn-go btn-sm" href="<?php echo htmlspecialchars($site['url'],ENT_QUOTES,'UTF-8'); ?>" target="_blank" rel="noopener">
              <i class="bi bi-box-arrow-up-right"></i> <span>Ir al sitio</span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</main>

<!-- Toast resultados -->
<div class="position-fixed bottom-0 start-50 translate-middle-x p-3" style="z-index:1080">
  <div id="toastCount" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body"><span id="toastMsg">Mostrando todos</span></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<button id="toTop" class="btn btn-primary rounded-pill" style="position:fixed;right:16px;bottom:16px;z-index:1040;display:none">
  <i class="bi bi-chevron-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
/* ===== Al montar: marcar ready para animaciones de entrada ===== */
document.addEventListener('DOMContentLoaded', function(){ document.body.className += (document.body.className?' ':'') + 'ready'; });

/* ===== Partículas según tema ===== */
function initParticles(){
  var color = getComputedStyle(document.documentElement).getPropertyValue('--particle').trim() || '#6a5acd';
  if (window.pJSDom && window.pJSDom.length){ try{ window.pJSDom[0].pJS.fn.vendors.destroypJS(); window.pJSDom = []; }catch(e){} }
  if (window.particlesJS){
    particlesJS('particles-js',{
      particles:{ number:{value:70}, color:{value:color}, shape:{type:'circle'},
        opacity:{value:0.22}, size:{value:3},
        line_linked:{enable:true,distance:150,color:color,opacity:0.12,width:1},
        move:{enable:true,speed:1.4}},
      interactivity:{events:{onhover:{enable:true,mode:'repulse'}}},
      retina_detect:true
    });
  }
}
initParticles();

/* ===== AOS para las cards ===== */
if (window.AOS){
  AOS.init({ offset:80, duration:720, easing:'ease-out', once:true, startEvent:'DOMContentLoaded' });
  setTimeout(function(){ try{ AOS.refresh(); AOS.refreshHard(); }catch(e){} }, 250);
}

/* ===== Revelado confiable (fallback ES5) ===== */
(function(){
  var items = Array.prototype.slice.call(document.querySelectorAll('[data-aos]'));
  function reveal(el){ if ((' '+el.className+' ').indexOf(' aos-animate ') === -1){ el.className += ' aos-animate'; } }
  function prewarm(){ var vh=(window.innerHeight||document.documentElement.clientHeight)*2; for(var i=0;i<items.length;i++){ var r=items[i].getBoundingClientRect(); if(r.top<vh) reveal(items[i]); } }
  if ('IntersectionObserver' in window){
    var io=new IntersectionObserver(function(entries){ for(var i=0;i<entries.length;i++){ if(entries[i].isIntersecting) reveal(entries[i].target); } }, {root:null, rootMargin:'120px 0px', threshold:0.01});
    for (var j=0;j<items.length;j++) io.observe(items[j]); prewarm();
  }else{
    function inView(el){ var r=el.getBoundingClientRect(); var vh=(window.innerHeight||document.documentElement.clientHeight); return (r.top<vh+120); }
    function tick(){ for (var i=0;i<items.length;i++) if (inView(items[i])) reveal(items[i]); }
    window.addEventListener('scroll',tick,false); window.addEventListener('resize',tick,false); prewarm(); tick();
  }
})();

/* ===== Tilt 3D ===== */
(function(){
  function bindTilt(scope){
    var cards=(scope||document).querySelectorAll('.tilt');
    for (var i=0;i<cards.length;i++){
      (function(card){
        function onMove(e){ var r=card.getBoundingClientRect(), cx=r.left+r.width/2, cy=r.top+r.height/2, dx=(e.clientX-cx)/r.width, dy=(e.clientY-cy)/r.height; card.style.transform='perspective(1000px) rotateX('+(dy*8)+'deg) rotateY('+(-dx*10)+'deg)'; }
        function reset(){ card.style.transform='perspective(1000px) rotateX(0) rotateY(0)'; }
        card.addEventListener('mousemove',onMove,false); card.addEventListener('mouseleave',reset,false);
      })(cards[i]);
    }
  }
  bindTilt(document);
})();

/* ===== Utils ===== */
function debounce(fn,ms){ var t; return function(){ var c=this,a=arguments; clearTimeout(t); t=setTimeout(function(){ fn.apply(c,a); }, ms||180); }; }
function normalize(s){ return (s||'').toString().toLowerCase(); }

/* ===== Buscador con ribbon + resaltado ===== */
(function(){
  var input=document.getElementById('search'), btnClear=document.getElementById('btnClear');
  var toastEl=document.getElementById('toastCount'), toastMsg=document.getElementById('toastMsg');
  var toast = toastEl ? new bootstrap.Toast(toastEl,{delay:1200}) : null;
  if(!input) return;

  var run=debounce(function(){
    var term=normalize(input.value.replace(/^\s+|\s+$/g,'')); 
    var cols=document.querySelectorAll('#cards .col'); var shown=0;
    for(var i=0;i<cols.length;i++){
      var col=cols[i], card=col.querySelector('.card-portal');
      var t=col.querySelector('.card-title'), u=col.querySelector('.card-url'), d=col.querySelector('.card-desc');
      if(t) t.innerHTML=t.textContent; if(u) u.innerHTML=u.textContent; if(d) d.innerHTML=d.textContent;
      var txt=((t?t.textContent:'')+' '+(u?u.textContent:'')+' '+(d?d.textContent:'')).toLowerCase();
      var ok=!term || (txt.indexOf(term)>-1);
      if (ok){ col.className=col.className.replace(/\bd-none\b/g,''); shown++; } else if (col.className.indexOf('d-none')===-1){ col.className+=' d-none'; }
      if (card){ if (ok && term){ if (col.className.indexOf('matched')===-1) col.className+=' matched'; } else { col.className=col.className.replace(/\bmatched\b/g,''); } }
      if (ok && term){
        var rx=new RegExp('('+term.replace(/[-\/\\^$*+?.()|[\]{}]/g,'\\$&')+')','gi');
        if(t) t.innerHTML=t.textContent.replace(rx,'<mark>$1</mark>');
        if(u) u.innerHTML=u.textContent.replace(rx,'<mark>$1</mark>');
        if(d) d.innerHTML=d.textContent.replace(rx,'<mark>$1</mark>');
      }
    }
    if (toast){ toastMsg.textContent = term ? (shown+' coincidencia'+(shown===1?'':'s')) : 'Mostrando todos'; toast.show(); }
    try{ AOS.refresh(); }catch(e){}
  }, 100);

  input.addEventListener('input', run, false);
  if(btnClear){ btnClear.addEventListener('click', function(){ input.value=''; run(); input.focus(); }, false); }
})();

/* ===== Orden ===== */
(function(){
  function sortCards(reverse){
    var row=document.getElementById('cards'); if(!row) return;
    var nodes=row.children, arr=[]; for(var i=0;i<nodes.length;i++){ arr.push(nodes[i]); }
    arr.sort(function(a,b){ var ta=a.querySelector('.card-title'), tb=b.querySelector('.card-title'); var A=(ta?ta.textContent:'').toLowerCase(), B=(tb?tb.textContent:'').toLowerCase(); return A<B ? (reverse?1:-1) : A>B ? (reverse?-1:1) : 0; });
    for(var j=0;j<arr.length;j++){ row.appendChild(arr[j]); }
    try{ AOS.refresh(); }catch(e){}
  }
  var btnAZ=document.getElementById('btnAZ'), btnZA=document.getElementById('btnZA');
  if(btnAZ) btnAZ.addEventListener('click', function(){ sortCards(false); this.classList.add('tap'); setTimeout((function(b){return function(){b.classList.remove('tap');};})(this), 220); }, false);
  if(btnZA) btnZA.addEventListener('click', function(){ sortCards(true);  this.classList.add('tap'); setTimeout((function(b){return function(){b.classList.remove('tap');};})(this), 220); }, false);
})();

/* ===== Densidad ===== */
(function(){
  var root=document.body, btnComfort=document.getElementById('btnComfort'), btnCompact=document.getElementById('btnCompact');
  if(btnComfort) btnComfort.addEventListener('click', function(){ root.className = root.className.replace(/\bcompact\b/g,''); this.classList.add('tap'); setTimeout((function(b){return function(){b.classList.remove('tap');};})(this), 220); }, false);
  if(btnCompact) btnCompact.addEventListener('click', function(){ if(root.className.indexOf('compact')===-1) root.className+=' compact'; this.classList.add('tap'); setTimeout((function(b){return function(){b.classList.remove('tap');};})(this), 220); }, false);
})();

/* ===== Botón Arriba ===== */
(function(){
  var btn=document.getElementById('toTop');
  function vis(){ var show=(window.scrollY||document.documentElement.scrollTop)>260; btn.style.display = show ? 'block':'none'; if(show){ btn.classList.add('pulse'); }else{ btn.classList.remove('pulse'); } }
  window.addEventListener('scroll', vis, false); vis();
  btn.addEventListener('click', function(){ window.scrollTo({top:0,left:0,behavior:'smooth'}); }, false);
})();

/* ===== Tema persistente ===== */
(function(){
  var root=document.documentElement, btn=document.getElementById('btnTheme');
  var saved=null; try{ saved=localStorage.getItem('portal-theme'); }catch(e){}
  if(saved==='light'||saved==='dark'){ root.setAttribute('data-theme', saved); initParticles(); }
  if(btn){ btn.addEventListener('click', function(){ var current=root.getAttribute('data-theme')||'dark'; var next=(current==='dark')?'light':'dark'; root.setAttribute('data-theme', next); try{ localStorage.setItem('portal-theme', next); }catch(e){} initParticles(); this.classList.add('tap'); setTimeout((function(b){return function(){b.classList.remove('tap');};})(this), 220); }, false); }
})();

/* ===== Ripple + tap en “Ir al sitio” y press en cards ===== */
(function(){
  // Ripple + tap-pop en los botones Ir al sitio
  var btns=document.getElementsByClassName('btn-go');
  for (var i=0;i<btns.length;i++){
    (function(btn){
      btn.addEventListener('click', function(e){
        var rect=btn.getBoundingClientRect(); var x=(e.clientX || (rect.left+rect.width/2)) - rect.left; var y=(e.clientY || (rect.top+rect.height/2)) - rect.top;
        var r=document.createElement('span'); r.className='ripple'; r.style.left=(x-10)+'px'; r.style.top=(y-10)+'px'; r.style.width=r.style.height='20px';
        btn.appendChild(r); r.offsetWidth; r.style.animation='rippleAnim 650ms ease-out forwards'; setTimeout(function(){ try{ btn.removeChild(r); }catch(ex){} }, 700);
        btn.classList.add('tap'); setTimeout(function(){ btn.classList.remove('tap'); }, 220);
      }, false);
    })(btns[i]);
  }
  // Press/release en cards
  var cards=document.getElementsByClassName('card-portal');
  function rm(el,c){ el.className = el.className.replace(new RegExp('\\b'+c+'\\b','g'),''); }
  for (var j=0;j<cards.length;j++){
    (function(card){
      card.addEventListener('mousedown', function(){ if(card.className.indexOf('press')===-1) card.className+=' press'; }, false);
      card.addEventListener('mouseup',   function(){ rm(card,'press'); }, false);
      card.addEventListener('mouseleave',function(){ rm(card,'press'); }, false);
      card.addEventListener('touchstart',function(){ if(card.className.indexOf('press')===-1) card.className+=' press'; }, false);
      card.addEventListener('touchend',  function(){ rm(card,'press'); }, false);
    })(cards[j]);
  }
})();

/* ===== AJAX opcional (sites.json) ===== */
(function(){
  function loadJSON(cb){
    try{
      var xhr=new XMLHttpRequest(); xhr.open('GET','sites.json',true);
      xhr.onreadystatechange=function(){
        if(xhr.readyState===4){
          if(xhr.status>=200 && xhr.status<300){ try{ var data=JSON.parse(xhr.responseText); cb(null,data); }catch(e){ cb(e); } }
          else{ cb(new Error('HTTP '+xhr.status)); }
        }
      };
      xhr.send();
    }catch(err){ cb(err); }
  }
  var btn=document.getElementById('btnReload');
  if(btn){ btn.addEventListener('click', function(){
    this.classList.add('tap');
    setTimeout((function(b){return function(){b.classList.remove('tap');};})(this), 220);
    loadJSON(function(err,data){
      if(!err && data && data.length){
        var row=document.getElementById('cards'); if(!row) return; row.innerHTML='';
        var aos=<?php echo json_encode($aosTypes); ?>;
        for(var i=0;i<data.length;i++){
          var s=data[i]; var name=(s.name||'').replace(/</g,'&lt;'), url=(s.url||'').replace(/</g,'&lt;'), desc=(s.desc||'').replace(/</g,'&lt;');
          var type=aos[i % aos.length];
          row.innerHTML += ''
           +'<div class="col" data-aos="'+type+'" data-aos-delay="'+(160 + i*75)+'">'
           +  '<div class="card h-100 card-portal tilt position-relative">'
           +    '<div class="ribbon">MATCH</div>'
           +    '<div class="card-body p-4 d-flex flex-column tilt-inner">'
           +      '<h5 class="card-title mb-2">'+name+'</h5>'
           +      '<div class="card-url mb-1">'+url+'</div>'
           +      '<div class="card-desc mb-3">'+desc+'</div>'
           +      '<div class="mt-auto"><a class="btn-go btn-sm" href="'+url+'" target="_blank" rel="noopener">'
           +      '<i class="bi bi-box-arrow-up-right"></i> <span>Ir al sitio</span></a></div>'
           +    '</div>'
           +  '</div>'
           +'</div>';
        }
        var items=row.querySelectorAll('[data-aos]'); for (var k=0;k<items.length;k++){ items[k].className+=' aos-animate'; }
        try{ AOS.refresh(); }catch(e){}
      }
    });
  }, false); }
})();
</script>
</body>
</html>
