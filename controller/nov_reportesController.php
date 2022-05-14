<?php
include_once("model/nov_reportesModel.php");
include_once("model/contratosModel.php");
include_once("model/companiasModel.php");
include_once("model/nov_periodosModel.php");
include_once("model/empleadosModel.php");

include_once("model/nov_conceptosModel.php");
include_once("model/nov_concepto-convenio-contratoModel.php");


$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{

    case 'reporte_rn06': //ok
        $view->disableLayout=true;
        $id_contrato = ($_GET['id_contrato'])? $_GET['id_contrato'] : null;
        $id_periodo = ($_GET['id_periodo'])? $_GET['id_periodo'] : null;
        $id_empleado = ($_GET['id_empleado'])? $_GET['id_empleado'] : null;
        $id_concepto = ($_GET['id_concepto'])? $_GET['id_concepto'] : null; //viene el id_concepto_convenio_contrato
        $view->partes = $rta = ReporteNovedades::getReporteRn6($id_contrato, $id_periodo, $id_empleado, $id_concepto);

        $encabezado = array();
        $encabezado['obj_contrato'] = new Contrato($_GET['id_contrato']);
        $encabezado['contrato'] = ($encabezado['obj_contrato']->getIdContrato() > 0)? $encabezado['obj_contrato']->getNroContrato().' '.$encabezado['obj_contrato']->getNombre() : 'Todos';
        $encabezado['id_compania'] = $encabezado['obj_contrato']->getIdCompania();
        $encabezado['obj_cliente'] = new Compania($encabezado['id_compania']);
        $encabezado['cliente'] = ($encabezado['obj_cliente']->getIdCompania() > 0)? $encabezado['obj_cliente']->getRazonSocial() : 'Todos';
        $encabezado['obj_periodo'] = new NovPeriodo($_GET['id_periodo']);
        $encabezado['periodo'] = $encabezado['obj_periodo']->getFechaDesde().' - '.$encabezado['obj_periodo']->getFechaHasta();

        $encabezado['obj_empleado'] = new Empleado($_GET['id_empleado']);
        $encabezado['empleado'] = ($encabezado['obj_empleado']->getIdEmpleado() > 0)? $encabezado['obj_empleado']->getLegajo().' '.$encabezado['obj_empleado']->getApellido().' '.$encabezado['obj_empleado']->getNombre() : 'Todos';

        $encabezado['obj_concepto_convenio_contrato'] = new ConceptoConvenioContrato($_GET['id_concepto']);
        $encabezado['obj_concepto'] = new Concepto($encabezado['obj_concepto_convenio_contrato']->getIdConcepto());
        $encabezado['concepto'] = ($encabezado['obj_concepto_convenio_contrato']->getIdConceptoConvenioContrato() > 0)? $encabezado['obj_concepto']->getNombre().' ('.$encabezado['obj_concepto_convenio_contrato']->getCodigo().')' : 'Todos';


        $encabezado['fecha_emision'] = date('d/m/Y H:i');

        $view->contentTemplate="view/novedades_partes/generador_rn06.php";
        break;


    case 'reporte_rn04': //ok
        $view->disableLayout=true;
        $id_contrato = ($_GET['id_contrato'])? $_GET['id_contrato'] : null;
        $_SESSION['cal_id_contrato'] = $id_contrato;
        $id_periodo = ($_GET['id_periodo'])? $_GET['id_periodo'] : null;
        $id_empleado = ($_GET['id_empleado'])? $_GET['id_empleado'] : null;
        $id_concepto = ($_GET['id_concepto'])? $_GET['id_concepto'] : null; //viene el id_concepto_convenio_contrato
        $view->partes = $rta = ReporteNovedades::getReporteRn4($id_contrato, $id_periodo, $id_empleado, $id_concepto);
        $view->resumen = ReporteNovedades::getReporteRn4Resumen($id_contrato, $id_periodo);

        $encabezado = array();
        $encabezado['obj_contrato'] = new Contrato($_GET['id_contrato']);
        $encabezado['contrato'] = ($encabezado['obj_contrato']->getIdContrato() > 0)? $encabezado['obj_contrato']->getNroContrato().' '.$encabezado['obj_contrato']->getNombre() : 'Todos';
        $encabezado['id_compania'] = $encabezado['obj_contrato']->getIdCompania();
        $encabezado['obj_cliente'] = new Compania($encabezado['id_compania']);
        $encabezado['cliente'] = ($encabezado['obj_cliente']->getIdCompania() > 0)? $encabezado['obj_cliente']->getRazonSocial() : 'Todos';
        $encabezado['obj_periodo'] = new NovPeriodo($_GET['id_periodo']);
        $encabezado['periodo'] = $encabezado['obj_periodo']->getFechaDesde().' - '.$encabezado['obj_periodo']->getFechaHasta();

        $encabezado['obj_empleado'] = new Empleado($_GET['id_empleado']);
        $encabezado['empleado'] = ($encabezado['obj_empleado']->getIdEmpleado() > 0)? $encabezado['obj_empleado']->getLegajo().' '.$encabezado['obj_empleado']->getApellido().' '.$encabezado['obj_empleado']->getNombre() : 'Todos';

        //$encabezado['obj_concepto_convenio_contrato'] = new ConceptoConvenioContrato($_GET['id_concepto']);
        //$encabezado['obj_concepto'] = new Concepto($encabezado['obj_concepto_convenio_contrato']->getIdConcepto());
        //$encabezado['concepto'] = ($encabezado['obj_concepto_convenio_contrato']->getIdConceptoConvenioContrato() > 0)? $encabezado['obj_concepto']->getNombre().' ('.$encabezado['obj_concepto_convenio_contrato']->getCodigo().')' : 'Todos';

        $encabezado['fecha_emision'] = date('d/m/Y H:i');
        $encabezado['dh'] = ReporteNovedades::getDaysBeetweenDates($encabezado['obj_periodo']->getFechaDesde(), $encabezado['obj_periodo']->getFechaHasta());
        
        $endDatePeriod = DateTime::createFromFormat('d/m/Y', $encabezado['obj_periodo']->getFechaHasta())->format("Y-m-d H:i:s");
        $today = date("Y-m-d H:i:s");
        if($endDatePeriod <= $today) $encabezado['dh1'] = $encabezado['dh'];
        else $encabezado['dh1'] = ReporteNovedades::getDaysBeetweenDates($encabezado['obj_periodo']->getFechaDesde(), date('d/m/Y')); //date('d/m/Y') da el formato dd/mm/yyyy

        $view->contentTemplate="view/novedades_partes/generador_rn04.php";
        break;


    case 'reporte_rn03':
        $view->disableLayout=true;
        $id_contrato = ($_GET['id_contrato'])? $_GET['id_contrato'] : null;
        $periodo = ($_GET['periodo'])? $_GET['periodo'] : null;
        $view->partes = $rta = ReporteNovedades::getReporteRn3($id_contrato, $periodo);

        $encabezado = array();
        $encabezado['obj_contrato'] = new Contrato($_GET['id_contrato']); //si hay 2 o mas contratos, toma el 1ro.
        $encabezado['contratos'] = ReporteNovedades::getContratosList($_GET['id_contrato'])[0]['contrato'];
        $encabezado['template'] = $encabezado['obj_contrato']->getNovTemplate(); //si hay 2 o mas contratos, toma el 1ro.

        $encabezado['id_compania'] = $encabezado['obj_contrato']->getIdCompania();
        $encabezado['obj_cliente'] = new Compania($encabezado['id_compania']);
        $encabezado['cliente'] = ($encabezado['obj_cliente']->getIdCompania() > 0)? $encabezado['obj_cliente']->getRazonSocial() : 'Todos';

        $encabezado['periodos_list'] = NovPeriodo::getPeriodosList($_GET['id_contrato'], $_GET['periodo'] );
        $encabezado['periodo'] = $encabezado['periodos_list'][0]['nombre1'];

        $encabezado['fecha_emision'] = date('d/m/Y H:i');

        $view->contentTemplate="view/novedades_partes/generador_rn03_21.php";
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
