<?php
include_once("model/vencimientos_personalModel.php");

if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];


switch ($operation) {
    
    case 'load':
        $id = $_POST['id'];
        $files = Vencimiento_personal::uploadsLoad($id);

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

        break;

    case 'upload':

        break;

    case 'download':

        break;

    case 'delete':

        break;



    default :

        break;
}

/*if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);}
else {
    include_once('view/empleadosLayout.php');
}*/


?>
