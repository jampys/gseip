<?php
include_once("model/habilidadesModel.php");
$id = $_POST['id'];
$files = Habilidad::getHabilidadesImg($id);

$ret= array();


foreach($files as $file){

    $filePath = $file['directory']."/".$file['name'];
    if(!file_exists($filePath)) //Si el archivo no existe, saltea el loop
        continue;

    $details = array();
    $details['name'] = $file['name'];
    $details['path'] = $filePath;
    $details['size'] = filesize($filePath);
    $ret[] = $details;

}

//echo json_encode('ahhhhh');
echo json_encode($ret);




?>