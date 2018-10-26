﻿<?php
include_once("model/obj_tareasModel.php");

//include_once("model/puestosModel.php");
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
        $view->tareas = Tarea::getTareas($_POST['id_objetivo']);
        $view->contentTemplate="view/objetivos/tareasGrid.php";
        break;

    case 'saveTarea': //ok
        $tarea = new Tarea($_POST['id_tarea']);
        $tarea->setNombre($_POST['nombre']);
        $tarea->setDescripcion($_POST['descripcion']);
        $tarea->setFechaInicio($_POST['fecha_inicio']);
        $tarea->setFechaFin($_POST['fecha_fin']);
        $tarea->setIdObjetivo($_POST['id_objetivo']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        $rta = $tarea->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newTarea': //ok
        $view->label='Nueva actividad';
        $view->tarea = new Tarea();

        $view->disableLayout=true;
        //$view->target = $_POST['target'];
        $view->contentTemplate="view/objetivos/tarea_detailForm.php";
        break;

    case 'editTarea': //ok
        $view->tarea = new Tarea($_POST['id_tarea']);
        //$view->label = ($_POST['target']!='view')? 'Editar actividad': 'Ver actividad';
        $view->label = ($_POST['target']!='view')? '<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> '.$view->tarea->getNombre() : '<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> '.$view->tarea->getNombre();

        $view->disableLayout=true;
        //$view->target = $_POST['target'];
        $view->contentTemplate="view/objetivos/tarea_detailForm.php";
        break;

    case 'deleteTarea': //ok
        $view->tarea = new Tarea($_POST['id_tarea']);
        $rta = $view->tarea->deleteTarea();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
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