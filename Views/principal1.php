
<?php

//echo $data;
//echo $data;
//$sql ="ola z";
/* 
"Extra1"=> $document["extra_1"],
"Extra2"=> $document["extra_2"],

echo $_SESSION['id_ztrack'] ;
echo $_SESSION['usuario_ztrack'] ;
echo $_SESSION['apellidos_ztrack'] ;
echo $_SESSION['nombres_ztrack'] ;
echo $_SESSION['correo_ztrack'] ;
echo $_SESSION['c_f_ztrack'] ;
echo $_SESSION['empresa_ztrack'] ;
*/
$sql = $_SESSION['id_ztrack'].",".$_SESSION['c_f_ztrack'] .",".$_SESSION['empresa_ztrack'] ; 
echo $sql ;
//$url1 = "http://148.102.22.93:2531/api/ot/";
$url1 = "http://161.132.206.104/apizgroup/datamaduradores1/";
//$url1 = "http://localhost:8080/apizgroup/dataMaduradores/";
$datos = [
    "sql" => $sql,
];
$opciones = array(
    "http" => array(
        "header" => "Content-type: application/json\r\n",
        "method" => "POST",
        "content" => json_encode($datos), # Agregar el contenido definido antes
    ),
);
# Preparar petición
$contexto = stream_context_create($opciones);
$resultadoEX = file_get_contents($url1, false, $contexto);
//$data1 = json_decode($data);
$data1 = json_decode($resultadoEX);
//echo $resultadoEX;




?>


<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DemoCarrusel</title>
    <link rel="stylesheet" href="<?php echo base_url ; ?>Assets/Principal/css/styles.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

<style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }

      .btn-bd-primary {
        --bd-violet-bg: #712cf9;
        --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

        --bs-btn-font-weight: 600;
        --bs-btn-color: var(--bs-white);
        --bs-btn-bg: var(--bd-violet-bg);
        --bs-btn-border-color: var(--bd-violet-bg);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-hover-bg: #6528e0;
        --bs-btn-hover-border-color: #6528e0;
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
        --bs-btn-active-color: var(--bs-btn-hover-color);
        --bs-btn-active-bg: #5a23c8;
        --bs-btn-active-border-color: #5a23c8;
      }

      .bd-mode-toggle {
        z-index: 1500;
      }

      .bd-mode-toggle .dropdown-menu .active .bi {
        display: block !important;
      }

	  
 @media (max-width: 577px) {
  .carousel-inner .carousel-item > div {
    display: none;
  }
  .carousel-inner .carousel-item > div:first-child {
    display: block;
  }
}

.carousel-inner .carousel-item.active,
.carousel-inner .carousel-item-start,
.carousel-inner .carousel-item-next,
.carousel-inner .carousel-item-prev {
  display: flex;
  // transition-duration: 10s;
}

/* display 4 */
@media (min-width: 577px) {
  .carousel-inner .carousel-item-right.active,
  .carousel-inner .carousel-item-next,
  .carousel-item-next:not(.carousel-item-start) {
    transform: translateX(25%) !important;

  }

  .carousel-inner .carousel-item-left.active,
  .carousel-item-prev:not(.carousel-item-end),
  .active.carousel-item-start,
  .carousel-item-prev:not(.carousel-item-end) {
    transform: translateX(-25%) !important;

  }

  .carousel-item-next.carousel-item-start, .active.carousel-item-end {
    transform: translateX(0) !important;
  }

  .carousel-inner .carousel-item-prev,
  .carousel-item-prev:not(.carousel-item-end) {
    transform: translateX(-25%) !important;

  }
} 
.carousel-control-prev,
.carousel-control-next {
  background-color: #e1e1e1;
  width: 6vh;
  height: 6vh;
  border-radius: 50%;
  top: 50%;
  transform: translateY(-50%);
}
.carousel-control-prev span,
.carousel-control-next span {
  width: 1.5rem;
  height: 1.5rem;
}
	  
    </style>
</head>

