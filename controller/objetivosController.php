<?php

include_once("model/objetivosModel.php");

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        $view->puestos = Puesto::getPuestos();
        $view->contentTemplate="view/puestosGrid.php";
        break;

    case 'savePuesto':
        $puesto = new Puesto($_POST['id_puesto']);
        $puesto->setNombre($_POST['nombre']);
        $puesto->setDescripcion($_POST['descripcion']);
        $puesto->setCodigo($_POST['codigo']);
        $puesto->setCodigoSuperior(($_POST['codigo_superior'])? $_POST['codigo_superior'] : null);

        $rta = $puesto->save();
        print_r(json_encode($rta));
        exit;
        break;

    case 'newObjetivo': //ok
        $view->objetivo = new Objetivo();
        $view->label='Nuevo objetivo';

        $view->superior = Objetivo::getObjetivos();

        $view->disableLayout=true;
        $view->contentTemplate="view/objetivosForm.php";
        break;

    case 'editObjetivo': //ok
        $view->label='Editar Objetivo';
        $view->objetivo = new Objetivo($_POST['id_objetivo']);

        $view->superior = Objetivo::getObjetivos();

        $view->disableLayout=true;
        $view->contentTemplate="view/objetivosForm.php";
        break;

    case 'deletePuesto':
        $puesto = new Puesto($_POST['id_puesto']);
        $rta = $puesto->deletePuesto();
        print_r(json_encode($rta));
        die;
        break;

    case 'autocompletarPuestos':
        $view->puesto = new Puesto();
        $rta=$view->puesto->autocompletarPuestos($_POST['term']);
        print_r(json_encode($rta));
        exit;
        break;

    default : //ok
        $view->objetivos = Objetivo::getObjetivos();
        $view->contentTemplate="view/objetivosGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/objetivosLayout.php');
}


?>
