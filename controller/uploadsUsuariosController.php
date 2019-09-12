<?php
include_once("model/usuariosModel.php");

if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$output_dir = $GLOBALS['ini']['upload_dir']."profile_pictures/";


switch ($operation) {

    case 'load': //ok

        $id = $_POST['id'];
        $files = Usuario::uploadsLoad($id);

        $ret= array();

        foreach($files as $file){

            $filePath = $file['profile_picture']; //$file['directory']."/".$file['name'];
            if(!file_exists($filePath)){
                $custom_error= array();
                $custom_error['jquery-upload-file-error']= "No se encuentra el archivo en el servidor.";
                $custom_error['name'] = $file['profile_picture']; //$file['name'];
                $ret[] = $custom_error;
                continue; //salta el loop
            }

            $details = array();
            $details['name'] = $file['profile_picture']; //$file['name'];
            $details['path'] = $file['profile_picture']; //$filePath;
            $details['size'] = filesize($filePath);
            $details['fecha'] = $file['fecha'];
            $ret[] = $details;

        }

        echo json_encode($ret);
        break;

    case 'upload': //ok

        $id = $_POST['id']; //con este id (id_renovacion) hago el insert de los uploads en la BD

        if(isset($_FILES["myfile"])) {

            $ret = array();

            // Manejo de errores: Si hay un error con el archivo subido o el directorio destino no existe...
            if($_FILES['myfile']['error']!= 0 || !is_dir($output_dir)){ 
                $custom_error= array();
                $custom_error['jquery-upload-file-error'] = "Error al subir el archivo.";
                echo json_encode($custom_error);
                die();
            }

            //You need to handle  both cases
            //If Any browser does not support serializing of multiple files using FormData()
            if(!is_array($_FILES["myfile"]["name"])) //single file
            {
                $temp = explode(".", $_FILES["myfile"]["name"]);
                $temp1 = preg_replace('/\s+/', '_', $temp[0]); //reemplaza en el nombre del archivo los espacios en blanco por '_'
                $newfilename = str_pad($id, 5, 0, STR_PAD_LEFT) . '_' . $temp1 . '_' .round(microtime(true)) . '.' . end($temp);
                if(move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . $newfilename))
                    Usuario::uploadsUpload($output_dir, $newfilename, $id); //inserta en la BD
                $ret[]= $newfilename;
            }
            else  //Multiple files, file[]
            {
                $fileCount = count($_FILES["myfile"]["name"]);
                for($i=0; $i < $fileCount; $i++)
                {
                    $temp = explode(".", $_FILES["myfile"]["name"][$i]);
                    $temp1 = preg_replace('/\s+/', '_', $temp[0]); //reemplaza en el nombre del archivo los espacios en blanco por '_'
                    $newfilename = str_pad($id, 5, 0, STR_PAD_LEFT) . '_' . $temp1 . '_' .round(microtime(true)) . '.' . end($temp);
                    if (move_uploaded_file($_FILES["myfile"]["tmp_name"][$i], $output_dir . $newfilename))
                        Usuario::uploadsUpload($output_dir, $newfilename, $id); //inserta en la BD
                    $ret[] = $newfilename;
                }

            }
            echo json_encode($ret);


        }


        break;

    case 'download':

        if(isset($_GET['filename']))
        {
            $fileName = $_GET['filename'];
            $fileName = str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
            $file = $fileName; //$output_dir.$fileName;
            $file = str_replace("..","",$file);
            if (file_exists($file)) {
                $fileName = str_replace(" ","",$fileName);
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename='.$fileName);
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                exit;
            }

        }

        break;


    case 'delete': //ok

        if( isset($_POST['name']) && isset($_POST['id'])) {

            if($_POST['name'] == 'uploads/profile_pictures/default.png') break; //la foto por defecto nunca se elimina

            $fileName =$_POST['name'];
            $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
            $filePath = $fileName; //$output_dir. $fileName;

            if (file_exists($filePath)) {
                unlink($filePath);
                Usuario::uploadsDelete($_POST['id']); //Borra el registro de la BD
            }
        }

        break;


    default :
        break;
}




?>
