<?php

include_once("model/objetivosModel.php");

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        $view->puestos = Objetivo::getObjetivos();
        $view->contentTemplate="view/objetivosGrid.php";
        break;

    case 'saveObjetivo': //ok
        $puesto = new Objetivo($_POST['id_objetivo']);
        $puesto->setNombre($_POST['nombre']);
        $puesto->setTipo($_POST['tipo']);
        $puesto->setObjetivoSuperior(($_POST['objetivo_superior'])? $_POST['objetivo_superior'] : null);

        $rta = $objetivo->save();
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
