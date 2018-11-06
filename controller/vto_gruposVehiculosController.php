<?php
include_once("model/vto_gruposVehiculosModel.php");


$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        $view->vehiculos = Vehiculo::getVehiculos();
        $view->contentTemplate="view/vehiculos/vehiculosGrid.php";
        break;

    case 'savePostulacion':
        $postulacion = new Postulacion($_POST['id_postulacion']);
        $postulacion->setIdBusqueda($_POST['id_busqueda']);
        $postulacion->setIdPostulante($_POST['id_postulante']);
        $postulacion->setOrigenCv($_POST['origen_cv']);
        $postulacion->setExpectativas($_POST['expectativas']);
        $postulacion->setPropuestaEconomica($_POST['propuesta_economica']);

        $rta = $postulacion->save();
        print_r(json_encode($rta));
        exit;
        break;

    case 'newPostulacion':
        $view->label='Nueva postulación';
        $view->postulacion = new Postulacion();

        $view->busquedas = Busqueda::getBusquedasActivas();
        $view->postulantes = Postulante::getPostulantesActivos();
        $view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');

        $view->disableLayout=true;
        $view->contentTemplate="view/postulaciones/postulacionesForm.php";
        break;

    case 'editPostulacion':
        $view->label='Editar postulación';
        $view->postulacion = new Postulacion($_POST['id_postulacion']);

        $view->busquedas = Busqueda::getBusquedasActivas();
        $view->postulantes = Postulante::getPostulantesActivos();
        $view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/postulaciones/postulacionesForm.php";
        break;


    case 'deleteHabilidad':
        $habilidad = new Habilidad($_POST['id_habilidad']);
        $rta = $habilidad->deleteHabilidad();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;


    default : //ok
        $view->grupos = GrupoVehiculo::getGrupos();
        $view->contentTemplate="view/grupos_vehiculos/gruposGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/grupos_vehiculos/gruposLayout.php');
}


?>
