<?php

include_once("model/vto_renovacionesPersonalModel.php");
include_once("model/vto_vencimientosPersonalModel.php");
include_once("model/contratosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        $id_empleado = ($_POST['id_empleado']!='')? $_POST['id_empleado'] : null;
        $id_grupo = ($_POST['id_grupo']!='')? $_POST['id_grupo'] : null;
        $id_vencimiento = ($_POST['id_vencimiento']!='')? $_POST['id_vencimiento'] : null;
        $id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        $renovado = ($_POST['renovado']== 0)? null : 1;
        $view->renovaciones_personal = RenovacionPersonal::getRenovacionesPersonal($id_empleado, $id_grupo, $id_vencimiento, $id_contrato, $renovado);
        $view->contentTemplate="view/renovacionesPersonalGrid.php";
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

        $rta = $renovacion->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newRenovacion':
        $view->label='Nueva renovación';
        $view->renovacion = new RenovacionPersonal();

        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal();
        $view->empleadosGrupos = $view->renovacion->empleadosGrupos();

        $view->empleado = $view->renovacion->getEmpleado()->getApellido()." ".$view->renovacion->getEmpleado()->getNombre();

        $view->disableLayout=true;
        $view->contentTemplate="view/renovacionesPersonalForm.php";
        break;

    case 'editRenovacion':
        $view->label='Editar Renovación';
        $view->renovacion = new RenovacionPersonal($_POST['id_renovacion']);

        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal();
        $view->empleadosGrupos = $view->renovacion->empleadosGrupos();

        $view->empleado = $view->renovacion->getEmpleado()->getApellido()." ".$view->renovacion->getEmpleado()->getNombre();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/renovacionesPersonalForm.php";
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

    default :
        $view->renovacion = new RenovacionPersonal();
        $view->empleadosGrupos = $view->renovacion->empleadosGrupos(); //carga el combo para filtrar empleados-grupos
        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal(); //carga el combo para filtrar vencimientos
        $view->contratos = Contrato::getContratos(); //carga el combo para filtrar contratos
        //$view->renovaciones_personal = RenovacionPersonal::getRenovacionesPersonal(null, null, null, null, null);
        $view->contentTemplate="view/renovacionesPersonalGrid.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    include_once('view/renovacionesPersonalLayout.php');
}


?>
