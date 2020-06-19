<?php

if(!isset($_POST['search'])) exit('No se recibió el valor a buscar');

require_once 'db_config.php';

function search()
{
    $db = DbConfig::getConnection();
    $search = $db->real_escape_string($_POST['search']);
    $query = "SELECT id, nombre FROM medico WHERE nombre LIKE '%$search%'";
    $res = $db->query($query);
    while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
        echo "<p><a href='./ver_medicos.php?page=1&q=$row[id]' target='_blank'>$row[nombre]</a></p>";
    }
}

search();