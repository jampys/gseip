<?php
include_once("model/nov_parte-empleadoModel.php");

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
        //$id_puesto = ($_POST['search_puesto']!='')? $_POST['search_puesto'] : null;volante
        //$id_localidad = ($_POST['search_localidad']!='')? $_POST['search_localidad'] : null;
        //$id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        //$todas = ($_POST['renovado']== 0)? null : 1;
        //$view->busquedas = Busqueda::getBusquedas($id_puesto, $id_localidad, $id_contrato, $todas);
        $view->empleados = ParteEmpleado::getParteEmpleado($_POST['id_parte']);
        $view->contentTemplate="view/novedades_partes/empleadosGrid.php";
        break;

    case 'saveEmpleado': //ok
        $empleado = new ParteEmpleado($_POST['id_parte_empleado']);
        $empleado->setIdParte($_POST['id_parte']);
        $empleado->setIdEmpleado($_POST['id_empleado']);
        $empleado->setConductor( ($_POST['conductor']== 1)? $_POST['conductor'] : 0);
        $empleado->setAvoidEvent( ($_POST['avoid_event']== 1)? $_POST['avoid_event'] : null);
        $empleado->setComentario( ($_POST['comentario'])? $_POST['comentario'] : null );
        $empleado->setCreatedBy($_SESSION['id_user']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        $rta = $empleado->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newEmpleado': //ok
        $view->label='Nuevo empleado';
        $view->empleado = new ParteEmpleado();

        $view->empleados = Empleado::getEmpleadosActivos($_POST['id_contrato']);
        $view->conductor = Soporte::get_enum_values('nov_parte_empleado', 'conductor');

        $view->disableLayout=true;
        $view->contentTemplate="view/novedades_partes/empleado_detailForm.php";
        break;

    case 'editEmpleado': //ok
        $view->label = ($_POST['target']!='view')? 'Editar empleado': 'Ver empleado';
        $view->empleado = new ParteEmpleado($_POST['id_parte_empleado']);

        $view->empleados = Empleado::getEmpleadosActivos($_POST['id_contrato']);
        $view->conductor = Soporte::get_enum_values('nov_parte_empleado', 'conductor');

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/novedades_partes/empleado_detailForm.php";
        break;

    case 'deleteEmpleado': //ok
        $view->empleado = new ParteEmpleado($_POST['id_parte_empleado']);
        $rta = $view->empleado->deleteParteEmpleado();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;


    case 'checkEmpleado': //ok
        $view->empleado = new ParteEmpleado();
        $rta = $view->empleado->checkEmpleado($_POST['id_parte_empleado'], $_POST['id_parte']);
        print_r(json_encode($rta));
        exit;
        break;


    default : //carga la tabla de empleados del parte
        //$view->label='Empleados del parte';
        //$view->empleados = ParteEmpleado::getParteEmpleado($_POST['id_parte']);
        //$view->disableLayout=true;
        //$view->contentTemplate="view/cuadrillas/empleadosForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>