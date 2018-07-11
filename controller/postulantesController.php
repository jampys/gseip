﻿<?php
include_once("model/postulantesModel.php");

include_once("model/puestosModel.php");
include_once("model/localidadesModel.php");
include_once("model/contratosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrp.id_vencimiento';
        $id_puesto = ($_POST['search_puesto']!='')? $_POST['search_puesto'] : null;
        $id_localidad = ($_POST['search_localidad']!='')? $_POST['search_localidad'] : null;
        $todas = null; //($_POST['renovado']== 0)? null : 1;
        $view->postulantes = Postulante::getPostulantes($id_puesto, $id_localidad, $id_contrato, $todas);
        $view->contentTemplate="view/postulantes/postulantesGrid.php";
        break;

    case 'savePostulante': //ok
        $postulante = new Postulante($_POST['id_postulante']);
        $postulante->setApellido($_POST['apellido']);
        $postulante->setNombre($_POST['nombre']);
        $postulante->setDni($_POST['dni']);
        $postulante->setListaNegra( ($_POST['lista_negra'] == 1)? 1 : null);
        //$postulante->setIdPuesto( ($_POST['id_puesto']!='')? $_POST['id_puesto'] : null);

        $rta = $postulante->save();
        print_r(json_encode(sQuery::dpLastInsertId()));
        //print_r(json_encode($rta));
        exit;
        break;

    case 'newPostulante': //ok
        $view->label='Nuevo postulante';
        $view->postulante = new Postulante();

        //$view->puestos = Puesto::getPuestos();
        //$view->localidades = Localidad::getLocalidades();
        //$view->contratos = Contrato::getContratos();

        $view->disableLayout=true;
        $view->contentTemplate="view/postulantes/postulantesForm.php";
        break;

    case 'editPostulante': //ok
        $view->label='Editar postulante';
        $view->postulante = new Postulante($_POST['id_postulante']);

        //$view->puestos = Puesto::getPuestos();
        //$view->localidades = Localidad::getLocalidades();
        //$view->contratos = Contrato::getContratos();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/postulantes/postulantesForm.php";
        break;

    case 'deleteHabilidad':
        $habilidad = new Habilidad($_POST['id_habilidad']);
        $rta = $habilidad->deleteHabilidad();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;


    case 'checkDni':
        $view->postulante = new Postulante();
        $rta = $view->postulante->checkDni($_POST['dni'], $_POST['id_postulante']);
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
        $view->puestos = Puesto::getPuestos(); //carga el combo para filtrar puestos
        $view->localidades = Localidad::getLocalidades(); //carga el combo para filtrar localidades (Areas)
        $view->contratos = Contrato::getContratos(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/postulantes/postulantesGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/postulantes/postulantesLayout.php');
}


?>