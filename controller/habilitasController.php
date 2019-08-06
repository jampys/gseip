<?php

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view->disableLayout=false;


switch ($operation)
{

    case 'connection':
        //$view->puesto = new Puesto();
        //$rta=$view->puesto->autocompletarPuestos($_POST['term']);
        //print_r(json_encode($rta));
        //exit;



        try {

            if (
                !isset($_FILES['fileToUpload']['error']) ||
                is_array($_FILES['fileToUpload']['error'])
            ) {
                throw new RuntimeException('Invalid parameters.');
            }



            // Check $_FILES['upfile']['error'] value.
            switch ($_FILES['fileToUpload']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }



            // You should also check filesize here.
            if ($_FILES['fileToUpload']['size'] > 1000000) {
                throw new RuntimeException('Exceeded filesize limit.');
            }

            $allowed_types = array ('text/x-fortran', 'text/plain');
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $detected_type = finfo_file( $fileInfo, $_FILES['fileToUpload']['tmp_name'] );
            if ( !in_array($detected_type, $allowed_types) ) {
                throw new RuntimeException('Solo archivos de texto.');
            }
            finfo_close( $fileInfo );




            $a = array();
            $view->rta = array();
            $counter = 0;
//$file = fopen("uploads/files/SEIP 1016 Julio.txt", "r") or exit("Unable to open file!");
//$file = $_FILES['fileToUpload']['tmp_name'];

            if (is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {


                $file = fopen($_FILES['fileToUpload']['tmp_name'], "rb");

                while (!feof($file)) {
                    //echo fgets($file). "<br />";
                    $a[] = fgets($file);
                    //echo $line."<br />";
                    //if ($line[1] == "Contrato") echo $line[0]."<br />";


                }
                fclose($file);


                foreach ($a as $k => $v) {
                    // $array[3] se actualizará con cada valor de $array...
                    $line_1 = explode(" ", $a[$k]);
                    //$line_2 = explode(" ", $a[$k+1]);
                    //echo is_numeric($line_1[0]). "<br />";
                    //print_r($array);
                    if ($line_1[1] == "Contrato") {
                        $counter += 1;
                        //echo $line_1[0];

                        $b = array();
                        $b = array_slice($a, $k + 1, 20);

                        foreach ($b as $k1 => $v1) {
                            $line_2 = explode(" ", $b[$k1]);
                            // $array[3] se actualizará con cada valor de $array...
                            //$line_1 = explode(" ", $a[$k]);
                            //$line_2 = explode(" ", $a[$k+1]);
                            //echo is_numeric($line_1[0]). "<br />";
                            //print_r($array);
                            //if ($line_1[1] == "Contrato") echo $line_1[0];
                            if (is_numeric($line_2[0]) && is_numeric($line_2[1])) {
                                /*echo " " . $line_2[1] .
                                    " " . $line_2[4] .
                                    " " . $line_2[6] .
                                    " " . $line_2[7] .
                                    "<br />";*/
                                $view->rta[]= array('habilita'=> $line_1[0],
                                    'ot'=> $line_1[0],
                                    'cantidad'=> $line_1[0],
                                    'unitario'=> $line_1[0],
                                    'importe'=> $line_1[0]
                                );
                                break;
                            } else {
                                continue;
                            }


                        }


                    }


                }




//cantidad un. pr unitario importe


            }

        }catch (RuntimeException $e) {

            echo $e->getMessage();

        }









        $view->disableLayout=false;
        $view->contentTemplate="view/habilitas/habilitasForm.php";
        break;

    default : //ok
        //$view->empleados=Empleado::getEmpleados();
        $view->contentTemplate="view/habilitas/habilitasGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/habilitas/habilitasLayout.php');
}


?>
