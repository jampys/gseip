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
