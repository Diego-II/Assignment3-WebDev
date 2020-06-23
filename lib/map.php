<?php
require_once 'db_config.php';
require_once 'remove_special_chars.php';
require_once 'ver_docs_aux.php';

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
                $query_doc = "SELECT id FROM medico WHERE comuna_id = (SELECT id FROM comuna WHERE nombre='$temp')";
                $result = $db->query($query_doc);
                $com = array();
                $docs = array();
                while($row = $result->fetch_array(MYSQLI_BOTH)){
                    $com[] = $row['id'];
                    $docs[] = getOneDoc($db, $row['id']);
                }
                $comuna_doc_coord[] = array(
                    'lng' => $value['lng'],
                    'lat' => $value['lat'],
                    'name' => $temp,
                    'docs' => $docs
                );
            }
        }
    }
    return json_encode($comuna_doc_coord);
}
