<?php
require_once('db_config.php');


/** Create 'files_solicitudes' directory: */
chdir("..");
$cd = getcwd();
if (!file_exists($cd . "/files_solicitudes")){
    mkdir($cd . "/files_solicitudes", "0777",true);
    chmod($cd . "/files_solicitudes", 0777);
}

$files_sol_dir = $cd."/files_solicitudes";

/**NON file data: 
 * Get and Validate:
*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["nombre-solicitante"])) {
        $nameErr = "Name requerido";
    } else {
        $nombreSol = test_input($_POST["nombre-solicitante"]);
        if (!preg_match("/^[a-zA-Z. ]*$/",$nombreSol)) {
            $nameErr = "Only letters, white space and points allowed";
          }
    }
    if (empty($_POST["region-solicitante"])) {
        $regionErr = "Region requerido";
    } else {
        $regionSol = test_input($_POST["region-solicitante"]);
    }
    if (empty($_POST["comuna-solicitante"])) {
        $comunaErr = "Comuna requerido";
    } else {
        $comunaSol = test_input($_POST["comuna-solicitante"]);
    }
    if (empty($_POST["especialidad-solicitante"])) {
        $espErr = "Especialidad requerido";
    } else {
        $espSol = test_input($_POST["especialidad-solicitante"]);
    }
    if (empty($_POST["celular-solicitante"])) {
        $celularErr = "Celular requerido";
    } else {
        $celSol = test_input($_POST["celular-solicitante"]);
        $numlength = strlen((string)$celSol);
        if($numlength !== 9){
            $celularErr = "Celular con incorrecta cantidad de digitos.";
        }
    }
    if (empty($_POST["email-solicitante"])) {
        $emailErr = "Email requerido";
    } else {
        $mailSol = test_input($_POST["email-solicitante"]);
        if (!filter_var($mailSol, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
          }
    }
    //optional
    $twitSol = test_input($_POST["twitter-solicitante"]);
    $sinSol = test_input($_POST["sintomas-solicitante"]);
}

/**Create dir for files: 
 * Only create dir if there's a file
*/
$nameDir = str_replace(' ', '', $nombreSol);
$cd = getcwd();
if (!file_exists($files_sol_dir . "/files_solicitudes/". $nameDir)){
    mkdir($files_sol_dir . "/files_solicitudes/". $nameDir, "0777", true);
    chmod($files_sol_dir . "/files_solicitudes/". $nameDir, 0777);
}
//One directory for each person
$target_dir =  $files_sol_dir."/". $nameDir. "/";


$files_array = array();
$msg = array();
$uploadOkArray = array();
$fileTypeArray = array();

foreach($_FILES as $file){
    if ($file["name"] == NULL){
    break;
    }
    /**Upload adicional files: */

    $target_file = $target_dir . basename($file["name"]);

    $fileTypeArray[] = $file["type"];

    array_push($files_array,basename($file["name"]));

    $uploadOk = 1;

    if(isset($_POST["submitSol"])){
        $check = filesize($file["tmp_name"]);
        if($check == false){
            $msg[] =  "Archivo ". basename( $file["name"]). " no es una imagen.";
            $uploadOk = 0;
        }else{
            $uploadOk = 1;
        }
    }
    if(file_exists($target_file)){
        $msg[] = "Archivo ". basename( $file["name"]). " ya existe";
        $uploadOk = 0;
    }
    if ($file["size"] > 25000000) {
        $msg[] = "Archivo ". basename( $file["name"]). " excede tamano permitido (25 [mb]).";
        $uploadOk = 0;
    }

    if(!$uploadOk){
        $msg[] = "Archivo ". basename( $file["name"]). " no fue subida.";
        $uploadOkArray[] = 0;
    } else{
        if (move_uploaded_file($file["tmp_name"], $target_file)){
            $msg[] = "Archivo ". basename( $file["name"]). " subido correctamente.";
            $uploadOkArray[] = 1;
        } else{
            $msg[] = "Archivo ". basename( $file["name"]). " no fue subido.";
            $uploadOkArray[] = 0;
        }
    }
}


$db = DbConfig::getConnection();
if(isset($_POST["submitSol"])){
    //insertSol(args);
    insertSol($db, $nombreSol, $comunaSol, $celSol, $mailSol, $twitSol, $sinSol, $espSol,$msg, $files_array, $uploadOkArray, $target_dir, $fileTypeArray);
}
$db->close();

function insertSol($db, $nombreSol, $comunaSol, $celSol, $mailSol, $twitSol, $sinSol, $espSol, $msg, $files_array, $okArray, $target_dir, $fileTypeArray){

    $find_especialidad = "SELECT id FROM especialidad WHERE descripcion LIKE '$espSol'";
    $result = $db->query($find_especialidad);
    $id_especialidad = mysqli_fetch_array($result)["id"];

    $find_comuna = "SELECT id FROM comuna WHERE nombre LIKE '$comunaSol'";
    $resultado = $db->query($find_comuna);
    $id_comuna = mysqli_fetch_array($resultado)["id"];

    //INSERT INTO solicitud_atencion (nombre_solicitante, especialidad_id, sintomas, twitter, email, celular, comuna_id) VALUES (?, ?, ?, ?, ?, ?, ?)
    $insertSolData = $db->prepare("INSERT INTO solicitud_atencion (nombre_solicitante, especialidad_id, sintomas, twitter, email, celular, comuna_id) 
    VALUES ('$nombreSol', '$id_especialidad', '$sinSol', '$twitSol', '$mailSol','$celSol', '$id_comuna')");

    $insertSolData->execute();

    $id_solicitante = $db->insert_id;

    foreach($files_array as $key => $value){
        //Insertamos solo si se cargo en el directorio media. 
        if($okArray[$key]){
            $insertSolFile=$db->prepare("INSERT INTO archivo_solicitud (ruta_archivo,nombre_archivo, mimetype, solicitud_atencion_id)
             VALUES ('$target_dir','$value', '$fileTypeArray[$key]', '$id_solicitante')");
		    $insertSolFile->execute();
        }
    }
    
    echo "<h1>Registro de solicitud completado.</h1><h2>Redireccionando a pagina inicial.</h2>";
    

    foreach($msg as $key => $value){
        echo $value, '<br>';
    }

    echo "<td><button onclick = \"location.href = 'Index.php';\">Volver a pagina inicial</button></td>";
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

?>
