﻿<?php

include_once("model/vencimientos_personalModel.php");

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        $view->habilidades = Habilidad::getHabilidades();
        $view->contentTemplate="view/habilidadesGrid.php";
        break;

    case 'saveHabilidad':

        $habilidad = new Habilidad($_POST['id_habilidad']);
        $habilidad->setCodigo($_POST['codigo']);
        $habilidad->setNombre($_POST['nombre']);

        $rta = $habilidad->save();
        //print_r(json_encode($rta));
        echo sQuery::dpLastInsertId();
        //$ah = new sQuery();
        //echo "conexion comun: ".print_r(squery::dpLastInsertId() )."///";
        exit;
        break;

    case 'newRenovacion': //ok
        $view->renovacion = new Vencimiento_personal();
        $view->label='Nueva renovación';

        $view->disableLayout=true;
        $view->contentTemplate="view/vencimientos_personalForm.php";
        break;

    case 'editRenovacion': //ok
        $view->label='Editar Renovación';
        $view->renovacion = new Vencimiento_personal($_POST['id_renovacion']);

        $view->disableLayout=true;
        $view->contentTemplate="view/vencimientos_personalForm.php";
        break;

    case 'deleteHabilidad':
        $habilidad = new Habilidad($_POST['id_habilidad']);
        $rta = $habilidad->deleteHabilidad();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    case 'autocompletarHabilidades':
        $view->habilidad = new Habilidad();
        $rta=$view->habilidad->autocompletarHabilidades($_POST['term']);
        print_r(json_encode($rta));
        exit;
        break;

    default : //ok
        $view->vencimientos_personal = Vencimiento_personal::getVencimientosPersonal();
        $view->contentTemplate="view/vencimientos_personalGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/vencimientos_personalLayout.php');
}


?>
