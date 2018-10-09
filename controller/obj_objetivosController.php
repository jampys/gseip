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
    case 'refreshGrid':
        $view->disableLayout=true;
        $periodo = ($_POST['search_periodo']!='')? $_POST['search_periodo'] : null;
        //$view->periodos = Objetivo::getPeriodos();
        //$view->periodo_actual = Soporte::getPeriodoActual();
        $view->objetivos = Objetivo::getObjetivos($periodo);
        $view->contentTemplate="view/objetivos/objetivosGrid.php";
        break;

    case 'savePostulacion':
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
