﻿<?php

include_once("model/vto_renovacionesVehiculosModel.php");
include_once("model/vto_vencimientosVehiculosModel.php");
include_once("model/contratosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        $id_vehiculo = ($_POST['id_vehiculo']!='')? $_POST['id_vehiculo'] : null;
        $id_grupo = ($_POST['id_grupo']!='')? $_POST['id_grupo'] : null;
        $id_vencimiento = ($_POST['id_vencimiento']!='')? $_POST['id_vencimiento'] : null;
        $id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        $renovado = ($_POST['renovado']== 0)? null : 1;
        $view->renovaciones_vehiculos = RenovacionVehicular::getRenovacionesVehiculos($id_vehiculo, $id_grupo, $id_vencimiento, $id_contrato, $renovado);
        $view->contentTemplate="view/renovacionesVehiculosGrid.php";
        break;

    case 'saveRenovacion': //ok

        $renovacion = new RenovacionVehicular($_POST['id_renovacion']);
        $renovacion->setIdVencimiento($_POST['id_vencimiento']);
        $renovacion->setFechaEmision($_POST['fecha_emision']);
        $renovacion->setFechaVencimiento($_POST['fecha_vencimiento']);
        //$renovacion->setIdEmpleado($_POST['id_empleado']);
        $renovacion->setIdVehiculo ( ($_POST['id_vehiculo']!='')? $_POST['id_vehiculo'] : null);
        //$renovacion->setIdGrupo($_POST['id_grupo']);
        $renovacion->setIdGrupo ( ($_POST['id_grupo']!='')? $_POST['id_grupo'] : null);

        $rta = $renovacion->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newRenovacion': //ok
        $view->label='Nueva renovación';
        $view->renovacion = new RenovacionVehicular();

        $view->vencimientos = VencimientoVehicular::getVencimientosVehiculos();
        $view->vehiculosGrupos = $view->renovacion->vehiculosGrupos();

        $view->vehiculo = $view->renovacion->getVehiculo()->getMatricula()." ".$view->renovacion->getVehiculo()->getNroMovil();

        $view->disableLayout=true;
        $view->contentTemplate="view/renovacionesVehiculosForm.php";
        break;

    case 'editRenovacion': //ok
        $view->label='Editar Renovación';
        $view->renovacion = new RenovacionVehicular($_POST['id_renovacion']);

        $view->vencimientos = VencimientoVehicular::getVencimientosVehiculos();
        $view->vehiculosGrupos = $view->renovacion->vehiculosGrupos();

        $view->vehiculo = $view->renovacion->getVehiculo()->getMatricula()." ".$view->renovacion->getVehiculo()->getNroMovil();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/renovacionesVehiculosForm.php";
        break;

    case 'renovRenovacion': //Renueva una renovacion existente //ok
        $view->label='Renovación';
        $view->renovacion = new RenovacionVehicular($_POST['id_renovacion']);
        $view->renovacion->setIdRenovacion('');
        $view->renovacion->setFechaEmision('');
        $view->renovacion->setFechaVencimiento('');

        $view->vencimientos = VencimientoVehicular::getVencimientosVehiculos();
        $view->vehiculosGrupos = $view->renovacion->vehiculosGrupos();

        $view->vehiculo = $view->renovacion->getVehiculo()->getMatricula()." ".$view->renovacion->getVehiculo()->getNroMovil();

        $view->disableLayout=true;
        $view->contentTemplate="view/renovacionesVehiculosForm.php";
        break;

    case 'checkFechaEmision': //ok
        $view->renovacion = new RenovacionVehicular();
        $rta = $view->renovacion->checkFechaEmision($_POST['fecha_emision'], $_POST['id_vehiculo'], $_POST['id_grupo'], $_POST['id_vencimiento'], $_POST['id_renovacion']);
        print_r(json_encode($rta));
        exit;
        break;

    case 'checkFechaVencimiento': //ok
        $view->renovacion = new RenovacionVehicular();
        $rta = $view->renovacion->checkFechaVencimiento($_POST['fecha_emision'], $_POST['fecha_vencimiento'], $_POST['id_vehiculo'], $_POST['id_grupo'], $_POST['id_vencimiento'], $_POST['id_renovacion']);
        print_r(json_encode($rta));
        exit;
        break;

    default : //ok
        $view->renovacion = new RenovacionVehicular();
        $view->vehiculosGrupos = $view->renovacion->vehiculosGrupos(); //carga el combo para filtrar vehiculos-grupos
        $view->vencimientos = VencimientoVehicular::getVencimientosVehiculos(); //carga el combo para filtrar vencimientos
        $view->contratos = Contrato::getContratos(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/renovacionesVehiculosGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/renovacionesVehiculosLayout.php');
}


?>