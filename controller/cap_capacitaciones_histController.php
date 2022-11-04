<?php

include_once("model/cap_capacitacionesModel.php");
include_once("model/cap_categoriasModel.php");
include_once("model/cap_modalidadesModel.php");
include_once("model/contratosModel.php");
include_once("model/cap_edicionesModel.php");
include_once("model/cap_empleadosModel.php");
include_once("model/empleadosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        $id_categoria = ($_POST['id_categoria']!='')? $_POST['id_categoria'] : null;
        $mes_programada = ($_POST['mes_programada']!='')? $_POST['mes_programada'] : null;
        $asistio = ($_POST['asistio']!='')? $_POST['asistio'] : null;
        $id_empleado = ($_POST['id_empleado']!='')? $_POST['id_empleado'] : null;
        $id_contrato = ($_POST['id_contrato']!='')? implode(",", $_POST['id_contrato'])  : 'ce.id_contrato';
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];

        $rta = $view->capacitaciones = Capacitacion::getCapacitacionesHist($id_categoria, $mes_programada, $asistio, $id_empleado, $id_contrato, $startDate, $endDate);
        //$view->contentTemplate="view/objetivos/objetivosGrid.php";
        //break;
        print_r(json_encode($rta));
        exit;
        break;


    default : //muestra la grilla de capacitaciones
        $view->periodos = Capacitacion::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->categorias = Categoria::getCategorias();
        $view->empleados = Empleado::getEmpleadosControl(null); //carga el combo para filtrar empleados
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos

        $view->contentTemplate="view/capacitaciones/capacitaciones_histGrid.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    include_once('view/capacitaciones/capacitaciones_histLayout.php');
}


?>
