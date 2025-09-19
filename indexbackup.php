<?php
/**
 * index.php – Portal con animaciones para todos los elementos,
 * botones con efectos, títulos con entrada animada,
 * cards con distintos efectos AOS y capas bien apiladas
 * Compatible con PHP 5.4.31
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
	array('name'=>'Activar Volte Prepago / PostPago SV Másivo STBY','url'=>'http://172.16.68.252:888/monitoreos/activarvolteprepostSV/','desc'=>'Activar Volte Prepago / PostPago EDA SV'),
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

);
// Efectos AOS rotados para cada tarjeta
$aosTypes = ['fade-up','flip-left','zoom-in','slide-right','flip-right','fade-down'];
?><!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Syn's Animated Portal</title>

  <!-- Bootstrap, Icons, Animate.css, AOS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet"/>

  <!-- Fondo degradado y partículas -->
  <style>
    html, body {
      height:100%; margin:0; overflow-x:hidden;
      font-family:'Segoe UI',sans-serif;
      background: linear-gradient(270deg,#12161f,#1f1216,#161f12,#121f1f);
      background-size:800% 800%;
      animation:gradientBG 20s ease infinite;
      color:#e8e8e8;
    }
    @keyframes gradientBG {
      0%{background-position:0% 50%;}
      50%{background-position:100% 50%;}
      100%{background-position:0% 50%;}
    }
    /* Partículas por debajo de todo */
    #particles-js {
      position: fixed; top:0; left:0;
      width:100%; height:100%;
      z-index: -2;
    }
    /* Overlay semitransparente encima de partículas */
    #overlay {
      position: fixed; top:0; left:0;
      width:100%; height:100%;
      background: rgba(0,0,0,0.6);
      z-index: -1;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

  <!-- Portal Styles & Animaciones -->
  <style>
    :root {
      --accent-blue:#00bfff;
      --card-bg:rgba(28,31,38,0.85);
      --shadow:rgba(0,0,0,0.8);
    }
    /* Elementos que deben flotar sobre overlay */
    h1.portal-header,
    p.subheader,
    .row.mb-4,
    main.container {
      position: relative;
      z-index: 0; /* por encima de overlay (-1) */
    }
    h1.portal-header {
      font-size:3rem; text-align:center;
      margin:1rem 0 .25rem;
      color:var(--accent-blue);
      -webkit-text-stroke:1px #fff;
      text-shadow:0 0 8px var(--accent-blue),0 0 4px #fff;
      animation:animate__bounceInDown 1s both;
    }
    p.subheader {
      text-align:center; margin-bottom:1rem;
      font-size:1rem;
      text-shadow:0 0 4px #fff;
      animation:animate__fadeInUp 1s .2s both;
    }
    /* Buscador */
    #search {
      max-width:400px; margin:0 auto 2rem;
    }
    #search:focus {
      animation:animate__pulse 1s;
    }
    /* Tarjetas */
    .card-portal {
      position: relative;
      z-index: 0; /* también sobre overlay */
      background:var(--card-bg); border:none; border-radius:1rem;
      box-shadow:0 8px 30px var(--shadow);
      transition:transform .3s,box-shadow .3s;
    }
    .card-portal:hover {
      animation:animate__tada 1s both;
      transform:translateY(-6px) scale(1.02);
      box-shadow:0 12px 40px var(--shadow),0 0 10px var(--accent-blue);
    }
    .card-title {
      font-size:1.8rem; font-weight:700;
      color:var(--accent-blue);
      -webkit-text-stroke:.5px #fff;
      text-shadow:0 0 6px var(--accent-blue),0 0 3px #fff;
      animation:animate__fadeInDown .6s both;
    }
    .card-text small {
      display:block; word-break:break-all;
      color:#e8e8e8; margin-bottom:.3rem;
      animation:animate__fadeInUp .6s .3s both;
    }
    /* Botones */
    .btn-highlight {
      display:inline-flex; align-items:center;
      background:var(--accent-blue); color:#000;
      font-weight:600; text-transform:uppercase;
      padding:.6rem 1.6rem; border-radius:.3rem;
      box-shadow:0 0 8px var(--accent-blue),0 0 16px var(--accent-blue);
      transition:transform .2s,box-shadow .3s;
    }
    .btn-highlight:hover {
      animation:animate__rubberBand 1s both;
    }
    .btn-highlight:active {
      animation:animate__hinge 1s both;
    }
    .btn-highlight i {
      margin-right:.4rem;
      transition:transform .3s;
    }
    .btn-highlight:hover i {
      transform:rotate(20deg) scale(1.1);
    }
  </style>
