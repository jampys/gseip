<?php
include_once("model/etapasModel.php");
include_once("model/postulacionesModel.php");
include_once("model/postulantesModel.php");

include_once("model/puestosModel.php");
include_once("model/localidadesModel.php");
include_once("model/contratosModel.php");

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
        $rta = $view->etapas = Etapa::getEtapas($_POST['id_postulacion']);
        //$view->contentTemplate="view/postulaciones/etapasGrid.php";
        //break;
        print_r(json_encode($rta));
        exit;
        break;


    case 'saveEtapa': //ok
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

    case 'newEtapa': //ok
        $view->etapa = new Etapa($_POST['id_etapa']);
        $view->postulacion = new Postulacion($_POST['id_postulacion']);
        $view->id_postulante = new Empleado($view->postulacion->getIdPostulante());
        $view->postulante = $view->id_postulante->getApellido().' '.$view->id_postulante->getNombre();
        $view->label='Nueva etapa: '.$view->postulante;


        //$view->puestos = Puesto::getPuestos();
        $view->etapas = Soporte::get_enum_values('sel_etapas', 'etapa');
        $view->motivos = Soporte::get_enum_values('sel_etapas', 'motivo');
        $view->modos_contacto = Soporte::get_enum_values('sel_etapas', 'modo_contacto');
        $view->aplica_opts = Soporte::get_enum_values('sel_etapas', 'aplica');

        $view->disableLayout=true;
        $view->contentTemplate="view/busquedas/etapa_detailForm.php";
        break;

    case 'editEtapa': //ok
        //$view->label = ($_POST['target']!='view')? 'Editar etapa': 'Ver etapa';
        $view->etapa = new Etapa($_POST['id_etapa']);
        //$view->label = $view->etapa->getEtapa();
        $view->postulacion = new Postulacion($_POST['id_postulacion']);
        $view->id_postulante = new Postulante($view->postulacion->getIdPostulante());
        $view->postulante = $view->id_postulante->getApellido().' '.$view->id_postulante->getNombre();
        $view->label= $view->etapa->getEtapa().': '.$view->postulante;

        //$view->puestos = Puesto::getPuestos();
        $view->etapas = Soporte::get_enum_values('sel_etapas', 'etapa');
        $view->motivos = Soporte::get_enum_values('sel_etapas', 'motivo');
        $view->modos_contacto = Soporte::get_enum_values('sel_etapas', 'modo_contacto');
        $view->aplica_opts = Soporte::get_enum_values('sel_etapas', 'aplica');

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/busquedas/etapa_detailForm.php";
        break;

    case 'deleteEtapa': //ok
        $view->etapa = new Etapa($_POST['id_etapa']);
        $rta = $view->etapa->deleteEtapa();
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


    default : //carga la tabla de etapas de la postulacion //ok
        //$view->postulacion = new Postulacion($_POST['id_postulacion']);
        $view->label='Etapas de la postulación';
        $view->etapas = Etapa::getEtapas($_POST['id_postulacion']);
        //$view->localidades = Localidad::getLocalidades();
        //$view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');
        $view->disableLayout=true;
        $view->contentTemplate="view/postulaciones/etapasForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>
