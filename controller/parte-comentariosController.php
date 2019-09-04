<?php
include_once("model/nov_partesModel.php");

//include_once("model/puestosModel.php");
//include_once("model/localidadesModel.php");
//include_once("model/contratosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
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

    case 'saveComentarios': //ok
        $parte = new Parte($_POST['id_parte']);
        $parte->setComentarios( ($_POST['comentarios'])? $_POST['comentarios'] : null);
        $rta = $parte->updateComentarios();
        print_r(json_encode($rta));
        exit;
        break;

    /*case 'newEmpleado':
        $view->label='Nuevo empleado';
        $view->empleado = new ParteEmpleado();

        $view->empleados = Empleado::getEmpleadosActivos($_POST['id_contrato']);
        $view->conductor = Soporte::get_enum_values('nov_parte_empleado', 'conductor');

        $view->disableLayout=true;
        $view->contentTemplate="view/novedades_partes/empleado_detailForm.php";
        break;*/

    case 'editComentarios':
        $view->label = 'Comentarios';
        $view->parte = new Parte($_POST['id_parte']);

        //$view->empleados = Empleado::getEmpleadosActivos($_POST['id_contrato']);
        //$view->conductor = Soporte::get_enum_values('nov_parte_empleado', 'conductor');

        $view->disableLayout=true;
        //$view->target = $_POST['target'];
        $view->contentTemplate="view/novedades_partes/comentarios_detailForm.php";
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