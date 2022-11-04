<?php
include_once("model/nov_periodosModel.php");
include_once("model/cap_capacitacionesModel.php");
include_once("model/cap_reportesModel.php");


$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{

    case 'loadReportes': //ok //abre ventana modal para reportes
        $view->disableLayout=true;
        $view->label = 'Reportes capacitaciones';
        $view->periodos = Capacitacion::getPeriodos();

        $view->contentTemplate="view/capacitaciones/reportesForm.php";
        break;


    case 'reporte_rc01':
        $view->disableLayout=true;
        //$id_contrato = ($_GET['id_contrato'])? $_GET['id_contrato'] : null;
        //$_SESSION['cal_id_contrato'] = $id_contrato;
        $periodo = ($_GET['periodo'])? $_GET['periodo'] : null;
        //$id_empleado = ($_GET['id_empleado'])? $_GET['id_empleado'] : null;
        $view->empleados = $rta = ReporteCapacitacion::getReporteRc01($periodo);

        $encabezado = array();
        //$encabezado['obj_contrato'] = new Contrato($_GET['id_contrato']);
        //$encabezado['contrato'] = ($encabezado['obj_contrato']->getIdContrato() > 0)? $encabezado['obj_contrato']->getNroContrato().' '.$encabezado['obj_contrato']->getNombre() : 'Todos';
        //$encabezado['id_compania'] = $encabezado['obj_contrato']->getIdCompania();
        //$encabezado['obj_cliente'] = new Compania($encabezado['id_compania']);
        //$encabezado['cliente'] = ($encabezado['obj_cliente']->getIdCompania() > 0)? $encabezado['obj_cliente']->getRazonSocial() : 'Todos';
        //$encabezado['obj_periodo'] = new NovPeriodo($_GET['id_periodo']);
        //$encabezado['periodo'] = substr($encabezado['obj_periodo']->getNombre(), 0, 8).' ('.$encabezado['obj_periodo']->getFechaDesde().' - '.$encabezado['obj_periodo']->getFechaHasta().')';
        $encabezado['periodo'] = $periodo;
        //$encabezado['obj_empleado'] = new Empleado($_GET['id_empleado']);
        //$encabezado['empleado'] = ($encabezado['obj_empleado']->getIdEmpleado() > 0)? $encabezado['obj_empleado']->getLegajo().' '.$encabezado['obj_empleado']->getApellido().' '.$encabezado['obj_empleado']->getNombre() : 'Todos';

        //$encabezado['obj_concepto_convenio_contrato'] = new ConceptoConvenioContrato($_GET['id_concepto']);
        //$encabezado['obj_concepto'] = new Concepto($encabezado['obj_concepto_convenio_contrato']->getIdConcepto());
        //$encabezado['concepto'] = ($encabezado['obj_concepto_convenio_contrato']->getIdConceptoConvenioContrato() > 0)? $encabezado['obj_concepto']->getNombre().' ('.$encabezado['obj_concepto_convenio_contrato']->getCodigo().')' : 'Todos';


        $encabezado['fecha_emision'] = date('d/m/Y H:i');

        $view->contentTemplate="view/capacitaciones/generador_rc01.php";
        break;


    default :
        $view->areas = NovArea::getAreas(); //carga el combo para filtrar Areas
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/novedades_partes/partesGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/novedades_partes/partesLayout.php');
}


?>
