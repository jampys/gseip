<?php
include_once("model/vto_grupo-vehiculoModel.php");
include_once("model/vehiculosModel.php");
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
        //$id_puesto = ($_POST['search_puesto']!='')? $_POST['search_puesto'] : null;
        //$id_localidad = ($_POST['search_localidad']!='')? $_POST['search_localidad'] : null;
        //$id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        //$todas = ($_POST['renovado']== 0)? null : 1;
        //$view->busquedas = Busqueda::getBusquedas($id_puesto, $id_localidad, $id_contrato, $todas);
        $view->etapas = Etapa::getEtapas($_POST['id_postulacion']);
        $view->contentTemplate="view/postulaciones/etapasGrid.php";
        break;

    case 'saveEtapa':
        $etapa = new Etapa($_POST['id_etapa']);
        $etapa->setIdPostulacion($_POST['id_postulacion']);
        $etapa->setFechaEtapa($_POST['fecha_etapa']);
        $etapa->setEtapa($_POST['etapa']);
        $etapa->setAplica($_POST['aplica']);
        $etapa->setMotivo($_POST['motivo']);
        $etapa->setModoContacto($_POST['modo_contacto']);
        $etapa->setComentarios($_POST['comentarios']);
        $etapa->setIdUser($_SESSION['id_user']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        //$busqueda->setIdLocalidad( ($_POST['id_localidad']!='')? $_POST['id_localidad'] : null);
        $rta = $etapa->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newVehiculo': //ok
        $view->label='Nuevo vehículo';
        $view->grupo_vehiculo = new GrupoVehiculo($_POST['id_grupo_vehiculo']);

        //$view->etapas = Soporte::get_enum_values('sel_etapas', 'etapa');
        $view->vehiculos = Vehiculo::getVehiculos();

        $view->disableLayout=true;
        $view->contentTemplate="view/grupos_vehiculos/vehiculo_detailForm.php";
        break;

    case 'editVehiculo':
        $view->label = ($_POST['target']!='view')? 'Editar vehículo': 'Ver vehículo';
        $view->etapa = new Etapa($_POST['id_etapa']);

        //$view->etapas = Soporte::get_enum_values('sel_etapas', 'etapa');
        $view->vehiculos = Vehiculo::getVehiculos();

        $view->disableLayout=true;
        //$view->target = $_POST['target'];
        $view->contentTemplate="view/grupos_vehiculos/vehiculo_detailForm.php";
        break;

    case 'deleteEtapa':
        $view->etapa = new Etapa($_POST['id_etapa']);
        $rta = $view->etapa->deleteEtapa();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;


    default : //carga la tabla de vehiculos del grupo //ok
        $view->label='Vehículos del grupo';
        $view->vehiculos = GrupoVehiculo::getVehiculos($_POST['id_grupo']);
        $view->disableLayout=true;
        $view->contentTemplate="view/grupos_vehiculos/vehiculosForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>
