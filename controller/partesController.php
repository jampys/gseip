<?php
include_once("model/nov_partesModel.php");

include_once("model/nov_areasModel.php");
include_once("model/contratosModel.php");

include_once("model/cuadrillasModel.php");
include_once("model/vehiculosModel.php");
include_once("model/nov_eventosCuadrillaModel.php");
include_once("model/empleadosModel.php");

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
        //$id_contrato = ($_POST['search_contrato']!='')? $_POST['search_contrato'] : null;
        //$todas = null; //($_POST['renovado']== 0)? null : 1;
        $view->partes = Parte::getPartes($id_puesto, $id_localidad, $id_contrato, $todas);
        $view->contentTemplate="view/novedades_partes/partesGrid.php";
        break;

    case 'insertPartes': //guarda de manera masiva los partes seleccionados
        $busqueda = new Busqueda($_POST['id_busqueda']);
        $busqueda->setNombre($_POST['nombre']);
        $busqueda->setFechaApertura($_POST['fecha_apertura']);
        $busqueda->setFechaCierre( ($_POST['fecha_cierre']!='')? $_POST['fecha_cierre'] : null );
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        $busqueda->setIdPuesto( ($_POST['id_puesto']!='')? $_POST['id_puesto'] : null);
        $busqueda->setIdLocalidad( ($_POST['id_localidad']!='')? $_POST['id_localidad'] : null);
        $busqueda->setIdContrato( ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null);

        $rta = $busqueda->save();
        print_r(json_encode(sQuery::dpLastInsertId()));
        //print_r(json_encode($rta));
        exit;
        break;

    case 'saveParte': //guarda un parte despues de ser editado
        $busqueda = new Busqueda($_POST['id_busqueda']);
        $busqueda->setNombre($_POST['nombre']);
        $busqueda->setFechaApertura($_POST['fecha_apertura']);
        $busqueda->setFechaCierre( ($_POST['fecha_cierre']!='')? $_POST['fecha_cierre'] : null );
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        $busqueda->setIdPuesto( ($_POST['id_puesto']!='')? $_POST['id_puesto'] : null);
        $busqueda->setIdLocalidad( ($_POST['id_localidad']!='')? $_POST['id_localidad'] : null);
        $busqueda->setIdContrato( ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null);

        $rta = $busqueda->save();
        print_r(json_encode(sQuery::dpLastInsertId()));
        //print_r(json_encode($rta));
        exit;
        break;

    case 'newParte': //ok
        $view->label='Nuevo parte';
        $view->parte = new Parte();

        $view->empleados = Empleado::getEmpleados();
        $view->areas = NovArea::getAreas();
        $view->vehiculos = Vehiculo::getVehiculos();
        $view->eventos = EventosCuadrilla::getEventosCuadrilla();

        $view->cuadrillas = Cuadrilla::getCuadrillas($_POST['add_contrato'], null);

        $view->disableLayout=true;
        $view->contentTemplate="view/novedades_partes/partesForm.php";
        break;

    case 'editBusqueda':
        $view->label='Editar búsqueda';
        $view->busqueda = new Busqueda($_POST['id_busqueda']);

        $view->puestos = Puesto::getPuestos();
        $view->localidades = Localidad::getLocalidades();
        $view->contratos = Contrato::getContratos();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/busquedas/busquedasForm.php";
        break;

    case 'deleteHabilidad':
        $habilidad = new Habilidad($_POST['id_habilidad']);
        $rta = $habilidad->deleteHabilidad();
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

    default : //ok
        $view->areas = NovArea::getAreas(); //carga el combo para filtrar Areas
        $view->contratos = Contrato::getContratos(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/novedades_partes/partesGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/novedades_partes/partesLayout.php');
}


?>
