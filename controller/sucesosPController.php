<?php

include_once("model/empleadosModel.php");
include_once("model/nov_eventosLiquidacionModel.php");
include_once("model/nov_sucesosModel.php");
include_once("model/nov_sucesosPModel.php");
include_once("model/contratosModel.php");
include_once("model/nov_periodosModel.php");
include_once("model/contrato-empleadoModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'saveSuceso': //ok
        $suceso = new SucesoP($_POST['id_suceso']);
        $suceso->setIdEvento($_POST['id_evento']);
        $suceso->setIdEmpleado($_POST['id_empleado']);
        $suceso->setFechaDesde($_POST['fecha_desde']);
        $suceso->setFechaHasta($_POST['fecha_hasta']);
        $suceso->setObservaciones($_POST['observaciones']);
        $suceso->setCreatedBy($_SESSION['id_user']);
        $suceso->setIdContrato($_POST['id_contrato']);
        $suceso->setProgramado($_POST['programado']);
        $rta = $suceso->save();
        print_r(json_encode(sQuery::dpLastInsertId()));
        //print_r(json_encode($rta));
        exit;
        break;

    case 'newSuceso': //ok
        $view->label='Nuevo Suceso programado';
        $view->suceso = new Suceso($_POST['id_suceso']);

        $view->empleados = Empleado::getEmpleadosControl(null);
        $view->eventos = EventosLiquidacion::getEventosLiquidacion();
        $view->periodos = NovPeriodo::getProximosPeriodos();

        $view->disableLayout=true;
        $view->contentTemplate="view/sucesos/sucesosPForm.php";
        break;

    case 'editSuceso': //ok
        $view->suceso = new SucesoP($_POST['id_suceso']);
        $view->label = ($_POST['target']!='view')? 'Editar suceso programado' : 'Ver suceso programado';

        $view->empleados = Empleado::getEmpleadosControl(null);
        $view->eventos = EventosLiquidacion::getEventosLiquidacion();
        $view->periodos = NovPeriodo::getProximosPeriodos();
        $view->contratos = ContratoEmpleado::getContratosByEmpleado($view->suceso->getIdEmpleado(), 1);

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/sucesos/sucesosPForm.php";
        break;


    case 'getContratos': //ok select dependiente. Trae todos los contratos de un empleado
        $rta = ContratoEmpleado::getContratosByEmpleado($_POST['id_empleado'], $_POST['activos']);
        print_r(json_encode($rta));
        exit;
        break;


    default :
        //$view->empleados = Empleado::getEmpleadosControl(null); //carga el combo para filtrar empleados
        //$view->eventos = EventosLiquidacion::getEventosLiquidacion(); //carga el combo para filtrar eventos liquidacion
        //$view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos
        //$view->contentTemplate="view/sucesos/sucesosGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/sucesos/sucesosLayout.php');
}


?>
