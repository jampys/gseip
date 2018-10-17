<?php
include_once("model/obj_avancesModel.php");
include_once("model/obj_tareasModel.php");

//include_once("model/localidadesModel.php");
//include_once("model/contratosModel.php");

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
        //$view->busquedas = Busqueda::getBusquedas($id_puesto, $id_localidad, $id_contrato, $todas);
        $view->avances = Avance::getAvances($_POST['id_objetivo']);
        $view->contentTemplate="view/objetivos/avancesGrid.php";
        break;

    case 'saveAvance': //ok
        $avance = new Avance($_POST['id_avance']);
        $avance->setIdAvance($_POST['id_avance']);
        $avance->setIdObjetivo($_POST['id_objetivo']);
        $avance->setIdTarea($_POST['id_tarea']);
        $avance->setFecha($_POST['fecha']);
        $avance->setIndicador($_POST['indicador']);
        $avance->setCantidad($_POST['cantidad']);
        $avance->setComentarios($_POST['comentarios']);
        $avance->setIdUser($_SESSION['id_user']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        //$busqueda->setIdLocalidad( ($_POST['id_localidad']!='')? $_POST['id_localidad'] : null);
        $rta = $avance->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newAvance': //ok
        $view->label='Nuevo avance';
        $view->avance = new Avance($_POST['id_avance']);

        $view->tareas = Tarea::getTareas($_POST['id_objetivo']);
        $view->indicadores = Soporte::get_enum_values('obj_objetivos', 'indicador');

        $view->disableLayout=true;
        $view->contentTemplate="view/objetivos/avance_detailForm.php";
        break;

    case 'editAvance': //ok
        $view->label = ($_POST['target']!='view')? 'Editar avance': 'Ver avance';
        $view->avance = new Avance($_POST['id_avance']);

        $view->tareas = Tarea::getTareas($_POST['id_objetivo']);
        $view->indicadores = Soporte::get_enum_values('obj_objetivos', 'indicador');

        $view->disableLayout=true;
        //$view->target = $_POST['target'];
        $view->contentTemplate="view/objetivos/avance_detailForm.php";
        break;

    case 'deleteAvance': //ok
        $view->avance = new Avance($_POST['id_avance']);
        $rta = $view->avance->deleteAvance();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    default : //carga la tabla de avance del objetivo
        //$view->postulacion = new Postulacion($_POST['id_postulacion']);
        //$view->label='Avance del objetivo';
        //$view->etapas = Etapa::getEtapas($_POST['id_postulacion']);
        //$view->localidades = Localidad::getLocalidades();
        //$view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');
        //$view->disableLayout=true;
        //$view->contentTemplate="view/objetivos/avancesForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>
