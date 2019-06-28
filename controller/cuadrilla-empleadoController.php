<?php
include_once("model/cuadrillasModel.php");
include_once("model/cuadrilla-empleadoModel.php");

include_once("model/puestosModel.php");
include_once("model/localidadesModel.php");
include_once("model/contratosModel.php");

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
        $view->empleados = CuadrillaEmpleado::getCuadrillaEmpleado($_POST['id_cuadrilla'], null);
        $view->contentTemplate="view/cuadrillas/empleadosGrid.php";
        break;

    case 'saveEmpleado': //ok
        $empleado = new CuadrillaEmpleado($_POST['id_cuadrilla_empleado']);
        $empleado->setIdCuadrilla($_POST['id_cuadrilla']);
        $empleado->setIdEmpleado($_POST['id_empleado']);
        $empleado->setConductor($_POST['conductor']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        //$busqueda->setIdLocalidad( ($_POST['id_localidad']!='')? $_POST['id_localidad'] : null);
        $rta = $empleado->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newEmpleado': //ok
        $view->label='Nuevo empleado';
        $view->empleado = new CuadrillaEmpleado($_POST['id_cuadrilla_empleado']);
        $view->cuadrilla = new Cuadrilla($_POST['id_cuadrilla']);

        $view->empleados = Empleado::getEmpleadosActivos($view->cuadrilla->getIdContrato());

        $view->disableLayout=true;
        $view->contentTemplate="view/cuadrillas/empleado_detailForm.php";
        break;

    case 'editEmpleado': //ok
        $view->label = ($_POST['target']!='view')? 'Editar empleado': 'Ver empleado';
        $view->empleado = new CuadrillaEmpleado($_POST['id_cuadrilla_empleado']);
        $view->cuadrilla = new Cuadrilla($_POST['id_cuadrilla']);

        $view->empleados = Empleado::getEmpleadosActivos($view->cuadrilla->getIdContrato());

        $view->disableLayout=true;
        //$view->target = $_POST['target'];
        $view->contentTemplate="view/cuadrillas/empleado_detailForm.php";
        break;

    case 'deleteEmpleado': //ok
        $view->empleado = new CuadrillaEmpleado($_POST['id_cuadrilla_empleado']);
        $rta = $view->empleado->deleteCuadrillaEmpleado();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;


    case 'checkEmpleado': //ok
        $view->empleado = new CuadrillaEmpleado();
        $rta = $view->empleado->checkEmpleado($_POST['id_cuadrilla_empleado'], $_POST['id_cuadrilla'], $_POST['id_empleado']);
        print_r(json_encode($rta));
        exit;
        break;


    default : //carga la tabla de empleados de la cuadrilla //ok
        //$view->label='Empleados de la cuadrilla';
        $view->cuadrilla = new Cuadrilla($_POST['id_cuadrilla']);
        $view->label='Empleados de cuadrilla: '.$view->cuadrilla->getNombre();
        $view->empleados = CuadrillaEmpleado::getCuadrillaEmpleado($_POST['id_cuadrilla'], null);
        $view->disableLayout=true;
        $view->contentTemplate="view/cuadrillas/empleadosForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>