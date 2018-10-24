<?php
include_once("model/obj_objetivosModel.php");
include_once("model/obj_tareasModel.php");
include_once("model/obj_avancesModel.php");
include_once("model/evaluacionesModel.php");
include_once("model/puestosModel.php");
include_once("model/areasModel.php");
include_once("model/contratosModel.php");

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
        $objetivo->setMetaValor( ($_POST['meta_valor'])? $_POST['meta_valor'] : null);
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
        $view->indicadores = Soporte::get_enum_values('obj_objetivos', 'indicador');
        $view->frecuencias = Soporte::get_enum_values('obj_objetivos', 'frecuencia');
        $view->empleados = (!$_POST['id_empleado'])? Empleado::getEmpleadosActivos() : Empleado::getEmpleados(); //carga el combo de empleados

        $view->disableLayout=true;
        $view->contentTemplate="view/objetivos/objetivosForm.php";
        break;

    case 'editObjetivo': //ok
        $view->objetivo = new Objetivo($_POST['id_objetivo']);

        if($_POST['target'] == 'edit') $view->label='Editar objetivo: '.$view->objetivo->getCodigo();
        else if ($_POST['target'] == 'clone') {
            $view->label='Clonar objetivo: '.$view->objetivo->getCodigo();
            $view->objetivo->setIdObjetivo(null); //pone el id_objetivo en null para al guardar insertar uno nuevo
        }


        $view->periodos = Evaluacion::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->puestos = Puesto::getPuestos();
        $view->areas = Area::getAreas();
        $view->contratos = Contrato::getContratos();
        $view->indicadores = Soporte::get_enum_values('obj_objetivos', 'indicador');
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

    case 'detalle': //detalle del objetivo //ok
        $view->objetivo = new Objetivo($_POST['id_objetivo']);
        $view->label='Detalle objetivo: '.$view->objetivo->getCodigo();

        $view->tareas = Tarea::getTareas($_POST['id_objetivo']);
        $view->avances = Avance::getAvances($_POST['id_objetivo'], null);

        $view->disableLayout=true;
        $view->contentTemplate="view/objetivos/objetivosFormUpdate.php";
        break;


    case 'graficarGantt':
        $view->objetivo = new Objetivo($_POST['id_objetivo']);
        $rta = $view->objetivo->graficarGantt();
        print_r(json_encode($rta));
        exit;
        break;



    default : //ok //muestra la grilla de objetivos
        $view->periodos = Evaluacion::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        //$view->objetivos = Objetivo::getObjetivos($view->periodo_actual);
        $view->puestos = Puesto::getPuestos();
        $view->areas = Area::getAreas();
        $view->contratos = Contrato::getContratos();
        $view->indicadores = Soporte::get_enum_values('obj_objetivos', 'indicador');
        $view->empleados = Empleado::getEmpleadosActivos();


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
