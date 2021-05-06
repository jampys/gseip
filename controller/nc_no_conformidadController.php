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
    case 'refreshGrid':
        $view->disableLayout=true;
        $id_empleado = ($_POST['id_empleado']!='')? $_POST['id_empleado'] : null;
        $id_grupo = ($_POST['id_grupo']!='')? $_POST['id_grupo'] : null;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? $_POST['id_vencimiento'] : null;
        $id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrp.id_vencimiento';
        $id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        $id_subcontratista = ($_POST['id_subcontratista']!='')? $_POST['id_subcontratista'] : null;
        $renovado = ($_POST['renovado']== 0)? null : 1;
        $view->renovaciones_personal = RenovacionPersonal::getRenovacionesPersonal($id_empleado, $id_grupo, $id_vencimiento, $id_contrato, $id_subcontratista, $renovado);
        $view->contentTemplate="view/renovacionesPersonalGrid.php";
        break;

    case 'saveRenovacion':

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

    case 'newRenovacion':
        $view->label='Nuevo vencimiento';
        $view->renovacion = new RenovacionPersonal();

        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal();
        $view->empleadosGrupos = $view->renovacion->empleadosGrupos();

        $view->empleado = $view->renovacion->getEmpleado()->getApellido()." ".$view->renovacion->getEmpleado()->getNombre();

        $view->disableLayout=true;
        $view->contentTemplate="view/renovacionesPersonalForm.php";
        break;

    case 'editRenovacion':
        $view->label = ($_POST['target'] == 'view')? 'Ver vencimiento':'Editar vencimiento';
        $view->renovacion = new RenovacionPersonal($_POST['id_renovacion']);

        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal();
        $view->empleadosGrupos = $view->renovacion->empleadosGrupos();

        $view->empleado = $view->renovacion->getEmpleado()->getApellido()." ".$view->renovacion->getEmpleado()->getNombre();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/renovacionesPersonalForm.php";
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
        $view->contentTemplate="view/renovacionesPersonalForm.php";
        break;

    case 'deleteRenovacion':
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

    case 'checkRangoFechas':
        $view->renovacion = new RenovacionPersonal();
        $rta = $view->renovacion->checkRangoFechas($_POST['fecha_emision'], $_POST['fecha_vencimiento'], $_POST['id_empleado'], $_POST['id_grupo'], $_POST['id_vencimiento'], $_POST['id_renovacion']);
        print_r(json_encode($rta));
        exit;
        break;

    default :
        $view->renovacion = new RenovacionPersonal();
        $view->empleadosGrupos = $view->renovacion->empleadosGrupos(); //carga el combo para filtrar empleados-grupos
        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal(); //carga el combo para filtrar vencimientos
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos
        $view->subcontratistas = Subcontratista::getSubcontratistas(); //carga el combo para filtrar subcontratistas
        //$view->renovaciones_personal = RenovacionPersonal::getRenovacionesPersonal(null, null, null, null, null);
        $view->contentTemplate="view/no_conformidad/no_conformidadGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/no_conformidad/no_conformidadLayout.php');
}


?>
