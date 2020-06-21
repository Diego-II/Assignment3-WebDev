<?php
require_once('lib/db_config.php');
require_once('lib/ver_docs_aux.php');

$db = DbConfig::getConnection();

$sql="SELECT MAX(id) FROM medico";
$resultado=$db->query($sql);
$ultimo_id = mysqli_fetch_array($resultado)["MAX(id)"];

if (isset($_GET["page"])) {
    $page  = $_GET["page"];
} else {
    $page=1;
}
if(isset($_GET["q"]) && $_GET["q"] !== ""){
    $doc_id = $_GET["q"];
    $doc_array = getNDocs($db, $doc_id, $doc_id);
    $n_pages = 1;
    $page = 1;
}else{
    $doc_id = $_GET["q"];
    if($page === 1){
        $start_from = 1;
    }else{
        $start_from = 1 + ($page -1) * 5;
    }
    if($start_from + 4 >= $ultimo_id){
        $last_id = $ultimo_id;
    }else{
        $last_id = $start_from + 4;
    }
    //Cantidad de paginas:
    $n_pages = ceil(($ultimo_id/5));
    $doc_array = getNDocs($db, $start_from, $last_id);
}
$db -> close();

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Ver Medicos</title>
        <link rel="stylesheet" href="css/estilos_docs.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <script src="js/ver_medicos.js"></script>
    </head>
<body>
    <!-- Navbar -->
<div class="w3-top">
    <div class="w3-bar w3-red w3-card w3-left-align w3-large">
      <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-red" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
      <a href="Index.html" class="w3-bar-item w3-button w3-padding-large w3-white">Home</a>
      <a href="ver_medicos.php?page=1" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Ver Medicos</a>
      <a href="agregar_medicos.html" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Agregar Medicos</a>
      <a href="agregar_solicitud.html" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Agregar Solicitud</a>
      <a href="ver_solicitudes.php?page=1" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Ver Solicitudes</a>
    </div>
  
    <!-- Navbar on small screens -->
    <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-large">
      <a href="ver_medicos.php" class="w3-bar-item w3-button w3-padding-large">Ver Medicos</a>
      <a href="agregar_medicos.html" class="w3-bar-item w3-button w3-padding-large">Agregar Medicos</a>
      <a href="agregar_solicitud.html" class="w3-bar-item w3-button w3-padding-large">Agregar Solicitud</a>
      <a href="ver_solicitudes.php?page=1" class="w3-bar-item w3-button w3-padding-large">Ver Solicitudes</a>
    </div>
  </div>

  <div class="w3-row-padding w3-padding-64 w3-container">
    <div class="w3-content">
        <h1>Ver Medicos</h1>
        <!-- Buscador de medicos: -->
        <div class="w3-row">
        <div class="w3-col m4 l3">
        <div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon glyphicon-search" aria-hidden="true"></span></span>
            <input type="text" class="form-control" id="search" placeholder="Buscar Medico" onkeyup="search();">
        </div>
        </div>
        <div class="w3-rest" id="result">
        </div>
        </div>
    <table>
        <thead>
            <tr>
                <th>Nombre Medico</th>
                <th>Especialidades</th>
                <th>Comuna</th>
                <th>Datos contacto</th>
            </tr>
        </thead>
        <?php
        //Iterete over doctors:
        foreach($doc_array as $k => $doc){
        echo sprintf("
        <tr class='text-field-l'  onclick=onClickActionDoc('mySlides%d','info1%d','info2%d')>        
        <td>%s</td>
            <td>%s
            </td>
            <td>%s</td>
            <td>
                <p>
                Tel: <mark class='bold'>%s</mark>
                <br>
                Mail: <mark class='bold'>%s</mark>
                <br>
                Twitter: <mark class='bold'>%s</mark>
                </p>
        </td>
        </tr>
        <tr class='text-field-l'>
                <td >
                    <div class='w3-content w3-display-container'  id='info1%d' style='display: none; text-align: center;'>%s<button class='w3-button w3-black w3-display-left' onclick=plusDivs(-1,\"mySlides%d\")>&#10094;</button>
                    <button class='w3-button w3-black w3-display-right' onclick=plusDivs(1,\"mySlides%d\")>&#10095;</button>
                    </div>
                </td>
                <td colspan='3'>
                <p  id='info2%d' style='display: none;'>
                    Nombre: <mark class='bold'>%s</mark> 
                    <br>
                    Region: <mark class='bold'>%s</mark>
                    <br>
                    Comuna: <mark class='bold'>%s</mark>
                    <br>
                    Especialidades: <mark class='bold'>%s</mark>
                    <br>
                    Tel: <mark class='bold'>%s</mark>
                    <br>
                    Mail: <mark class='bold'>%s</mark>
                    <br>
                    Twitter: <mark class='bold'>%s</mark>
                    <br>
                    Experiencia: <mark class='bold'>%s</mark>
                </p>
            </td>
        </tr>", $k, $k, $k, $doc["nombre-medico"], implode(', ', $doc["especialidad-medico"]), $doc["comuna-medico"], $doc["celular-medico"], $doc["email-medico"], $doc["twitter-medico"], $k, getFotosSlides($k, $doc["nombre-medico"], $doc["dir-fotos"], $doc["fotos-medico"]), $k, $k, $k, $doc["nombre-medico"], $doc["region-medico"], $doc["comuna-medico"], implode(', ', $doc["especialidad-medico"]), $doc["celular-medico"], $doc["email-medico"], $doc["twitter-medico"], $doc["experiencia-medico"]);}
        echo "</table>";
        ?>

<table>
    <tr>
        <td>
            <a href = <?php echo getPrevPage()?> > &lt;&lt;&lt; </a>
        </td>
        <td style="text-align:center"> <?php echo "Pagina ".$_GET["page"]."/".$n_pages;?> </td>
        <td>
            <a href = <?php echo getNextPage($n_pages)?>  > >>> </a>
        </td>
    <tr>
  </table>
</div>
</div>

<script src="./js/jquery.min.js">
</script>
<script type="text/javascript" src="js/search_doc.js"></script>
</body>
</html>