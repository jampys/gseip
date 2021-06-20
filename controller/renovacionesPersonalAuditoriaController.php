<?php

include_once("model/vto_renovacionesPersonalModel.php");
include_once("model/vto_vencimientosPersonalModel.php");
include_once("model/contratosModel.php");
include_once("model/subcontratistasModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        $id_empleado = ($_POST['id_empleado']!='')? $_POST['id_empleado'] : null;
        $id_grupo = ($_POST['id_grupo']!='')? $_POST['id_grupo'] : null;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? $_POST['id_vencimiento'] : null;
        $id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'v.id_vencimiento';
        $id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        $id_subcontratista = ($_POST['id_subcontratista']!='')? $_POST['id_subcontratista'] : null;
        $renovado = ($_POST['renovado']== 0)? null : 1;
        $view->renovaciones_personal = RenovacionPersonal::getRenovacionesPersonalAuditoria($id_empleado, $id_grupo, $id_vencimiento, $id_contrato, $id_subcontratista, $renovado);
        $view->contentTemplate="view/renovaciones_personal_auditoria/renovacionesPersonalGrid.php";

        $view->details = array();
        $view->details['actualizados'] = 0;
        $view->details['no_aplica'] = 0;
        $view->details['vencidos'] = 0;
        $view->details['desactivados'] = 0;
        $view->details['sin_datos'] = 0;

        break;


    default : //ok
        $view->renovacion = new RenovacionPersonal();
        $view->empleadosGrupos = $view->renovacion->empleadosGrupos(); //carga el combo para filtrar empleados-grupos
        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal(); //carga el combo para filtrar vencimientos
        $view->contratos = Contrato::getContratosControlVencimientos(); //carga el combo para filtrar contratos
        $view->subcontratistas = Subcontratista::getSubcontratistas(); //carga el combo para filtrar subcontratistas
        //$view->renovaciones_personal = RenovacionPersonal::getRenovacionesPersonal(null, null, null, null, null);
        $view->contentTemplate="view/renovaciones_personal_auditoria/renovacionesPersonalGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/renovaciones_personal_auditoria/renovacionesPersonalLayout.php');
}


?>
