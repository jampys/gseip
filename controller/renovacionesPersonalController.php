<?php

include_once("model/vto_renovacionesPersonalModel.php");
include_once("model/vto_vencimientosPersonalModel.php");

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        $view->renovaciones_personal = RenovacionPersonal::getRenovacionesPersonal();
        $view->contentTemplate="view/renovacionesPersonalGrid.php";
        break;

    case 'saveRenovacion':

        $renovacion = new RenovacionPersonal($_POST['id_renovacion']);
        $renovacion->setIdVencimiento($_POST['id_vencimiento']);
        $renovacion->setFechaEmision($_POST['fecha_emision']);
        $renovacion->setFechaVencimiento($_POST['fecha_vencimiento']);
        $renovacion->setIdEmpleado($_POST['id_empleado']);
        //$renovacion->setAlertStatus($_POST['alert_status']);

        $renovacion->save();
        print_r(json_encode(sQuery::dpLastInsertId()));
        exit;
        break;

    case 'newRenovacion': //ok
        $view->label='Nueva renovación';
        $view->renovacion = new RenovacionPersonal();

        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal();

        $view->empleado = $view->renovacion->getEmpleado()->getApellido()." ".$view->renovacion->getEmpleado()->getNombre();

        $view->disableLayout=true;
        $view->contentTemplate="view/renovacionesPersonalForm.php";
        break;

    case 'editRenovacion': //ok
        $view->label='Editar Renovación';
        $view->renovacion = new RenovacionPersonal($_POST['id_renovacion']);

        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal();

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

    case 'autocompletarHabilidades':
        $view->habilidad = new Habilidad();
        $rta=$view->habilidad->autocompletarHabilidades($_POST['term']);
        print_r(json_encode($rta));
        exit;
        break;

    default : //ok
        $view->renovaciones_personal = RenovacionPersonal::getRenovacionesPersonal();
        $view->contentTemplate="view/renovacionesPersonalGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/renovacionesPersonalLayout.php');
}


?>
