<?php
include_once("model/obj_objetivosModel.php");

include_once("model/puestosModel.php");
include_once("model/areasModel.php");
include_once("model/contratosModel.php");
include_once("model/evaluacionesModel.php");

//include_once("model/busquedasModel.php");
//include_once("model/postulantesModel.php");
//include_once("model/etapasModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        $periodo = ($_POST['search_periodo']!='')? $_POST['search_periodo'] : null;
        //$view->periodos = Objetivo::getPeriodos();
        //$view->periodo_actual = Soporte::getPeriodoActual();
        $view->objetivos = Objetivo::getObjetivos($periodo);
        $view->contentTemplate="view/objetivos/objetivosGrid.php";
        break;

    case 'saveObjetivo': //ok
        $objetivo = new Objetivo($_POST['id_objetivo']);
        $objetivo->setPeriodo($_POST['periodo']);
        $objetivo->setNombre($_POST['nombre']);
        $objetivo->setIdPuesto(($_POST['id_puesto'])? $_POST['id_puesto'] : null);
        $objetivo->setIdArea(($_POST['id_area'])? $_POST['id_area'] : null);
        $objetivo->setIdContrato(($_POST['id_contrato'])? $_POST['id_contrato'] : null);
        $objetivo->setMeta($_POST['meta']);
        $objetivo->setActividades($_POST['actividades']);
        $objetivo->setIndicador($_POST['indicador']);
        $objetivo->setFrecuencia($_POST['frecuencia']);
        $objetivo->setIdResponsableEjecucion($_POST['id_responsable_ejecucion']);
        $objetivo->setIdResponsableSeguimiento($_POST['id_responsable_seguimiento']);

        $rta = $objetivo->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newObjetivo': //ok
        $view->label='Nuevo objetivo';
        $view->objetivo = new Objetivo();

        $view->periodos = Evaluacion::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->puestos = Puesto::getPuestos();
        $view->areas = Area::getAreas();
        $view->contratos = Contrato::getContratos();
        $view->frecuencias = Soporte::get_enum_values('obj_objetivos', 'frecuencia');
        $view->empleados = (!$_POST['id_empleado'])? Empleado::getEmpleadosActivos() : Empleado::getEmpleados(); //carga el combo de empleados

        $view->disableLayout=true;
        $view->contentTemplate="view/objetivos/objetivosForm.php";
        break;

    case 'editObjetivo': //ok
        $view->label='Editar objetivo';
        $view->objetivo = new Objetivo($_POST['id_objetivo']);

        $view->periodos = Evaluacion::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->puestos = Puesto::getPuestos();
        $view->areas = Area::getAreas();
        $view->contratos = Contrato::getContratos();
        $view->frecuencias = Soporte::get_enum_values('obj_objetivos', 'frecuencia');
        $view->empleados = (!$_POST['id_empleado'])? Empleado::getEmpleadosActivos() : Empleado::getEmpleados(); //carga el combo de empleados

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/objetivos/objetivosForm.php";
        break;


    case 'deleteObjetivo': //ok
        $objetivo = new Objetivo($_POST['id_objetivo']);
        $rta = $objetivo->deleteObjetivo();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;
    

    default : //ok
        $view->periodos = Objetivo::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        //$view->objetivos = Objetivo::getObjetivos($view->periodo_actual);
        $view->contentTemplate="view/objetivos/objetivosGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/objetivos/objetivosLayout.php');
}


?>
