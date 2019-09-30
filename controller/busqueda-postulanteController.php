<?php
include_once("model/busquedasModel.php");
include_once("model/postulacionesModel.php");
include_once("model/postulantesModel.php");
include_once("model/localidadesModel.php");
include_once("model/sel_especialidadesModel.php");

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
        $view->vehiculos = ContratoVehiculo::getVehiculos($_POST['id_contrato']);
        $view->contentTemplate="view/contratos/vehiculosGrid.php";
        break;

    case 'saveVehiculo':
        $gv = new ContratoVehiculo($_POST['id_contrato_vehiculo']);
        $gv->setIdContrato($_POST['id_contrato']);
        $gv->setIdVehiculo($_POST['id_vehiculo']);
        $gv->setFechaDesde($_POST['fecha_desde']);
        $gv->setFechaHasta( ($_POST['fecha_hasta']!='')? $_POST['fecha_hasta'] : null);
        $gv->setIdLocalidad($_POST['id_localidad']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        $rta = $gv->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newPostulacion': //ok
        $view->label='Nueva postulación';
        $view->postulacion = new Postulacion($_POST['id_postulacion']);

        $view->postulantes = Postulante::getPostulantesActivos();
        $view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');

        $view->disableLayout=true;
        $view->contentTemplate="view/busquedas/nPostulacion_detailForm.php";
        break;


    case 'newPostulante': //ok
        //$view->label='Nuevo postulante';
        $view->postulante = new Postulante();

        $view->formaciones = Soporte::get_enum_values('sel_postulantes', 'formacion');
        $view->localidades = Localidad::getLocalidades();
        $view->especialidades = Especialidad::getEspecialidades();

        $view->disableLayout=true;
        $view->contentTemplate="view/busquedas/nPostulante_detailForm.php";
        break;

    case 'editPostulacion': //ok
        $view->label = ($_POST['target']!='view')? 'Editar postulación': 'Ver postulación';
        $view->postulacion = new Postulacion($_POST['id_postulacion']);

        $view->postulantes = Postulante::getPostulantesActivos();
        $view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');

        $view->disableLayout=true;
        $view->contentTemplate="view/busquedas/nPostulacion_detailForm.php";
        break;

    case 'deleteVehiculo':
        $view->contrato_vehiculo = new ContratoVehiculo($_POST['id_contrato_vehiculo']);
        $rta = $view->contrato_vehiculo->deleteVehiculoContrato();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    case 'checkVehiculo':
        $view->contrato_vehiculo = new ContratoVehiculo();
        $rta = $view->contrato_vehiculo->checkVehiculo($_POST['id_vehiculo'], $_POST['id_contrato'], $_POST['id_contrato_vehiculo']);
        print_r(json_encode($rta));
        exit;
        break;


    default : //carga la tabla de postulantes de la busqueda
        $view->disableLayout=true;
        $view->busqueda = new Busqueda($_POST['id_busqueda']);
        $view->label= $view->busqueda->getNombre();
        $view->postulaciones = Postulacion::getPostulaciones($_POST['id_busqueda'], null, null);
        $view->contentTemplate="view/busquedas/nPostulacionesForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>
