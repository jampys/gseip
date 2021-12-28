<?php
include_once("model/contratosModel.php");
include_once("model/cap_empleadosModel.php");
include_once("model/cap_edicionesModel.php");
include_once("model/contrato-empleadoModel.php");
include_once("model/cap_modalidadesModel.php");

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
        $rta = $view->ediciones = Edicion::getEdiciones($_POST['id_capacitacion']);
        //$view->contentTemplate="view/no_conformidad/accionesGrid.php";
        //break;
        print_r(json_encode($rta));
        exit;
        break;

    case 'saveEdicion': //ok
        $edicion = new Edicion($_POST['id_edicion']);
        $edicion->setIdCapacitacion($_POST['id_capacitacion']);
        $edicion->setNombre($_POST['nombre']);
        $edicion->setFechaEdicion($_POST['fecha_edicion']);
        $edicion->setCapacitador($_POST['capacitador']);
        $edicion->setDuracion($_POST['duracion']);
        $edicion->setIdModalidad($_POST['id_modalidad']);
        $edicion->setIdUser($_SESSION['id_user']);
        $rta = $edicion->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newEdicion': //ok
        $view->label='Agregar edición';
        $view->edicion = new Edicion($_POST['id_edicion']);

        $view->modalidades = Modalidad::getModalidades();

        $view->disableLayout=true;
        $view->contentTemplate="view/capacitaciones/edicion_detailForm.php";
        break;

    case 'editEdicion': //ok
        $view->edicion = new Edicion($_POST['id_edicion']);
        $view->label = $view->edicion->getNombre();

        $view->modalidades = Modalidad::getModalidades();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/capacitaciones/edicion_detailForm.php";
        break;

    case 'deleteEdicion': //ok
        $view->edicion = new Edicion($_POST['id_edicion']);
        $rta = $view->edicion->deleteEdicion();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    default : //carga la tabla de empleados de la capacitacion //ok
        $view->label='Ediciones de la capacitación';
        //$view->acciones = Accion::getAcciones($_POST['id_no_conformidad']);
        $view->disableLayout=true;
        $view->contentTemplate="view/capacitaciones/edicionesForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>
