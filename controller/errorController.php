<?php

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view->disableLayout=false;


switch ($operation)
{

    case 'xxx':
        //$view->puesto = new Puesto();
        //$rta=$view->puesto->autocompletarPuestos($_POST['term']);
        //print_r(json_encode($rta));
        //exit;
        break;

    default : //ok
        $view->msg_header = 'Acceso denegado';
        $view->msg_error = $_SESSION['error'];
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/errorLayout.php');
}


?>
