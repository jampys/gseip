﻿<?php
include_once("model/contrato-vehiculoModel.php");
include_once("model/vehiculosModel.php");
include_once("model/contratosModel.php");
include_once("model/localidadesModel.php");

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
        $view->vehiculos = ContratoVehiculo::getVehiculos($_POST['id_contrato']);
        $view->contentTemplate="view/contratos/vehiculosGrid.php";
        break;

    case 'saveVehiculo': //ok
        $gv = new ContratoVehiculo($_POST['id_contrato_vehiculo']);
        $gv->setIdContrato($_POST['id_contrato']);
        $gv->setIdVehiculo($_POST['id_vehiculo']);
        $gv->setFechaDesde($_POST['fecha_desde']);
        $gv->setFechaHasta( ($_POST['fecha_hasta']!='')? $_POST['fecha_hasta'] : null);
        $gv->setIdLocalidad($_POST['id_localidad']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        $rta = $gv->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newVehiculo': //ok
        $view->label='Nuevo vehículo';
        $view->contrato_vehiculo = new ContratoVehiculo($_POST['id_contrato_vehiculo']);

        //$view->etapas = Soporte::get_enum_values('sel_etapas', 'etapa');
        $view->vehiculos = Vehiculo::getVehiculos();
        $view->localidades = Localidad::getLocalidades();

        $view->disableLayout=true;
        $view->contentTemplate="view/contratos/vehiculo_detailForm.php";
        break;

    case 'editVehiculo': //ok
        $view->label = ($_POST['target']!='view')? 'Editar vehículo': 'Ver vehículo';
        $view->contrato_vehiculo = new ContratoVehiculo($_POST['id_contrato_vehiculo']);

        //$view->etapas = Soporte::get_enum_values('sel_etapas', 'etapa');
        $view->vehiculos = Vehiculo::getVehiculos();
        $view->localidades = Localidad::getLocalidades();

        $view->disableLayout=true;
        //$view->target = $_POST['target'];
        $view->contentTemplate="view/contratos/vehiculo_detailForm.php";
        break;

    case 'deleteVehiculo': //ok
        $view->contrato_vehiculo = new ContratoVehiculo($_POST['id_contrato_vehiculo']);
        $rta = $view->contrato_vehiculo->deleteVehiculoContrato();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    case 'checkVehiculo': //ok
        $view->contrato_vehiculo = new ContratoVehiculo();
        $rta = $view->contrato_vehiculo->checkVehiculo($_POST['id_vehiculo'], $_POST['id_contrato'], $_POST['id_contrato_vehiculo']);
        print_r(json_encode($rta));
        exit;
        break;


    default : //carga la tabla de vehiculos del contrato //ok
        $view->disableLayout=true;
        $view->contrato = new Contrato($_POST['id_contrato']);
        $view->vehiculos = ContratoVehiculo::getVehiculos($_POST['id_contrato']);
        $view->label= $view->contrato->getNombre().' '.$view->contrato->getNroContrato();
        $view->contentTemplate="view/contratos/vehiculosForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>