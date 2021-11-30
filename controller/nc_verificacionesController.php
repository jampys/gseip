﻿<?php
include_once("model/nc_verificacionesModel.php");
include_once("model/contratosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrp.id_vencimiento';
        //$id_puesto = ($_POST['search_puesto']!='')? $_POST['search_puesto'] : null;
        //$id_localidad = ($_POST['search_localidad']!='')? $_POST['search_localidad'] : null;
        //$id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        //$todas = ($_POST['renovado']== 0)? null : 1;
        //$view->busquedas = Busqueda::getBusquedas($id_puesto, $id_localidad, $id_contrato, $todas);
        $rta = $view->verificaciones = Verificacion::getVerificaciones($_POST['id_no_conformidad']);
        //$view->contentTemplate="view/no_conformidad/accionesGrid.php";
        //break;
        print_r(json_encode($rta));
        exit;
        break;

    case 'saveVerificacion': //ok
        $verificacion = new Verificacion($_POST['id_verificacion']);
        $verificacion->setIdNoConformidad($_POST['id_no_conformidad']);
        $verificacion->setVerificacionEficacia($_POST['verificacion_eficacia']);
        $verificacion->setFechaVerificacion($_POST['fecha_verificacion']);
        $verificacion->setIdUser($_SESSION['id_user']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        //$busqueda->setIdLocalidad( ($_POST['id_localidad']!='')? $_POST['id_localidad'] : null);
        $rta = $verificacion->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newVerificacion': //ok
        $view->label='Nueva verificación';
        $view->verificacion = new Verificacion($_POST['id_verificacion']);

        $view->disableLayout=true;
        $view->contentTemplate="view/no_conformidad/verificacion_detailForm.php";
        break;

    case 'editVerificacion': //ok
        $view->label = ($_POST['target']!='view')? 'Editar verificación': 'Ver verificación';
        $view->verificacion = new Verificacion($_POST['id_verificacion']);

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/no_conformidad/verificacion_detailForm.php";
        break;

    case 'deleteVerificacion':
        $view->verificacion = new Verificacion($_POST['id_verificacion']);
        $rta = $view->verificacion->deleteVerificacion();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    default : //carga la tabla de acciones de la No conformidad //ok
        $view->label='Verificaciones de la No conformidad';
        //$view->acciones = Accion::getAcciones($_POST['id_no_conformidad']);
        $view->disableLayout=true;
        $view->contentTemplate="view/no_conformidad/verificacionesForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>