<?php
include_once("model/nc_accionesModel.php");

include_once("model/puestosModel.php");
include_once("model/localidadesModel.php");
include_once("model/contratosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrp.id_vencimiento';
        //$id_puesto = ($_POST['search_puesto']!='')? $_POST['search_puesto'] : null;
        //$id_localidad = ($_POST['search_localidad']!='')? $_POST['search_localidad'] : null;
        //$id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        //$todas = ($_POST['renovado']== 0)? null : 1;
        //$view->busquedas = Busqueda::getBusquedas($id_puesto, $id_localidad, $id_contrato, $todas);
        $view->etapas = Etapa::getEtapas($_POST['id_postulacion']);
        $view->contentTemplate="view/postulaciones/etapasGrid.php";
        break;

    case 'saveAccion': //ok
        $accion = new Accion($_POST['id_accion']);
        $accion->setIdNoConformidad($_POST['id_no_conformidad']);
        $accion->setAccion($_POST['accion']);
        $accion->setIdResponsableEjecucion($_POST['id_responsable_ejecucion']);
        $accion->setFechaImplementacion($_POST['fecha_implementacion']);
        $accion->setIdUser($_SESSION['id_user']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        //$busqueda->setIdLocalidad( ($_POST['id_localidad']!='')? $_POST['id_localidad'] : null);
        $rta = $accion->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newAccion': //ok
        $view->label='Nueva acción';
        $view->accion = new Accion($_POST['id_accion']);

        $view->empleados = (!$_POST['id_empleado'])? Empleado::getEmpleadosActivos(null) : Empleado::getEmpleados(); //carga el combo de empleados

        $view->disableLayout=true;
        $view->contentTemplate="view/no_conformidad/accion_detailForm.php";
        break;

    case 'editAccion': //ok
        $view->label = ($_POST['target']!='view')? 'Editar acción': 'Ver acción';
        $view->accion = new Accion($_POST['id_accion']);

        $view->empleados = (!$_POST['id_empleado'])? Empleado::getEmpleadosActivos(null) : Empleado::getEmpleados(); //carga el combo de empleados

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/no_conformidad/accion_detailForm.php";
        break;

    case 'deleteEtapa':
        $view->etapa = new Etapa($_POST['id_etapa']);
        $rta = $view->etapa->deleteEtapa();
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


    default : //carga la tabla de acciones de la No conformidad //ok
        $view->label='Acciones de la No conformidad';
        $view->acciones = Accion::getAcciones($_POST['id_no_conformidad']);
        //$view->localidades = Localidad::getLocalidades();
        //$view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');
        $view->disableLayout=true;
        $view->contentTemplate="view/no_conformidad/accionesForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>
