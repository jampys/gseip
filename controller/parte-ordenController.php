<?php
include_once("model/nov_parte-ordenModel.php");

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
        $view->ordenes = ParteOrden::getParteOrden($_POST['id_parte']);
        $view->contentTemplate="view/novedades_partes/ordenesGrid.php";
        break;

    case 'saveOrden': //ok
        $orden = new ParteOrden($_POST['id_parte_orden']);
        $orden->setIdParte($_POST['id_parte']);
        $orden->setNroParteDiario($_POST['nro_parte_diario']);
        $orden->setOrdenTipo($_POST['orden_tipo']);
        $orden->setOrdenNro($_POST['orden_nro']);
        $orden->setDuracion($_POST['duracion']);
        $orden->setServicio($_POST['servicio']);
        $orden->setCreatedBy($_SESSION['id_user']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        $rta = $orden->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newOrden': //ok
        $view->label='Nueva Orden';
        $view->orden = new ParteOrden();

        $view->orden_tipos = Soporte::get_enum_values('nov_parte_orden', 'orden_tipo');

        $view->disableLayout=true;
        //$view->target = $_POST['target'];
        $view->contentTemplate="view/novedades_partes/orden_detailForm.php";
        break;

    case 'editOrden': //ok
        $view->label = ($_POST['target']!='view')? 'Editar orden': 'Ver orden';
        $view->orden = new ParteOrden($_POST['id_parte_orden']);

        $view->orden_tipos = Soporte::get_enum_values('nov_parte_orden', 'orden_tipo');

        $view->disableLayout=true;
        //$view->target = $_POST['target'];
        $view->contentTemplate="view/novedades_partes/orden_detailForm.php";
        break;

    case 'deleteOrden': //ok
        $view->orden = new ParteOrden($_POST['id_parte_orden']);
        $rta = $view->orden->deleteParteOrden();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;


    case 'checkEmpleado':
        $view->empleado = new CuadrillaEmpleado();
        $rta = $view->empleado->checkEmpleado($_POST['id_cuadrilla_empleado'], $_POST['id_cuadrilla'], $_POST['id_empleado']);
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