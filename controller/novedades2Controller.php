<?php
include_once("model/nov_partesModel.php");
include_once("model/nov_parte-empleadoModel.php");
include_once("model/nov_parte-ordenModel.php");
include_once("model/nov_parte-empleado-conceptoModel.php");

include_once("model/nov_areasModel.php");
include_once("model/contratosModel.php");

include_once("model/cuadrillasModel.php");
include_once("model/vehiculosModel.php");
include_once("model/nov_eventosCuadrillaModel.php");
include_once("model/empleadosModel.php");
include_once("model/nov_periodosModel.php");
include_once("model/nov_sucesosModel.php");
include_once("model/nov_concepto-convenio-contratoModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{





    case 'newParte': //ok
        //$view->label='Nuevo parte: '.$_POST['fecha_parte'].' '.$_POST['contrato'];
        //$view->parte = new Parte();

        $view->empleados = Empleado::getEmpleadosActivos($_POST['add_contrato']);
        $view->periodo = New NovPeriodo($_POST['id_periodo']);
        $view->contrato = New Contrato($_POST['add_contrato']);
        //$view->vehiculos = Vehiculo::getVehiculos();
        //$view->eventos = EventosCuadrilla::getEventosCuadrilla();
        //$view->cuadrillas = Cuadrilla::getCuadrillasForPartes($_POST['add_contrato'], $_POST['fecha_parte']);
        //$view->params = array('fecha_parte' => $_POST['fecha_parte'], 'id_periodo' => $_POST['id_periodo']);

        //$view->disableLayout=true;
        $view->contentTemplate="view/novedades2/2empleadoForm.php";
        break;


    case 'tableEmpleados': //ok
        //$view->label='Nuevo parte: '.$_POST['fecha_parte'].' '.$_POST['contrato'];
        //$view->parte = new Parte();

        $view->empleados = Parte::getEmpleados($_POST['fecha'], $_POST['id_contrato']);


        $view->disableLayout=true;
        $view->contentTemplate="view/novedades2/empleadosGrid.php";
        break;




    case 'editParte': //ok
        $view->empleado = New Empleado($_POST['id_empleado']);
        $view->label = $view->empleado->getLegajo().' '.$view->empleado->getApellido()." ".$view->empleado->getNombre();
        $view->label.= ($_POST['id_parte'])? " - Parte nro. ".$_POST['id_parte']: "";

        $view->cuadrillas = Cuadrilla::getCuadrillas($_POST['id_contrato'], null);
        $view->eventos = EventosCuadrilla::getEventosCuadrilla();
        $view->ordenes = ParteOrden::getParteOrden($_POST['id_parte']); //2104
        $view->periodo = New NovPeriodo($_POST['id_periodo']);
        $view->parte_empleado = new ParteEmpleado($_POST['id_parte_empleado']);
        $view->parte = new Parte($_POST['id_parte']);
        $view->conceptos = new Parte($_POST['id_contrato'], $view->empleado->getIdConvenio());
        //$view->conceptos = ParteEmpleadoConcepto::getParteEmpleadoConcepto2($_POST['id_parte_empleado']);

        $eventos = ($_POST['eventos']!='')? implode(",", $_POST['eventos'])  : 'su.id_evento';
        $fecha_desde = $view->periodo->getFechaDesde(); //($_POST['fecha']!='')? $_POST['fecha'] : null;
        $fecha_hasta = $view->periodo->getFechaHasta(); //($_POST['fecha']!='')? $_POST['fecha'] : null;
        $id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        $view->sucesos = Suceso::getSucesos($_POST['id_empleado'], $eventos, $fecha_desde, $fecha_hasta, $id_contrato);
        //$view->params = array('fecha_parte' => $_POST['fecha_parte'], 'id_periodo' => $_POST['id_periodo']);
        $view->params = array('id_parte_empleado' => $_POST['id_parte_empleado']);

        $view->disableLayout=true;
        $view->contentTemplate="view/novedades2/3conceptoForm.php";
        break;


    case 'loadConceptos': //ok
        //$view->contratoEmpleado = ContratoEmpleado::getContratoEmpleado($_POST['id_contrato']);
        $view->conceptos = ParteEmpleadoConcepto::getParteEmpleadoConcepto2($_POST['id_parte_empleado']);
        print_r(json_encode($view->conceptos));
        exit;
        break;



    default : //ok
        //$view->areas = NovArea::getAreas(); //carga el combo para filtrar Areas
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/novedades2/1contratoForm.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/novedades2/novedadesLayout.php');
}


?>
