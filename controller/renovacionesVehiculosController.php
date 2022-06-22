<?php

include_once("model/vto_renovacionesVehiculosModel.php");
include_once("model/vto_vencimientosVehiculosModel.php");
include_once("model/contratosModel.php");
include_once("model/subcontratistasModel.php");
include_once("model/vto_gruposVehiculosModel.php");
include_once("model/nov_reportesModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        $id_vehiculo = ($_POST['id_vehiculo']!='')? $_POST['id_vehiculo'] : null;
        $id_grupo = ($_POST['id_grupo']!='')? $_POST['id_grupo'] : null;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? $_POST['id_vencimiento'] : null;
        $id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrv.id_vencimiento';
        $id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        $id_subcontratista = ($_POST['id_subcontratista']!='')? $_POST['id_subcontratista'] : null;
        $renovado = ($_POST['renovado']== 0)? null : 1;
        $rta = $view->renovaciones_vehiculos = RenovacionVehicular::getRenovacionesVehiculos($id_vehiculo, $id_grupo, $id_vencimiento, $id_contrato, $id_subcontratista, $renovado);
        //$view->contentTemplate="view/renovaciones_vehiculos/renovacionesVehiculosGrid.php";
        //break;
        print_r(json_encode($rta));
        exit;
        break;

    case 'saveRenovacion': //ok

        $renovacion = new RenovacionVehicular($_POST['id_renovacion']);
        $renovacion->setIdVencimiento($_POST['id_vencimiento']);
        $renovacion->setFechaEmision($_POST['fecha_emision']);
        $renovacion->setFechaVencimiento($_POST['fecha_vencimiento']);
        $renovacion->setIdVehiculo ( ($_POST['id_vehiculo']!='')? $_POST['id_vehiculo'] : null);
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
        $view->renovacion = new RenovacionVehicular();

        $view->vencimientos = VencimientoVehicular::getVencimientosVehiculos();
        $view->vehiculosGrupos = $view->renovacion->vehiculosGrupos();

        $view->vehiculo = $view->renovacion->getVehiculo()->getMatricula()." ".$view->renovacion->getVehiculo()->getNroMovil();

        $view->disableLayout=true;
        $view->contentTemplate="view/renovaciones_vehiculos/renovacionesVehiculosForm.php";
        break;

    case 'editRenovacion': //ok
        $view->label = ($_POST['target'] == 'view')? 'Ver vencimiento':'Editar vencimiento';
        $view->renovacion = new RenovacionVehicular($_POST['id_renovacion']);

        $view->vencimientos = VencimientoVehicular::getVencimientosVehiculos();
        $view->vehiculosGrupos = $view->renovacion->vehiculosGrupos();

        $view->vehiculo = $view->renovacion->getVehiculo()->getMatricula()." ".$view->renovacion->getVehiculo()->getNroMovil();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/renovaciones_vehiculos/renovacionesVehiculosForm.php";
        break;

    case 'renovRenovacion': //Renueva una renovacion existente //ok
        $view->label='Renovar vencimiento';
        $view->renovacion = new RenovacionVehicular($_POST['id_renovacion']);
        $view->renovacion->setIdRenovacion('');
        $view->renovacion->setFechaEmision('');
        $view->renovacion->setFechaVencimiento('');
        $view->renovacion->setReferencia('');

        $view->vencimientos = VencimientoVehicular::getVencimientosVehiculos();
        $view->vehiculosGrupos = $view->renovacion->vehiculosGrupos();

        $view->vehiculo = $view->renovacion->getVehiculo()->getMatricula()." ".$view->renovacion->getVehiculo()->getNroMovil();

        $view->disableLayout=true;
        $view->contentTemplate="view/renovaciones_vehiculos/renovacionesVehiculosForm.php";
        break;


    case 'deleteRenovacion': //ok
        /*$renovacion = new RenovacionVehicular($_POST['id_renovacion']);
        $rta = $renovacion->deleteRenovacion();
        print_r(json_encode($rta));
        die;
        break;*/

        try{
            sQuery::dpBeginTransaction();
            $renovacion = new RenovacionVehicular($_POST['id_renovacion']);
            $uploads = RenovacionVehicular::uploadsLoad($_POST['id_renovacion']);
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
        $view->renovacion = new RenovacionVehicular();
        $rta = $view->renovacion->checkRangoFechas($_POST['fecha_emision'], $_POST['fecha_vencimiento'], $_POST['id_vehiculo'], $_POST['id_grupo'], $_POST['id_vencimiento'], $_POST['id_renovacion']);
        print_r(json_encode($rta));
        exit;
        break;


    case 'reporte': //RV02
        $view->disableLayout=true;
        $id_vehiculo = ($_GET['id_vehiculo'])? $_GET['id_vehiculo'] : null;
        $id_grupo = ($_GET['id_grupo'])? $_GET['id_grupo'] : null;
        $id_vencimiento = ($_GET['id_vencimiento'])? $_GET['id_vencimiento'] : 'vrp.id_vencimiento';
        $id_contrato = ($_GET['id_contrato'])? $_GET['id_contrato'] : null;
        $id_subcontratista = ($_GET['id_subcontratista'])? $_GET['id_subcontratista'] : null;
        $renovado = ($_GET['renovado']== 0)? null : 1;

        $view->vencimientos = $rta = RenovacionPersonal::getRenovacionesPersonal($id_vehiculo, $id_grupo, $id_vencimiento ,$id_contrato, $id_subcontratista, $renovado);

        $encabezado = array();
        $encabezado['obj_vehiculo'] = new Vehiculo($_GET['id_vehiculo']);
        $encabezado['vehiculo'] = ($encabezado['obj_vehiculo']->getIdVehiculo() > 0)? $encabezado['obj_vehiculo']->getNroMovil().' '.$encabezado['obj_vehiculo']->getMatricula() : 'Todos';
        $encabezado['obj_grupo'] = new Grupo($_GET['id_grupo']);
        $encabezado['grupo'] = ($encabezado['obj_grupo']->getIdGrupo() > 0)? $encabezado['obj_grupo']->getNombre().' '.$encabezado['obj_grupo']->getNroReferencia() : 'Todos';
        $encabezado['obj_contrato'] = new Contrato($_GET['id_contrato']);
        $encabezado['contrato'] = ($encabezado['obj_contrato']->getIdContrato() > 0)? $encabezado['obj_contrato']->getNroContrato().' '.$encabezado['obj_contrato']->getNombre() : 'Todos';
        $encabezado['vencimientos'] = ($_GET['id_vencimiento']!='')? ReporteNovedades::getVencimientosPersonalList($_GET['id_vencimiento'])[0]['vencimientos'] : 'Todos';
        $encabezado['obj_subcontratista'] = new Subcontratista($_GET['id_subcontratista']);
        $encabezado['subcontratista'] = ($encabezado['obj_subcontratista']->getIdSubcontratista() > 0)? $encabezado['obj_subcontratista']->getRazonSocial() : 'Todos';
        $encabezado['fecha_emision'] = date('d/m/Y H:i');

        $view->contentTemplate="view/renovaciones_vehiculos/generador_rv02.php";
        break;


    default : //ok
        $view->renovacion = new RenovacionVehicular();
        $view->vehiculosGrupos = $view->renovacion->vehiculosGrupos(); //carga el combo para filtrar vehiculos-grupos
        $view->vencimientos = VencimientoVehicular::getVencimientosVehiculos(); //carga el combo para filtrar vencimientos
        $view->contratos = Contrato::getContratosControlVencimientos(); //carga el combo para filtrar contratos
        $view->subcontratistas = Subcontratista::getSubcontratistas(); //carga el combo para filtrar subcontratistas
        $view->contentTemplate="view/renovaciones_vehiculos/renovacionesVehiculosGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/renovaciones_vehiculos/renovacionesVehiculosLayout.php');
}


?>
