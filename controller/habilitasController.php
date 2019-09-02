<?php
include_once("model/habilitasModel.php");

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
                throw new RuntimeException('Error al cargar archivo.');
            }



            // Check $_FILES['upfile']['error'] value.
            switch ($_FILES['fileToUpload']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No ha seleccionado ningún archivo.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Tamaño maximo de archivo excedido.');
                default:
                    throw new RuntimeException('Error desconocido.');
            }



            // You should also check filesize here.
            if ($_FILES['fileToUpload']['size'] > 1000000) {
                throw new RuntimeException('Tamaño maximo de archivo excedido.');
            }

            $allowed_types = array ('text/x-fortran', 'text/plain');
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $detected_type = finfo_file( $fileInfo, $_FILES['fileToUpload']['tmp_name'] );
            if ( !in_array($detected_type, $allowed_types) ) {
                throw new RuntimeException('Solo se permiten archivos de texto.');
            }
            finfo_close( $fileInfo );




            $a = array();
            $view->rta = array();
            $view->datos = array();
            $view->datos["counter"] = 0;
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

                //obtengo los datos para el encabezado
                $c = array_slice($a, 0, 20);

                foreach ($c as $k1 => $v1) {
                    $line = explode(" ", $c[$k1]);

                    if ($line[0] == 'CENTRO') {
                        $view->datos['centro']= $line[2];
                        //break;
                    }


                    if ($line[7] == 'Fecha' || $line[7] == 'Fecha:' ) {
                        $view->datos['fecha']= $line[8];
                        //break;
                    }


                    if ($line[0] == 'Cantidad' && $line[3] == 'detalle' ) {
                        $view->datos['items']= $line[5];
                        break;
                    }

                    // https://stackoverflow.com/questions/2109325/how-do-i-strip-all-spaces-out-of-a-string-in-php?rq=1
                    if (substr(str_replace(' ', '', $c[$k1]), 0, 11) == 'CERTIFICADO') {
                        $view->datos['certificado']=
                            substr(preg_replace('/\s+/', '', $c[$k1]), -10);
                        //break;
                    }

                }



                //Comienzo a recorrer las lineas
                foreach ($a as $k => $v) {
                    // $array[3] se actualizará con cada valor de $array...
                    $line_1 = explode(" ", $a[$k]);
                    //$line_2 = explode(" ", $a[$k+1]);
                    //echo is_numeric($line_1[0]). "<br />";
                    //print_r($array);
                    if ($line_1[1] == "Contrato") {
                        //$counter += 1;
                        //echo $line_1[0];

                        $b = array();
                        $b = array_slice($a, $k + 1, 30);

                        foreach ($b as $k1 => $v1) {
                            $line_2 = explode(" ", $b[$k1]);

                            if($line_2[0] == 'Subtotal') break;

                            // $array[3] se actualizará con cada valor de $array...
                            //$line_1 = explode(" ", $a[$k]);
                            //$line_2 = explode(" ", $a[$k+1]);
                            //echo is_numeric($line_1[0]). "<br />";
                            //print_r($array);
                            //if ($line_1[1] == "Contrato") echo $line_1[0];
                            if (is_numeric($line_2[0]) && is_numeric($line_2[1])
                                && ($line_2[5]=='UNI' || $line_2[5]=='MES' || $line_2[5]=='H')
                                && ($line_2[0]=='00010'
                                || $line_2[0]=='00020'
                                    || $line_2[0]=='00030'
                                    || $line_2[0]=='00040'
                                    || $line_2[0]=='00050'
                                    || $line_2[0]=='00060'
                                    || $line_2[0]=='00070'
                                    || $line_2[0]=='00080'
                                    || $line_2[0]=='00090'
                                    || $line_2[0]=='00100'
                                    || $line_2[0]=='00110'
                                    || $line_2[0]=='00120'
                                    || $line_2[0]=='00130'
                                    || $line_2[0]=='00140'
                                    || $line_2[0]=='00150'
                                    || $line_2[0]=='00160'
                                    || $line_2[0]=='00170'
                                    || $line_2[0]=='00180'
                                    || $line_2[0]=='00190'
                                    || $line_2[0]=='00200'
                                )
                                ) {
                                /*echo " " . $line_2[1] .
                                    " " . $line_2[4] .
                                    " " . $line_2[6] .
                                    " " . $line_2[7] .
                                    "<br />";*/
                                //$counter += 1;
                                $view->datos["counter"] +=1;
                                $view->rta[]= array('habilita'=> $line_1[0],
                                    'posicion'=> $line_2[0],
                                    'ot'=> $line_2[1],
                                    'cantidad'=> $line_2[4],
                                    'unitario'=> $line_2[6],
                                    'importe'=> $line_2[7]
                                );
                                //break;
                            } else {
                                continue;
                            }


                        }


                    }


                }

                $_SESSION['head'] = $view->datos;
                $_SESSION['cart'] = $view->rta;




//cantidad un. pr unitario importe


            }

        }catch (RuntimeException $e) {
            $view->resultado = -1;
            //echo $e->getMessage();
            $view->error_msg = $e->getMessage();

        }


        $view->disableLayout=false;
        $view->contentTemplate="view/habilitas/habilitasForm.php";
        break;


    case 'save':

            $rta = array();
            $rta['msg']= "";
            $rta['saved'] = 0;
            $rta['duplicates'] = 0;
            $rta['others'] = 0;
            $rta['items'] = $_SESSION['head']['items']; //total de items de la habilita
            $rta['counter'] = $_SESSION['head']['counter']; //total de items procesados de la habilita

            foreach ($_SESSION['cart'] as $rg) {

                try{

                    $habilita = new Habilita();
                    $habilita->setPosicion($rg['posicion']);
                    $habilita->setOt($rg['ot']);
                    $habilita->setHabilita($rg['habilita']);
                    $habilita->setCantidad($rg['cantidad']);
                    $habilita->setUnitario($rg['unitario']);
                    $habilita->setImporte($rg['importe']);
                    $habilita->setCentro($_SESSION['head']['centro']);
                    $habilita->setCertificado($_SESSION['head']['certificado']);
                    $habilita->setFecha($_SESSION['head']['fecha']);
                    $habilita->setCreatedBy($_SESSION['id_user']);
                    $habilita->save();
                    $rta['saved']++;

                }catch (PDOException $e){

                    if($e->errorInfo[1] == 1062) $rta['duplicates']++;
                    else $rta['others']++;

                    //print_r(json_encode(-1));

                }




            }



            $rta['msg']= "Registros guardados: ".$rta['saved']."</br>";
            $rta['msg'].= "Registros duplicados: ".$rta['duplicates']."</br>";
            $rta['msg'].= "Otros errores: ".$rta['others']."</br>";
            print_r(json_encode($rta));
            exit;
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
