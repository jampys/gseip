<?php

include_once("model/objetivosModel.php");

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        //$view->periodos = Objetivo::getPeriodos();
        $periodo = (isset($_POST['periodo']))? $_POST['periodo'] : Soporte::getPeriodoActual();
        $view->objetivos = Objetivo::getObjetivos($periodo);
        $view->contentTemplate="view/objetivosGrid.php";
        break;

    case 'saveObjetivo':
        $objetivo = new Objetivo($_POST['id_objetivo']);
        $objetivo->setNombre($_POST['nombre']);
        $objetivo->setTipo($_POST['tipo']);
        $objetivo->setObjetivoSuperior(($_POST['objetivo_superior'])? $_POST['objetivo_superior'] : null);

        $rta = $objetivo->save();
        print_r(json_encode($rta));
        exit;
        break;

    case 'newObjetivo':
        $view->objetivo = new Objetivo();
        $view->label='Nuevo objetivo';

        //$view->superior = Objetivo::getObjetivos();
        //$view->tipos = Soporte::get_enum_values('objetivos', 'tipo');

        $view->disableLayout=true;
        $view->contentTemplate="view/objetivosForm.php";
        break;

    case 'editObjetivo':
        $view->label='Editar Objetivo';
        $view->objetivo = new Objetivo($_POST['id_objetivo']);

        $view->superior = Objetivo::getObjetivos();
        $view->tipos = Soporte::get_enum_values('objetivos', 'tipo');

        $view->disableLayout=true;
        $view->contentTemplate="view/objetivosForm.php";
        break;

    case 'deleteObjetivo':
        $objetivo = new Objetivo($_POST['id_objetivo']);
        $rta = $objetivo->deleteObjetivo();
        print_r(json_encode($rta));
        die;
        break;

    case 'autocompletarObjetivos':
        $view->objetivo = new Objetivo();
        $rta=$view->objetivo->autocompletarObjetivos($_POST['term']);
        print_r(json_encode($rta));
        exit;
        break;

    default : //ok
        $view->periodos = Objetivo::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->objetivos = Objetivo::getObjetivos($view->periodo_actual);
        $view->contentTemplate="view/objetivosGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/objetivosLayout.php');
}


?>
