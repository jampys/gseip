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

        if(isset($_GET['filename']))
        {
            $fileName=$_GET['filename'];
            $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
            $file = "uploads/vto_vencimiento_p/".$fileName;
            $file = str_replace("..","",$file);
            if (file_exists($file)) {
                $fileName =str_replace(" ","",$fileName);
                header('Content-Description: File Transfer');
                //header('Content-Disposition: attachment; filename='.$fileName);
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                header('content-type: '.filetype($file));
                ob_clean();
                flush();
                readfile($file);
                exit;
            }

        }


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
