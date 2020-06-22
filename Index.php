<?php
require_once 'lib/map.php';

$array_resultados = getDocsPins();
?>
<script>
    <?php
        //$js_array = json_encode($array_resultados);
        echo "var javascrypt_array = ". $array_resultados . ";\n";
    ?>
</script>

<!DOCTYPE html>
<html lang="en">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/estilos_docs.css">
        <title>Tarea 3</title>
    <head>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
              integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
              crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
                integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
                crossorigin=""></script>
        <script src="./js/jquery.min.js"></script>
        <script src="js/map.js"></script>
        <style>
            #mapid {
                height: 500px;
            }
        </style>
    </head>
<body>

<!-- Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-red w3-card w3-left-align w3-large">
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-red" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="Index.php" class="w3-bar-item w3-button w3-padding-large w3-white">Home</a>
    <a href="ver_medicos.php?page=1" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Ver Medicos</a>
    <a href="agregar_medicos.html" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Agregar Medicos</a>
    <a href="agregar_solicitud.html" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Agregar Solicitud</a>
    <a href="ver_solicitudes.php?page=1" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Ver Solicitudes</a>
  </div>

  <!-- Navbar on small screens -->
  <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-large">
    <a href="ver_medicos.php?page=1" class="w3-bar-item w3-button w3-padding-large">Ver Medicos</a>
    <a href="agregar_medicos.html" class="w3-bar-item w3-button w3-padding-large">Agregar Medicos</a>
    <a href="agregar_solicitud.html" class="w3-bar-item w3-button w3-padding-large">Agregar Solicitud</a>
    <a href="ver_solicitudes.php?page=1" class="w3-bar-item w3-button w3-padding-large">Ver Solicitudes</a>
  </div>
</div>

<!-- Header -->
<header class="w3-container w3-red w3-center" style="padding:128px 16px">
  <h1 class="w3-margin w3-jumbo">Registro Medico</h1>
  <p class="w3-xlarge">Registro web de medicos y solicitudes de atencion.</p>

        </div>
        <div id="mapid"></div>
        <script>
        var mymap = L.map('mapid').setView([-33.4372, -70.6506], 5);
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiZ2l0dXJyYSIsImEiOiJjanVoM3h2dWwwc3VpM3lzMDF2ZmVzb282In0.ZG2nvI23C9yUYuPzosxzFg', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox.streets',
        accessToken: 'your.mapbox.access.token'
        }).addTo(mymap);
        getDocsPins();
        </script>
</header>
<footer class="w3-container w3-padding-64 w3-center w3-opacity">
  Tildes y 'ñ' se omiten debido a que mi teclado es americano.
  <br>
  Templates CSS obtenidos de https://www.w3schools.com/w3css/4/w3.css
</footer>

<script>
// Used to toggle the menu on small screens when clicking on the menu button
function myFunction() {
  var x = document.getElementById("navDemo");
  if (x.className.indexOf("w3-show") === -1) {
    x.className += " w3-show";
  } else { 
    x.className = x.className.replace(" w3-show", "");
  }
}
</script>

</body>
</html>
