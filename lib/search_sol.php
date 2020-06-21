<?php

if(!isset($_POST['search2'])) exit('No se recibió el valor a buscar');

require_once 'db_config.php';

function search2(){
    $db = DbConfig::getConnection();
    $search = $db -> real_escape_string($_POST['search2']);
    $query = "SELECT id, nombre_solicitante FROM solicitud_atencion WHERE nombre_solicitante LIKE '%$search%'";
    $res = $db->query($query);
    while ($row = $res->fetch_array(MYSQLI_ASSOC)){
        echo "<p><a href='./ver_solicitudes.php?page=1&q=$row[id]' target='_blank'>$row[nombre_solicitante]</a></p>";
    }
}

search2();