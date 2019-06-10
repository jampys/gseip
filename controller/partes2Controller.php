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

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{

    case 'loadControl': //ok  //abre ventana modal para controlar novedades
        $view->disableLayout=true;
        $view->label = 'Controlar novedades';
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos

        $view->contentTemplate="view/novedades_partes/control_partesForm.php";
        break;


    case 'getPeriodosAndEmpleados': //select dependiente //ok
        $id_contrato = (($_POST['id_contrato']!='')? $_POST['id_contrato'] : null );
        $activos = (($_POST['activos']!='')? $_POST['activos'] : null );
        $periodos = NovPeriodo::getPeriodos($id_contrato, $activos);
        $empleados = Empleado::getEmpleadosActivos($id_contrato);
        print_r(json_encode(array('periodos'=>$periodos, 'empleados'=>$empleados)));
        exit;
        break;


    default :
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/novedades_partes/partesLayout.php');
}


?>
