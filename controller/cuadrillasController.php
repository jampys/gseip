<?php
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
        $id_contrato = ($_POST['search_contrato']!='')? $_POST['search_contrato'] : 9999;
        $todas = null; //($_POST['renovado']== 0)? null : 1;
        $rta = $view->cuadrillas = Cuadrilla::getCuadrillas($id_contrato);
        //$view->contentTemplate="view/cuadrillas/cuadrillasGrid.php";
        //break;
        print_r(json_encode($rta));
        exit;
        break;

    case 'saveCuadrilla': //ok
        $cuadrilla = new Cuadrilla($_POST['id_cuadrilla']);
        $cuadrilla->setIdContrato($_POST['id_contrato']);
        $cuadrilla->setDefaultIdVehiculo( ($_POST['default_id_vehiculo']!='')? $_POST['default_id_vehiculo'] : null );
        $cuadrilla->setDefaultIdArea( ($_POST['default_id_area']!='')? $_POST['default_id_area'] : null );
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        $cuadrilla->setNombre($_POST['nombre']);
        $cuadrilla->setNombreCorto($_POST['nombre_corto']);
        $cuadrilla->setNombreCortoOp( ($_POST['nombre_corto_op'])? $_POST['nombre_corto_op'] : null );
        $cuadrilla->setActividad($_POST['actividad']);
        $cuadrilla->setDisabled(($_POST['disabled'] == 1)? 1 : null);

        $rta = $cuadrilla->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newCuadrilla': //ok
        $view->label='Nueva cuadrilla';
        $view->cuadrilla = new Cuadrilla($_POST['id_cuadrilla']);

        $view->id_contrato = $_POST['id_contrato'];
        //$view->contratos = Contrato::getContratosControl();
        $view->vehiculos = Vehiculo::getVehiculos();
        $view->areas = NovArea::getAreas($_POST['id_contrato']);

        $view->disableLayout=true;
        $view->contentTemplate="view/cuadrillas/cuadrillasForm.php";
        break;

    case 'editCuadrilla': //ok
        $view->cuadrilla = new Cuadrilla($_POST['id_cuadrilla']);
        $view->label='Cuadrilla: '.$view->cuadrilla->getNombre();

        //$view->contratos = Contrato::getContratosControl();
        $view->vehiculos = Vehiculo::getVehiculos();
        //$view->areas = NovArea::getAreas(25);
        $view->areas = NovArea::getAreas($view->cuadrilla->getIdContrato());

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/cuadrillas/cuadrillasForm.php";
        break;


    case 'deleteCuadrilla': //ok
        $cuadrilla = new Cuadrilla($_POST['id_cuadrilla']);
        $rta = $cuadrilla->deleteCuadrilla();
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
        $view->contratos = Contrato::getContratosControlNovedades(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/cuadrillas/cuadrillasGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/cuadrillas/cuadrillasLayout.php');
}


?>
