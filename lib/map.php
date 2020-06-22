<?php
require_once 'db_config.php';
require_once 'remove_special_chars.php';

//header('Content-Type: application/json');

function getDocsPins(){
    // Get the contents of the JSON file
    $strJsonFileContents = file_get_contents("json/comunas.json");
    // Convert to array
    $comunas = json_decode($strJsonFileContents, true);
    //var_dump($array);
    $db = DbConfig::getConnection();

    //Obtenemos las comunas de los docs:
    $query_comunas = "SELECT nombre FROM comuna WHERE id IN (SELECT comuna_id FROM medico)";
    $result = $db->query($query_comunas);

    while($row = $result->fetch_array(MYSQLI_BOTH)){
        $comunas_docs[] = $row;
    }
    $result -> free();

    foreach ($comunas as $key => $value){
        $temp = remove_accents($value["name"]);
        foreach ($comunas_docs as $k => $val){
            if (remove_accents($val["nombre"]) === $temp){
                $comuna_doc_coord[] = $value;
            }
        }
    }
    return json_encode($comuna_doc_coord);
}
