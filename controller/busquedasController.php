<?php
include_once("model/busquedasModel.php");

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
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? $_POST['id_vencimiento'] : null;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrp.id_vencimiento';
        $id_puesto = ($_POST['search_puesto']!='')? $_POST['search_puesto'] : null;
        $id_localidad = ($_POST['search_localidad']!='')? $_POST['search_localidad'] : null;
        $id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        $todas = ($_POST['renovado']== 0)? null : 1;
        $view->busquedas = Busqueda::getBusquedas($id_puesto, $id_localidad, $id_contrato, $todas);
        $view->contentTemplate="view/busquedas/busquedasGrid.php";
        break;

    case 'saveRenovacion':

        $renovacion = new RenovacionPersonal($_POST['id_renovacion']);
        $renovacion->setIdVencimiento($_POST['id_vencimiento']);
        $renovacion->setFechaEmision($_POST['fecha_emision']);
        $renovacion->setFechaVencimiento($_POST['fecha_vencimiento']);
        //$renovacion->setIdEmpleado($_POST['id_empleado']);
        $renovacion->setIdEmpleado ( ($_POST['id_empleado']!='')? $_POST['id_empleado'] : null);
        //$renovacion->setIdGrupo($_POST['id_grupo']);
        $renovacion->setIdGrupo ( ($_POST['id_grupo']!='')? $_POST['id_grupo'] : null);
        $renovacion->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);

        $rta = $renovacion->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newBusqueda': //ok
        $view->label='Nueva búsqueda';
        $view->busqueda = new Busqueda();

        $view->puestos = Puesto::getPuestos();
        $view->localidades = Localidad::getLocalidades();
        $view->contratos = Contrato::getContratos();

        $view->disableLayout=true;
        $view->contentTemplate="view/busquedas/busquedasForm.php";
        break;

    case 'editBusqueda': //ok
        $view->label='Editar búsqueda';
        $view->busqueda = new Busqueda($_POST['id_busqueda']);

        $view->puestos = Puesto::getPuestos();
        $view->localidades = Localidad::getLocalidades();
        $view->contratos = Contrato::getContratos();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/busquedas/busquedasForm.php";
        break;

    case 'renovRenovacion': //Renueva una renovacion existente
        $view->label='Renovación';
        $view->renovacion = new RenovacionPersonal($_POST['id_renovacion']);
        $view->renovacion->setIdRenovacion('');
        $view->renovacion->setFechaEmision('');
        $view->renovacion->setFechaVencimiento('');

        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal();
        $view->empleadosGrupos = $view->renovacion->empleadosGrupos();

        $view->empleado = $view->renovacion->getEmpleado()->getApellido()." ".$view->renovacion->getEmpleado()->getNombre();

        $view->disableLayout=true;
        $view->contentTemplate="view/renovacionesPersonalForm.php";
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
        //$view->renovacion = new RenovacionPersonal();
        //$view->empleadosGrupos = $view->renovacion->empleadosGrupos(); //carga el combo para filtrar empleados-grupos
        //$view->vencimientos = VencimientoPersonal::getVencimientosPersonal(); //carga el combo para filtrar vencimientos
        $view->puestos = Puesto::getPuestos(); //carga el combo para filtrar puestos
        $view->localidades = Localidad::getLocalidades(); //carga el combo para filtrar localidades (Areas)
        $view->contratos = Contrato::getContratos(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/busquedas/busquedasGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/busquedas/busquedasLayout.php');
}


?>