<body>
    <header data-bs-theme="dark">
        <div class="collapse text-bg-dark" id="navbarHeader">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-md-7 py-4">
                        <h4>About</h4>
                        <p class="text-body-secondary">
                            Add some information about the album below, the author, or any
                            other background context. Make it a few sentences long so folks
                            can pick up some informative tidbits. Then, link them off to
                            some social networking sites or contact information.
                        </p>
                    </div>
                    <div class="col-sm-4 offset-md-1 py-4">
                        <h4>Contact</h4>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-white">Follow on Twitter</a></li>
                            <li><a href="#" class="text-white">Like on Facebook</a></li>
                            <li><a href="#" class="text-white">Email me</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a href="<?php echo base_url; ?>Principal/salir" class="navbar-brand d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="me-2" viewBox="0 0 24 24">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                        <circle cx="12" cy="13" r="4" />
                    </svg>
                    <strong>Album</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </header>
    <main>
        <section class="py-3 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-7 col-md-9 mx-auto">
                    <h1 class="fw-light">Sistema de control y monitoreo en vivo</h1>
                </div>
            </div>
        </section>


        <div class="container2"> 
	<div id="myCarousel" class="carousel slide container2" data-bs-touch="true" data-bs-interval="false">
	  <div class="carousel-inner w-100" role="listbox">
		<div class="carousel-item">
		  <div class="col-sm-6 col-md-6 col-lg-4 mb-4">
			  <div class="card">
          <div class="card card-body"><!-- <img class="img-fluid" src="http://placehold.it/380?text=1">--></div>
        </div>
      </div>
    </div>


    <?php
		$i=0;
	  foreach($data1 as $row){
		$activo='carousel-item';
		if($i == 0){
			$activo ='carousel-item active';
		}else{
			$activo='carousel-item';
		}	
	  ?>
		<div class="<?= $activo; ?>">
            <div class="col-sm-6 col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-headers" style="color: black ;height: 80px;">
                        <div class="row iconos-s">
                            <div class="col-4">
                                <i class="fa-solid fa-power-off icon" id="power" style="color: green"></i>
                            </div>
                            <div class="col-4">
                                <img src="<?php echo base_url ; ?>Assets/principal/img/f.png" alt="" width="100 px" />
                            </div>
                            <div class="col-4">
                                <i class="fa-solid fa-rotate-right icon" style="color: orange"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body1 py-2">
                        <span id=""><?= $row->Date."-".$row->Extra1 ?></span>
                        <h5 class="card-title py-1 titulo-cuadro" >ID: <?= $row->NombreContenedor ?> </h5>
                        <h5 class="card-title py-2 subtitulo-cuadro">ALIAS: <?= $row->Descripcion ?></h5>
                        <div class="d-flex justify-content-center align-items-center centrar-iconos">
                            <div class="btn-group justify-content-between align-items-center">
                                <?php  $acumulado =  $row->NombreContenedor.",".$row->Extra1.",".$_SESSION['c_f_ztrack'] ;  ?>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="abrirGrafica('<?= $acumulado ?>')">
                                    <i class="fa-solid fa-chart-line"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa-solid fa-database"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success">
                                    <i class="fa-solid fa-envelope"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger">
                                    <i class="fa-regular fa-file-pdf"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive cargaInicial">
                            <table  class="table table-bordered border-dark tablas align-items-center tabla" id="Datos">
                                <thead>
                                    <tr style="background-color: black;">
                                        <th class="text-title zoom">Param</th>
                                        <th class="text-title">Set</th>
                                        <th class="text-title zoom2">Actual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="iconos">
                                            <div class="icon_P">
                                                <i class="fa-solid fa-temperature-half"></i>
                                            </div>
                                        </th>
                                        <td>
                                             <div class="input-group mb-2 inputmodificado">
                                                <input type="text" class="form-control" aria-label="temperatura" value="<?= $row->SetPoint ?>" readonly />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-secondary">
                                                <span >S : <?= $row->TempSupply1?> ' C°</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="iconos">
                                            <div class="icon_P">
                                               <i class="fa-solid fa-cloud-rain"></i>
                                            </div>
                                        </th>
                                        <td>
                                            <div class="input-group mb-2 inputmodificado">
                                                <input type="text" class="form-control" value="<?= $row->HumiditySetPoint ?>" readonly />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-secondary">
                                                <span >R: <?= $row->ReturnAir ?>  %</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="iconos">
                                            <div class="icon_P " style="margin-top: 10px;">CO2</div>
                                        </th>
                                        <td>
                                            <div class="input-group mb-2 inputmodificado">
                                                <input type="text" class="form-control" value="<?= $row->SetPointCo2 ?>" readonly />
                                            </div>
                                        </td>
                                        <td class="text-secondary">
                                            <div class="div">
                                                <span > <?= $row->Co2Reading ?> %</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="iconos">
                                            <div class="icon_P" style="margin-top: 10px;">C2H2</div>
                                        </th>                                   
                                        <td>
                                            <div class="input-group mb-2 inputmodificado">
                                                <input type="text" class="form-control" value="<?= $row->SetEthyleno ?>" readonly />
                                            </div>
                                        </td>
                                        <td class="text-secondary">
                                            <span > <?= $row->Ethylene ?> ppm</span>
                                        </td>
                                    </tr>                     
                                    <tr>
                                        <th class="iconos">
                                            <div class="icon_P">
                                                <i class="fa-solid fa-syringe icon_P"></i>
                                            </div>
                                        </th>
                                        <td>
                                            <div class="input-group mb-2 inputmodificado">
                                                <input type="text" class="form-control"  id="ultima_fecha" readonly />
                                            </div>
                                        </td>
                                        <td class="text-secondary">
                                            <span >Horas</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div id="datosExtras'.$row['id'].'" class="datos-div" ></div>
                            <div class="card-footers">
                                <div class="expandir" >
                                    <button type="button" onclick="#"  class="btn redondo" data-id="1" ><i class="fa-solid fa-chevron-down"></i></button>
                                </div>
                            </div>
                        </div>

            </div>
		  </div>
		</div>
		<?php 
		$i++;
		}
		?>
	  </div>
	  <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Previous</span>
	  </button>
	  <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Next</span>
	  </button>
	</div>
