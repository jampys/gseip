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
        $id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrp.id_vencimiento';
        $id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        $id_subcontratista = ($_POST['id_subcontratista']!='')? $_POST['id_subcontratista'] : null;
        $renovado = ($_POST['renovado']== 0)? null : 1;
        $rta = $view->renovaciones_personal = RenovacionPersonal::getRenovacionesPersonal($id_empleado, $id_grupo, $id_vencimiento, $id_contrato, $id_subcontratista, $renovado);
        //$view->contentTemplate="view/renovaciones_personal/renovacionesPersonalGrid.php";
        //break;
        print_r(json_encode($rta));
        exit;
        break;

    case 'saveRenovacion': //ok

        $renovacion = new RenovacionPersonal($_POST['id_renovacion']);
        $renovacion->setIdVencimiento($_POST['id_vencimiento']);
        $renovacion->setFechaEmision($_POST['fecha_emision']);
        $renovacion->setFechaVencimiento($_POST['fecha_vencimiento']);
        $renovacion->setIdEmpleado ( ($_POST['id_empleado']!='')? $_POST['id_empleado'] : null);
        $renovacion->setIdGrupo ( ($_POST['id_grupo']!='')? $_POST['id_grupo'] : null);
        $renovacion->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        $renovacion->setReferencia($_POST['referencia']);
        $renovacion->setCreatedBy($_SESSION["id_user"]);

        $rta = $renovacion->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newRenovacion': //ok
        $view->label='Nuevo vencimiento';
        $view->renovacion = new RenovacionPersonal();

        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal();
        $view->empleadosGrupos = $view->renovacion->empleadosGrupos();

        $view->empleado = $view->renovacion->getEmpleado()->getApellido()." ".$view->renovacion->getEmpleado()->getNombre();

        $view->disableLayout=true;
        $view->contentTemplate="view/renovacionesPersonalForm.php";
        break;

    case 'editRenovacion': //ok
        $view->label = ($_POST['target'] == 'view')? 'Ver vencimiento':'Editar vencimiento';
        $view->renovacion = new RenovacionPersonal($_POST['id_renovacion']);

        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal();
        $view->empleadosGrupos = $view->renovacion->empleadosGrupos();

        $view->empleado = $view->renovacion->getEmpleado()->getApellido()." ".$view->renovacion->getEmpleado()->getNombre();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/renovaciones_personal/renovacionesPersonalForm.php";
        break;

    case 'renovRenovacion': //Renueva una renovacion existente
        $view->label='Renovar vencimiento';
        $view->renovacion = new RenovacionPersonal($_POST['id_renovacion']);
        $view->renovacion->setIdRenovacion('');
        $view->renovacion->setFechaEmision('');
        $view->renovacion->setFechaVencimiento('');
        $view->renovacion->setReferencia('');

        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal();
        $view->empleadosGrupos = $view->renovacion->empleadosGrupos();

        $view->empleado = $view->renovacion->getEmpleado()->getApellido()." ".$view->renovacion->getEmpleado()->getNombre();

        $view->disableLayout=true;
        $view->contentTemplate="view/renovaciones_personal/renovacionesPersonalForm.php";
        break;

    case 'deleteRenovacion': //ok
        /*$renovacion = new RenovacionPersonal($_POST['id_renovacion']);
        $rta = $renovacion->deleteRenovacion();
        print_r(json_encode($rta));
        die;
        break;*/

        try{
            sQuery::dpBeginTransaction();
            $renovacion = new RenovacionPersonal($_POST['id_renovacion']);
            $uploads = RenovacionPersonal::uploadsLoad($_POST['id_renovacion']);
            $renovacion->deleteRenovacion();
            foreach($uploads as $up){
                if (!file_exists($up['directory'].$up['name'])) throw new Exception('Archivo no existe.');
            }

            sQuery::dpCommit();
            foreach($uploads as $up){
                unlink($up['directory'].$up['name']);
            }
            print_r(json_encode(1));

        }catch (Exception $e){
            sQuery::dpRollback();
            throw new Exception('Error en el query.'); //para que entre en el .fail de la peticion ajax
        }


        die;
        break;

    case 'checkRangoFechas': //ok
        $view->renovacion = new RenovacionPersonal();
        $rta = $view->renovacion->checkRangoFechas($_POST['fecha_emision'], $_POST['fecha_vencimiento'], $_POST['id_empleado'], $_POST['id_grupo'], $_POST['id_vencimiento'], $_POST['id_renovacion']);
        print_r(json_encode($rta));
        exit;
        break;


    case 'reporte': //RV01
        $view->disableLayout=true;
        $id_empleado = ($_GET['id_empleado'])? $_GET['id_empleado'] : null;
        $id_grupo = ($_GET['id_grupo'])? $_GET['id_grupo'] : null;
        $id_vencimiento = ($_GET['id_vencimiento']!='')? implode(",", $_GET['id_vencimiento'])  : 'vrp.id_vencimiento';
        $id_contrato = ($_GET['id_contrato'])? $_GET['id_contrato'] : null;
        $id_subcontratista = ($_GET['id_subcontratista'])? $_GET['id_subcontratista'] : null;
        $renovado = ($_GET['id_subcontratista'])? $_GET['id_subcontratista'] : null;

        $view->vencimientos = $rta = RenovacionPersonal::getRenovacionesPersonal($id_empleado, $id_grupo, $id_vencimiento ,$id_contrato, $id_subcontratista, $renovado);
        //$cliente = ((new Contrato($_GET['id_contrato']))->getNombre())? (new Contrato($_GET['id_contrato']))->getNombre() : 'Todos';
        //$contrato = ((new Contrato($_GET['id_contrato']))->getNombre())? (new Contrato($_GET['id_contrato']))->getNombre() : 'Todos';
        //$counts = array_count_values(array_column($rta, 'tipo_ensayo')); //https://stackoverflow.com/questions/11646054/php-count-specific-array-values
        //$count_ppt = ($counts['P'])? $counts['P'] : 0;
        //$count_cert = ($counts['N'])? $counts['N'] : 0;

        $encabezado = array();
        $encabezado['obj_contrato'] = new Contrato($_GET['id_contrato']);
        $encabezado['contrato'] = ($encabezado['obj_contrato']->getIdContrato() > 0)? $encabezado['obj_contrato']->getNroContrato().' '.$encabezado['obj_contrato']->getNombre() : 'Todos';
        $encabezado['id_compania'] = $encabezado['obj_contrato']->getIdCompania();
        $encabezado['obj_cliente'] = new Compania($encabezado['id_compania']);
        $encabezado['cliente'] = ($encabezado['obj_cliente']->getIdCompania() > 0)? $encabezado['obj_cliente']->getRazonSocial() : 'Todos';
        $encabezado['cuadrilla'] = ($cuadrilla != null)? $cuadrilla : 'Todas';

        $encabezado['fecha_desde'] = date_format(date_create($_GET['fecha_desde']), 'd/m/Y');
        $encabezado['fecha_hasta'] = date_format(date_create($_GET['fecha_hasta']), 'd/m/Y');

        $encabezado['fecha_emision'] = date('d/m/Y H:i');

        $view->contentTemplate="view/renovaciones_personal/generador_rv01.php";
        break;


    default : //ok
        $view->renovacion = new RenovacionPersonal();
        $view->empleadosGrupos = $view->renovacion->empleadosGrupos(); //carga el combo para filtrar empleados-grupos
        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal(); //carga el combo para filtrar vencimientos
        $view->contratos = Contrato::getContratosControlVencimientos(); //carga el combo para filtrar contratos
        $view->subcontratistas = Subcontratista::getSubcontratistas(); //carga el combo para filtrar subcontratistas
        //$view->renovaciones_personal = RenovacionPersonal::getRenovacionesPersonal(null, null, null, null, null);
        $view->contentTemplate="view/renovaciones_personal/renovacionesPersonalGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/renovaciones_personal/renovacionesPersonalLayout.php');
}


?>
