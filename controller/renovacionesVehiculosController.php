<?php

include_once("model/vto_renovacionesVehiculosModel.php");
include_once("model/vto_vencimientosVehiculosModel.php");
include_once("model/contratosModel.php");
include_once("model/subcontratistasModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        $id_vehiculo = ($_POST['id_vehiculo']!='')? $_POST['id_vehiculo'] : null;
        $id_grupo = ($_POST['id_grupo']!='')? $_POST['id_grupo'] : null;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? $_POST['id_vencimiento'] : null;
        $id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrv.id_vencimiento';
        $id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        $id_subcontratista = ($_POST['id_subcontratista']!='')? $_POST['id_subcontratista'] : null;
        $renovado = ($_POST['renovado']== 0)? null : 1;
        $view->renovaciones_vehiculos = RenovacionVehicular::getRenovacionesVehiculos($id_vehiculo, $id_grupo, $id_vencimiento, $id_contrato, $id_subcontratista, $renovado);
        $view->contentTemplate="view/renovacionesVehiculosGrid.php";
        break;

    case 'saveRenovacion': //ok

        $renovacion = new RenovacionVehicular($_POST['id_renovacion']);
        $renovacion->setIdVencimiento($_POST['id_vencimiento']);
        $renovacion->setFechaEmision($_POST['fecha_emision']);
        $renovacion->setFechaVencimiento($_POST['fecha_vencimiento']);
        $renovacion->setIdVehiculo ( ($_POST['id_vehiculo']!='')? $_POST['id_vehiculo'] : null);
        $renovacion->setIdGrupo ( ($_POST['id_grupo']!='')? $_POST['id_grupo'] : null);
        $renovacion->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        $renovacion->setReferencia($_POST['referencia']);
        $renovacion->setCreatedBy($_SESSION["id_user"]);

        $rta = $renovacion->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newRenovacion': //ok
        $view->label='Nuevo vencimiento';
        $view->renovacion = new RenovacionVehicular();

        $view->vencimientos = VencimientoVehicular::getVencimientosVehiculos();
        $view->vehiculosGrupos = $view->renovacion->vehiculosGrupos();

        $view->vehiculo = $view->renovacion->getVehiculo()->getMatricula()." ".$view->renovacion->getVehiculo()->getNroMovil();

        $view->disableLayout=true;
        $view->contentTemplate="view/renovacionesVehiculosForm.php";
        break;

    case 'editRenovacion': //ok
        $view->label = ($_POST['target'] == 'view')? 'Ver vencimiento':'Editar vencimiento';
        $view->renovacion = new RenovacionVehicular($_POST['id_renovacion']);

        $view->vencimientos = VencimientoVehicular::getVencimientosVehiculos();
        $view->vehiculosGrupos = $view->renovacion->vehiculosGrupos();

        $view->vehiculo = $view->renovacion->getVehiculo()->getMatricula()." ".$view->renovacion->getVehiculo()->getNroMovil();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/renovacionesVehiculosForm.php";
        break;

    case 'renovRenovacion': //Renueva una renovacion existente //ok
        $view->label='Renovar vencimiento';
        $view->renovacion = new RenovacionVehicular($_POST['id_renovacion']);
        $view->renovacion->setIdRenovacion('');
        $view->renovacion->setFechaEmision('');
        $view->renovacion->setFechaVencimiento('');
        $view->renovacion->setReferencia('');

        $view->vencimientos = VencimientoVehicular::getVencimientosVehiculos();
        $view->vehiculosGrupos = $view->renovacion->vehiculosGrupos();

        $view->vehiculo = $view->renovacion->getVehiculo()->getMatricula()." ".$view->renovacion->getVehiculo()->getNroMovil();

        $view->disableLayout=true;
        $view->contentTemplate="view/renovacionesVehiculosForm.php";
        break;

    case 'checkRangoFechas': //ok
        $view->renovacion = new RenovacionVehicular();
        $rta = $view->renovacion->checkRangoFechas($_POST['fecha_emision'], $_POST['fecha_vencimiento'], $_POST['id_vehiculo'], $_POST['id_grupo'], $_POST['id_vencimiento'], $_POST['id_renovacion']);
        print_r(json_encode($rta));
        exit;
        break;

    default : //ok
        $view->renovacion = new RenovacionVehicular();
        $view->vehiculosGrupos = $view->renovacion->vehiculosGrupos(); //carga el combo para filtrar vehiculos-grupos
        $view->vencimientos = VencimientoVehicular::getVencimientosVehiculos(); //carga el combo para filtrar vencimientos
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos
        $view->subcontratistas = Subcontratista::getSubcontratistas(); //carga el combo para filtrar subcontratistas
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
