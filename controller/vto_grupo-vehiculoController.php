<?php
include_once("model/vto_grupo-vehiculoModel.php");
include_once("model/vehiculosModel.php");
include_once("model/vto_gruposVehiculosModel.php");
//include_once("model/contratosModel.php");

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
        $rta = $view->vehiculos = GrupoVehiculo::getVehiculos($_POST['id_grupo']);
        //$view->contentTemplate="view/grupos_vehiculos/vehiculosGrid.php";
        //break;
        print_r(json_encode($rta));
        exit;
        break;

    case 'saveVehiculo': //ok
        $gv = new GrupoVehiculo($_POST['id_grupo_vehiculo']);
        $gv->setIdGrupo($_POST['id_grupo']);
        $gv->setIdVehiculo($_POST['id_vehiculo']);
        $gv->setFechaDesde($_POST['fecha_desde']);
        $gv->setFechaHasta( ($_POST['fecha_hasta']!='')? $_POST['fecha_hasta'] : null);
        $gv->setCertificado($_POST['certificado']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        $rta = $gv->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newVehiculo': //ok
        $view->label='Nuevo vehículo';
        $view->grupo_vehiculo = new GrupoVehiculo($_POST['id_grupo_vehiculo']);

        //$view->etapas = Soporte::get_enum_values('sel_etapas', 'etapa');
        $view->vehiculos = Vehiculo::getVehiculos();

        $view->disableLayout=true;
        $view->contentTemplate="view/grupos_vehiculos/vehiculo_detailForm.php";
        break;

    case 'editVehiculo': //ok
        $view->label = ($_POST['target']!='view')? 'Editar vehículo': 'Ver vehículo';
        $view->grupo_vehiculo = new GrupoVehiculo($_POST['id_grupo_vehiculo']);

        //$view->etapas = Soporte::get_enum_values('sel_etapas', 'etapa');
        $view->vehiculos = Vehiculo::getVehiculos();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/grupos_vehiculos/vehiculo_detailForm.php";
        break;

    case 'deleteVehiculo': //ok
        $view->grupo_vehiculo = new GrupoVehiculo($_POST['id_grupo_vehiculo']);
        $rta = $view->grupo_vehiculo->deleteGrupoVehiculo();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    case 'checkVehiculo':
        $view->grupo_vehiculo = new GrupoVehiculo();
        $rta = $view->grupo_vehiculo->checkVehiculo($_POST['id_vehiculo'], $_POST['id_vencimiento'], $_POST['id_grupo_vehiculo']);
        print_r(json_encode($rta));
        exit;
        break;


    default : //carga la tabla de vehiculos del grupo //ok
        $view->disableLayout=true;
        $view->vehiculos = GrupoVehiculo::getVehiculos($_POST['id_grupo']);

        $view->grupo = new Grupo($_POST['id_grupo']);
        $view->label= $view->grupo->getNombre().' '.$view->grupo->getNroReferencia();
        $view->contentTemplate="view/grupos_vehiculos/vehiculosForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>
