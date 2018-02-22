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
        $view->msg_header = 'Error de conexión';
        $view->msg_error = 'Se ha producido un error de conexión con la Base de Datos o la red presenta algun inconveniente. </br>'.
                            'Intente nuevamente en un instante y si el problema persiste comuníquese con el administrador.';
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
