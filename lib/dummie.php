<?php
require_once 'db_config.php';
require_once 'ver_docs_aux.php';

$db = DbConfig::getConnection();

$query_comunas = "SELECT nombre FROM comuna WHERE id IN (SELECT comuna_id FROM medico)";
$result = $db->query($query_comunas);


$query_doc = "SELECT id FROM medico WHERE comuna_id = '13'";
$result = $db->query($query_doc);
$com = $result->fetch_array(MYSQLI_BOTH);
echo $com;
/**
foreach ($com as $k => $v){
    $doc[] = getOneDoc($db, $v);
}

var_dump($doc);
 */