</div>
    


        
    </main>
    <footer class="text-body-secondary py-5">
        <div class="container justify-content-center">


        </div>
    </footer>
    <script>
        $("#power").click(function() {
            $("#power").removeClass("color: green");
            $("#power").addClass("color:red");

        });

        $('.carousel .carousel-item').each(function(){
    var minPerSlide = 3;
    var next = $(this).next();
    if (!next.length) {
    next = $(this).siblings(':first');
    }
    next.children(':first-child').clone().appendTo($(this));
    
    for (var i=0;i<minPerSlide;i++) {
        next=next.next();
        if (!next.length) {
        	next = $(this).siblings(':first');
      	}
        
        next.children(':first-child').clone().appendTo($(this));
      }
});
    </script>
    <script src="views/assets/js/script.js"></script>
    <script src="views/assets/js/MostarTablas.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0 "></script>
</div>
    <div id="interfazGrafica" class="modal modal-xl fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="title">Data Dispositivo</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <h1 align="center" id="tituloGrafica">Hola peru</h1>
            <canvas align ="center" id="graficaFinal" style="" width="600" height="400"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
const grafica1 = document.getElementById("graficaFinal");
const tituloGrafica = document.getElementById("tituloGrafica");


 function graficaMadurador1(info){

    if (typeof w !== 'undefined') {w.destroy();}
    setPoint =[];
    returnAir = [];
    tempSupply =[];
    ambienteAir =[];
    relativeHumidity =[];
    evaporationCoil =[];
    D_ethylene = [];
    co2 = [];
    sp_ethylene =[];
    fecha =[];
    inyeccionEtileno = [];
 
    contador =0;

    const datosInyeccion ={
        label : " PowerState",
        data : info.powerState,
        backgroundColor: '#f7f2e2', // Color de fondo
        borderColor: '#f7f2e2', // Color del borde
        borderWidth: 1,
        yAxisID : 'y1',
        pointRadius: 0,
        cubicInterpolationMode: 'monotone',
        tension: 0.4 ,
        fill: true,  
        datalabels: {
            //display: 'false',  
            labels: {
                title: null
              } 
          },
    }


    const datosEvaporationCoil ={
        label : " Evap",
        data : info.evaporationCoil,
        backgroundColor: '#95a5a6', // Color de fondo
        borderColor: '#95a5a6', // Color del borde
        borderWidth: 3,
        yAxisID : 'y',
        pointRadius: 0,
        cubicInterpolationMode: 'monotone',
        tension: 0.4 ,
        hidden :true,
        datalabels: {
            //display: 'false', 
            labels: {
                title: null
              } 
          },
    }

    const datosSetPoint ={
        label : " SetPoint",
        data : info.setPoint,
        backgroundColor: '#f1c40f', // Color de fondo
        borderColor: '#f1c40f', // Color del borde
        borderWidth: 3,
        yAxisID : 'y',
        pointRadius: 0,
        cubicInterpolationMode: 'monotone',
        tension: 0.4 ,
        hidden :false,
        datalabels: {
            //display: 'false', 
            labels: {
                title: 'terrible'
              }  
          },
    }
    const datosreturnAir ={
        label : " Return ",
        data : info.returnAir,
        backgroundColor: '#ec7063', // Color de fondo
        borderColor: '#ec7063', // Color del borde
        borderWidth: 3,// Ancho del borde
        yAxisID : 'y',
        pointRadius: 0,
        cubicInterpolationMode: 'monotone',
        tension: 0.4,
        hidden :false,
        datalabels: {
            display: 'auto',
            clip :'true',
            clamp :'true',
            align: 'end',   
          },
    }
    const datostempSupply ={
        label : " Supply",
        data : info.tempSupply,
        backgroundColor: '#27ae60', // Color de fondo
        borderColor: '#27ae60', // Color del borde
        borderWidth: 3,// Ancho del borde
        yAxisID : 'y',
        pointRadius : 0,
        cubicInterpolationMode: 'monotone',
        tension: 0.4,
        hidden :false,
        datalabels: {
            display: 'auto',
            clip :'true',
            clamp :'true',
            align: 'end',   
          },
    }
    const datosambienteAir ={
        label : " Ambient",
        data : info.ambienteAir,
        backgroundColor: '#9ccc65', // Color de fondo
        borderColor: '#9ccc65', // Color del borde
        borderWidth: 3,// Ancho del borde
        yAxisID : 'y',
        pointRadius : 0,
        cubicInterpolationMode: 'monotone',
        tension: 0.4,
        hidden :false,
        datalabels: {
            display: 'auto',
            clip :'true',
            clamp :'true',
            align: 'end',   
          },
    }
    const datorelativeHumidity ={
        label : "Humidity",
        data : info.relativeHumidity,
        backgroundColor: '#e4c1f4', // Color de fondo
        borderColor: '#e4c1f4', // Color del borde4476c6
        borderWidth: 3,// Ancho del borde
        yAxisID : 'y1',
        pointRadius : 0,
        cubicInterpolationMode: 'monotone',
        tension: 0.4,
        hidden :false,
        datalabels: {
            display: 'auto',
            clip :'true',
            clamp :'true',
            align: 'end',   
          },
    }


    console.time('loop');
    w = new Chart(grafica1, {
        type: 'line',// Tipo de gráfica
        data: {
            labels: info.fecha,
            datasets: [
                datosreturnAir,
                    datorelativeHumidity,
                    datosambienteAir,
                    datostempSupply,
                    datosEvaporationCoil, 
                    datosSetPoint ,
                    datosInyeccion 
        
                    // Aquí más datos...
            ]
        },
        options: {
            animation: {

              },
            responsive : true,
            backgroundColor: '#fff',
            interaction :{
                mode : 'index',
                intersect :false,
            },
            stacked :false,
            scales: {
                y: {
                    position: 'left',
                    display: true,
                    title: {
                        display: true,
                        text: 'Temperature (C°)',
                        color: '#1a2c4e',
                        font: {     
                            size: 20,
                            style: 'normal',
                            lineHeight: 1.2
                        },
                        padding: {top: 30, left: 0, right: 0, bottom: 0}
                    },
                    suggestedMin: 0,
                    suggestedMax: 30
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Percentage (%)',
                        color: '#1a2c4e',
                        font: {                      
                            size: 20,
                            style: 'normal',
                            lineHeight: 1.2
                        },
                        padding: {top: 30, left: 0, right: 0, bottom: 0}
                    },
                    grid: {
                        drawOnChartArea: false, // only want the grid lines for one axis to show up
                    },
                    suggestedMin: 0,
                    suggestedMax: 100,
                },
            },
            plugins: {
                datalabels: {
                    color: function(context) {
                      return context.dataset.backgroundColor;
                    },
                    font: {
                      weight: 'bold'
                    },          
                    padding: 6,
    
                  },
                title: {
                    display: true,
                    text: '',
                    color: '#1a2c4e',
                    font: {                        
                        size: 35,
                        style: 'normal',
                        lineHeight: 1.2
                    },
                    padding: {top: 30, left: 0, right: 0, bottom: 0}
                },
                zoom: {
                    pan :{
                        enabled :true,
                        mode: 'x',
                    },
                    zoom: {
                        wheel: {
                            enabled: true,
                        },
                        pinch: {
                            enabled: true
                        },
                        mode: 'x',
                        drag :{
                            enabled: false,
                        },
                        scaleMode :'x',
                    }
                },
                customCanvasBackgroundColor : {
                    color :'#fff',
                },
                legend : {
                    position :'top',
                    align : 'center',
                    labels : {
                        boxWidth :20 ,
                        boxHeight : 20,
                        color :'#1a2c4e',
                        padding :15 ,
                        textAlign : 'left',
                        font: {
                            size: 12,
                            style: 'normal',
                            lineHeight: 1.2
                          },
                        title : {
                            text :'Datos Graficados:',
                        },
                    },
                },
            }           
        },
       // plugins : [plugin,ChartDataLabels],
    });    
    console.timeEnd('loop');
}

