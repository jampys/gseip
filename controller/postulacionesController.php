<?php
include_once("model/postulacionesModel.php");

include_once("model/puestosModel.php");
include_once("model/localidadesModel.php");
include_once("model/contratosModel.php");

include_once("model/busquedasModel.php");
include_once("model/postulantesModel.php");
include_once("model/etapasModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrp.id_vencimiento';
        $id_busqueda = ($_POST['search_busqueda']!='')? $_POST['search_busqueda'] : null;
        $id_postulante = ($_POST['search_postulante']!='')? $_POST['search_postulante'] : null;
        $todas = null; //($_POST['renovado']== 0)? null : 1;
        $view->postulaciones = Postulacion::getPostulaciones($id_busqueda, $id_postulante, $todas);
        $view->contentTemplate="view/postulaciones/postulacionesGrid.php";
        break;

    case 'savePostulacion': //ok
        $postulacion = new Postulacion($_POST['id_postulacion']);
        $postulacion->setIdBusqueda($_POST['id_busqueda']);
        $postulacion->setIdPostulante($_POST['id_postulante']);
        $postulacion->setOrigenCv($_POST['origen_cv']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        //$postulacion->setIdPuesto( ($_POST['id_puesto']!='')? $_POST['id_puesto'] : null);
        $postulacion->setExpectativas($_POST['expectativas']);
        $postulacion->setPropuestaEconomica($_POST['propuesta_economica']);

        $rta = $postulacion->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newPostulacion': //ok
        $view->label='Nueva postulación';
        $view->postulacion = new Postulacion();

        $view->busquedas = Busqueda::getBusquedasActivas();
        $view->postulantes = Postulante::getPostulantesActivos();
        $view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');

        $view->disableLayout=true;
        $view->contentTemplate="view/postulaciones/postulacionesForm.php";
        break;

    case 'editPostulacion': //ok
        $view->label = ($_POST['target'] == 'view')? 'Ver postulación':'Editar postulación';
        $view->postulacion = new Postulacion($_POST['id_postulacion']);
        
        $view->busquedas = Busqueda::getBusquedasActivas();
        $view->postulantes = Postulante::getPostulantesActivos();
        $view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/postulaciones/postulacionesForm.php";
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
        $view->busquedas = Busqueda::getBusquedasActivas(); //carga el combo para filtrar busquedas
        $view->postulantes = Postulante::getPostulantesActivos(); //carga el combo para filtrar postulantes
        //$view->contratos = Contrato::getContratos(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/postulaciones/postulacionesGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/postulaciones/postulacionesLayout.php');
}


?>
