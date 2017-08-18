<?php

include_once("model/puestosModel.php");

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        $view->puestos = Puesto::getPuestos();
        $view->contentTemplate="view/puestosGrid.php";
        break;

    case 'savePuesto': //ok
        $puesto = new Puesto($_POST['id_puesto']);
        $puesto->setNombre($_POST['nombre']);
        $puesto->setDescripcion($_POST['descripcion']);
        $puesto->setCodigo($_POST['codigo']);
        $puesto->setCodigoSuperior($_POST['codigo_superior']);

        $rta = $puesto->save();
        print_r(json_encode($rta));
        exit;
        break;

    case 'newPuesto': //ok
        $view->puesto = new Puesto();
        $view->label='Nuevo Puesto de trabajo';

        $view->superior = Puesto::getPuestos();

        $view->disableLayout=true;
        $view->contentTemplate="view/puestosForm.php";
        break;

    case 'editPuesto': //ok
        $view->label='Editar Puesto de trabajo';
        $view->puesto = new Puesto($_POST['id_puesto']);

        $view->superior = Puesto::getPuestos();

        $view->disableLayout=true;
        $view->contentTemplate="view/puestosForm.php";
        break;

    case 'deletePuesto': //ok
        $puesto = new Puesto($_POST['id_puesto']);
        $rta = $puesto->deletePuesto();
        print_r(json_encode($rta));
        die;
        break;

    default : //ok
        $view->puestos = Puesto::getPuestos();
        $view->contentTemplate="view/puestosGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/puestosLayout.php');
}


?>
