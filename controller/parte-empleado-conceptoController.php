<?php
include_once("model/nov_parte-empleado-conceptoModel.php");
include_once("model/nov_parte-empleadoModel.php");
include_once("model/nov_concepto-convenio-contratoModel.php");

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
        $view->conceptos = ParteEmpleadoConcepto::getParteEmpleadoConcepto($_POST['id_parte']);
        $view->contentTemplate="view/novedades_partes/conceptosGrid.php";
        break;

    case 'saveConcepto': //ok
        $concepto = new ParteEmpleadoConcepto($_POST['id_parte_empleado_concepto']);
        $concepto->setIdParteEmpleado($_POST['id_parte_empleado']);
        $concepto->setIdConceptoConvenioContrato($_POST['id_concepto_convenio_contrato']);
        $concepto->setCantidad($_POST['cantidad']);
        $concepto->setCreatedBy($_SESSION['id_user']);
        $concepto->setMotivo($_POST['motivo']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        $rta = $concepto->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newConcepto': //ok  //abre formulario para cargar concepto nuevo (manualmente)
        $view->label='Nuevo Concepto';
        $view->concepto = new ParteEmpleadoConcepto();

        $view->empleados = ParteEmpleado::getParteEmpleado($_POST['id_parte']);

        $view->disableLayout=true;
        //$view->target = $_POST['target'];
        $view->contentTemplate="view/novedades_partes/concepto_detailForm.php";
        break;

    case 'editConcepto': //ok  //abre formulario para editar concepto cargado manualmente
        $view->label = ($_POST['target']!='view')? 'Editar concepto': 'Ver concepto';
        $view->concepto = new ParteEmpleadoConcepto($_POST['id_parte_empleado_concepto']);

        $view->empleados = ParteEmpleado::getParteEmpleado($_POST['id_parte']);

        $view->disableLayout=true;
        //$view->target = $_POST['target'];
        $view->contentTemplate="view/novedades_partes/concepto_detailForm.php";
        break;

    case 'deleteConcepto': //ok
        $view->concepto = new ParteEmpleadoConcepto($_POST['id_parte_empleado_concepto']);
        $rta = $view->concepto->deleteParteEmpleadoConcepto();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;


    case 'getConceptos': //select dependiente //ok
        $rta = ConceptoConvenioContrato::getConceptoConvenioContrato($_POST['id_contrato'], $_POST['id_convenio']);
        print_r(json_encode($rta));
        exit;
        break;


    default : //carga la tabla de empleados del parte
        //$view->label='Empleados del parte';
        //$view->empleados = ParteEmpleado::getParteEmpleado($_POST['id_parte']);
        //$view->disableLayout=true;
        //$view->contentTemplate="view/cuadrillas/empleadosForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>