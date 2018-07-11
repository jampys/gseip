﻿<?php
include_once("model/cuadrillasModel.php");

include_once("model/contratosModel.php");
include_once("model/vehiculosModel.php");
include_once("model/nov_areasModel.php");


//include_once("model/busquedasModel.php");
//include_once("model/postulantesModel.php");
//include_once("model/etapasModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrp.id_vencimiento';
        $id_contrato = ($_POST['search_contrato']!='')? $_POST['search_contrato'] : null;
        $todas = null; //($_POST['renovado']== 0)? null : 1;
        $view->cuadrillas = Cuadrilla::getCuadrillas($id_contrato, $todas);
        $view->contentTemplate="view/cuadrillas/cuadrillasGrid.php";
        break;

    case 'saveCuadrilla': //ok
        $cuadrilla = new Cuadrilla($_POST['id_cuadrilla']);
        $cuadrilla->setIdContrato($_POST['id_contrato']);
        $cuadrilla->setDefaultIdVehiculo($_POST['default_id_vehiculo']);
        $cuadrilla->setDefaultIdArea($_POST['default_id_area']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        //$postulacion->setIdPuesto( ($_POST['id_puesto']!='')? $_POST['id_puesto'] : null);
        $cuadrilla->setNombre($_POST['nombre']);
        $cuadrilla->setActividad($_POST['actividad']);

        $rta = $cuadrilla->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newCuadrilla': //ok
        $view->label='Nueva cuadrilla';
        $view->cuadrilla = new Cuadrilla($_POST['id_cuadrilla']);

        $view->contratos = Contrato::getContratos();
        $view->vehiculos = Vehiculo::getVehiculos();
        $view->areas = NovArea::getAreas();

        $view->disableLayout=true;
        $view->contentTemplate="view/cuadrillas/cuadrillasForm.php";
        break;

    case 'editCuadrilla': //ok
        $view->label='Editar cuadrilla';
        $view->cuadrilla = new Cuadrilla($_POST['id_cuadrilla']);

        $view->contratos = Contrato::getContratos();
        $view->vehiculos = Vehiculo::getVehiculos();
        $view->areas = NovArea::getAreas();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/cuadrillas/cuadrillasForm.php";
        break;


    case 'deleteHabilidad':
        $habilidad = new Habilidad($_POST['id_habilidad']);
        $rta = $habilidad->deleteHabilidad();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;


    case 'checkFechaEmision':
        $view->renovacion = new RenovacionPersonal();
        $rta = $view->renovacion->checkFechaEmision($_POST['fecha_emision'], $_POST['id_empleado'], $_POST['id_grupo'], $_POST['id_vencimiento'], $_POST['id_renovacion']);
        print_r(json_encode($rta));
        exit;
        break;

    case 'checkFechaVencimiento':
        $view->renovacion = new RenovacionPersonal();
        $rta = $view->renovacion->checkFechaVencimiento($_POST['fecha_emision'], $_POST['fecha_vencimiento'], $_POST['id_empleado'], $_POST['id_grupo'], $_POST['id_vencimiento'], $_POST['id_renovacion']);
        print_r(json_encode($rta));
        exit;
        break;

    default : //ok
        $view->contratos = Contrato::getContratos(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/postulaciones/postulacionesGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/cuadrillas/cuadrillasLayout.php');
}


?>