function abrirGrafica(codigo) {
    //document.getElementById("title").textContent = "Actualizar Receta";
    //document.getElementById("btnAccion").textContent = "Modificar";

    
// aqui tenemos la fecha en texto
fechaTotal = Date();
//aqui buscamos la posicion de GMT
posFecha =fechaTotal.search("GMT");
//AQUI CAPTURAMOS el uso  horario , le sumamos 3 la longitud de GMT y 5 la longitud de la hora
cadenaGMT = fechaTotal.substr(posFecha+3,5);
console.log(cadenaGMT);
    codigo1 = codigo+","+cadenaGMT;
    varia = codigo.split(",");
    console.log(varia[0]);
    if(varia[0]=="ZGRU1090804"){
         url = "http://161.132.206.104/apizgroup/secuencia.php?data=" + codigo1;
    }else{
         url = "http://161.132.206.104/apizgroup/data12horas/index.php?data=" + codigo1;
    }
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            //const res = this.responseText;

           const res = JSON.parse(this.responseText);
           //graficaMadurador1(res, codigo);
            /*
            document.getElementById("id").value = res.id;
            document.getElementById("codigo_receta").value = res.codigo_receta;
            document.getElementById("nombre_receta").value = res.nombre_receta;
            document.getElementById("descripcion_receta").value = res.descripcion;                
            */
           console.log(res);
           //console.log(codigo);
           //terrible =res[codigo];
           //console.log(terrible);
            graficaMadurador1(res);
            console.log(tituloGrafica.textContent);
            tituloGrafica.textContent = "Monitoreo de Dispositivo : "+res.device;
            $("#interfazGrafica").modal("show");
        }
    }
}

</script>
</body>

</html>

