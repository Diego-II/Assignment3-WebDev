<?php 
require_once('lib/db_config.php');
require_once('lib/ver_sol_aux.php');

$db = DbConfig::getConnection();

$sql="SELECT MAX(id) FROM solicitud_atencion";
$resultado=$db->query($sql);
$ultimo_id = mysqli_fetch_array($resultado)["MAX(id)"];

if (isset($_GET["page"])) {
    $page  = $_GET["page"];
} else {
    $page=1;
}
if(isset($_GET["q"]) && $_GET["q"] !== ""){
    $sol_id = $_GET["q"];
    $sol_array = getNSol($db,$sol_id,$sol_id);
    $n_pages = 1;
    $page = 1;
}else{
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

    $n_pages = ceil($ultimo_id/5);


    $sol_array = getNSol($db, $start_from, $last_id);
}
$db -> close();

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Ver Solicitudes</title>
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
      <a href="ver_medicos.php?page=1" class="w3-bar-item w3-button w3-padding-large">Ver Medicos</a>
      <a href="agregar_medicos.html" class="w3-bar-item w3-button w3-padding-large">Agregar Medicos</a>
      <a href="agregar_solicitud.html" class="w3-bar-item w3-button w3-padding-large">Agregar Solicitud</a>
      <a href="ver_solicitudes.php?page=1" class="w3-bar-item w3-button w3-padding-large">Ver Solicitudes</a>
    </div>
  </div>
  <div class="w3-row-padding w3-padding-64 w3-container">
    <div class="w3-content">
        <h1>Ver Solicitudes</h1>
        <!-- Buscador de solicitudes: -->
        <div class="w3-row">
            <div class="w3-col m4 l3">
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon glyphicon-search" aria-hidden="true"></span></span>
                    <input type="text" class="form-control" id="search2" placeholder="Buscar Solicitud" onkeyup="search2();">
                </div>
            </div>
            <div class="w3-rest" id="result2">
            </div>
        </div>
        <table>
        <thead>
            <tr>
                <th>Nombre Solicitante</th>
                <th>Especialidades</th>
                <th>Comuna</th>
                <th>Datos contacto</th>
            </tr>
        </thead>
        <?php
        foreach($sol_array as $k => $sol){
        echo sprintf("
        <!--- non hidden rows -->
        <tr class='text-field-l' onclick=mostrarDosInfo(\"info0%d\",\"info1%d\");>
        <td>%s</td>
        <td>%s</td>
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
        <!--- Hidden Row: -->
        <tr class='text-field-l'>
            <td colspan='1'>
                <p  id='info0%d' style='display: none;'>
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
                </p>
            </td>
            <td colspan='3'>
                <p id='info1%d' style='display: none;'>
                    Informacion Adicional: 
                        <mark class='bold'>%s</mark>
                    <br><br>
                    Archivos adicionales (Para descargar): 
                    <mark class='bold'><br>%s</mark>
                </p>
            </td>
            </tr>  

        ", $k, $k, $sol["nombre-solicitante"], $sol["especialidad-solicitante"], $sol["comuna-solicitante"], $sol["celular-solicitante"], $sol["email-solicitante"], $sol["twitter-solicitante"], $k, $sol["nombre-solicitante"], $sol["region-solicitante"], $sol["comuna-solicitante"], $sol["especialidad-solicitante"], $sol["celular-solicitante"], $sol["email-solicitante"], $sol["twitter-solicitante"], $k, $sol["sintomas-solicitante"], getFilesNames($sol["files-path"], $sol["files-name"], $sol["files-mime"]));}
        echo "</table>";
        ?>
<table>
    <tr>
        <td>
            <a href = <?php echo getPrevPage()?>  style="text-align:left"> &lt;&lt;&lt; </a> 
        </td>
        <td  style="text-align:center"> <?php echo "Pagina ".$_GET["page"]."/".$n_pages;?> </td> 
        <td>
            <a href = <?php echo getNextPage($n_pages)?>  style="text-align:right"> >>> </a> 
        </td>
    <tr>
</table>
</div>
</div>

<script src="./js/jquery.min.js">
</script>
<script type="text/javascript" src="js/search_sol.js"></script>
</body>
</html>