</head>
<body>
  <!-- Capas de fondo -->
  <div id="particles-js"></div>
  <div id="overlay"></div>

  <!-- Header -->
  <h1 class="portal-header">Syn's Portal</h1>
  <p class="subheader">Haz clic en “Ir al sitio” o busca para filtrar y resaltar</p>

  <!-- Buscador -->
  <div class="row mb-4 justify-content-center" data-aos="zoom-in" data-aos-delay="200">
    <div class="col-auto">
      <input id="search" type="text" class="form-control" placeholder="🔍 Buscar…">
    </div>
  </div>

  <!-- Grid de tarjetas -->
  <main class="container">
    <div class="row gy-4 gx-4">
      <?php foreach ($sites as $i => $site):
        $type = $aosTypes[$i % count($aosTypes)];
      ?>
      <div class="col-12 col-sm-6 col-md-4"
           data-aos="<?= $type ?>"
           data-aos-delay="<?= 300 + $i * 100 ?>">
        <div class="card card-portal" data-bs-toggle="tooltip"
             title="<?= htmlspecialchars($site['desc'],ENT_QUOTES,'UTF-8') ?> — <?= htmlspecialchars($site['url'],ENT_QUOTES,'UTF-8') ?>">
          <div class="card-body text-center p-4">
            <h5 class="card-title"><?= htmlspecialchars($site['name'],ENT_QUOTES,'UTF-8') ?></h5>
            <p class="card-text">
              <small><?= htmlspecialchars($site['url'],ENT_QUOTES,'UTF-8') ?></small><br>
              <small class="text-muted"><?= htmlspecialchars($site['desc'],ENT_QUOTES,'UTF-8') ?></small>
            </p>
            <a href="<?= htmlspecialchars($site['url'],ENT_QUOTES,'UTF-8') ?>"
               target="_blank"
               class="btn-highlight"
               data-bs-toggle="tooltip"
               title="Ir a <?= htmlspecialchars($site['name'],ENT_QUOTES,'UTF-8') ?>">
              <i class="bi bi-box-arrow-up-right"></i> Ir al sitio
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </main>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    // Inicializar partículas
    particlesJS('particles-js',{
      particles:{number:{value:60},color:{value:'#00bfff'},shape:{type:'circle'},
        opacity:{value:0.2},size:{value:3},
        line_linked:{enable:true,distance:150,color:'#00bfff',opacity:0.1,width:1},
        move:{enable:true,speed:2}},
      interactivity:{events:{onhover:{enable:true,mode:'repulse'}}},
      retina_detect:true
    });

    // Inicializar AOS
    AOS.init({ offset:80, duration:800, easing:'ease-in-out', once:true });

    // Asegurar animaciones visibles
    document.querySelectorAll('[data-aos]').forEach(el=>el.classList.add('aos-animate'));

    // Tooltips Bootstrap
    Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      .forEach(el=>new bootstrap.Tooltip(el));

    // Buscador con resaltado
    document.getElementById('search').addEventListener('input',function(){
      var term=this.value.trim().toLowerCase();
      document.querySelectorAll('.col-12.col-sm-6.col-md-4').forEach(function(col){
        var t=col.querySelector('.card-title'),
            u=col.querySelector('.card-text small:first-child'),
            d=col.querySelector('.card-text small.text-muted');
        [t,u,d].forEach(el=>el.innerHTML=el.textContent);
        var txt=(t.textContent+' '+u.textContent+' '+d.textContent).toLowerCase();
        if(!term||txt.indexOf(term)>-1){
          col.style.display='';
          if(term){
            var rx=new RegExp('('+term.replace(/[-\/\\^$*+?.()|[\]{}]/g,'\\$&')+')','gi');
            [t,u,d].forEach(el=>el.innerHTML=el.textContent.replace(rx,'<mark>$1</mark>'));
          }
        } else col.style.display='none';
      });
    });
  </script>
</body>
</html>
