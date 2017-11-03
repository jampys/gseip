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

        $id = $_POST['id'];
        /* con este id hago el insert de las fotos en la BD */

        $output_dir = "uploads/";
        if(isset($_FILES["myfile"])) {

            $ret = array();

            //	This is for custom errors;
            /*	$custom_error= array();
                $custom_error['jquery-upload-file-error']="File already exists";
                echo json_encode($custom_error);
                die();
            */
            $error =$_FILES["myfile"]["error"];
            //You need to handle  both cases
            //If Any browser does not support serializing of multiple files using FormData()
            if(!is_array($_FILES["myfile"]["name"])) //single file
            {
                $fileName = $_FILES["myfile"]["name"];
                move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
                $ret[]= $fileName;
                //Agregar codigo para insertar en la BD
            }
            else  //Multiple files, file[]
            {
                $fileCount = count($_FILES["myfile"]["name"]);
                for($i=0; $i < $fileCount; $i++)
                {
                    $fileName = $_FILES["myfile"]["name"][$i];
                    move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
                    $ret[]= $fileName;
                    //Agregar codigo para insertar en la BD
                }

            }
            echo json_encode($ret);


        }


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

        $output_dir = "uploads/vto_vencimiento_p/";
        if( isset($_POST['name'])) {

            $fileName =$_POST['name'];
            $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
            $filePath = $output_dir. $fileName;

            if (file_exists($filePath)) {

                unlink($filePath);
                //Agregar codigo para borrar de la BD
            }
        }


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
