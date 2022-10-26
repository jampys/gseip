<?php
include_once("model/cap_capacitacionesModel.php");


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
        $encabezado['periodo'] = substr($encabezado['obj_periodo']->getNombre(), 0, 8).' ('.$encabezado['obj_periodo']->getFechaDesde().' - '.$encabezado['obj_periodo']->getFechaHasta().')';

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
        $encabezado['periodo'] = substr($encabezado['obj_periodo']->getNombre(), 0, 8).' ('.$encabezado['obj_periodo']->getFechaDesde().' - '.$encabezado['obj_periodo']->getFechaHasta().')';

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


    case 'reporte_rn03': //ok
        $view->disableLayout=true;
        $id_contrato = ($_GET['id_contrato'])? $_GET['id_contrato'] : null;
        $periodo = ($_GET['periodo'])? $_GET['periodo'] : null;
        $view->partes = $rta = ReporteNovedades::getReporteRn3($id_contrato, $periodo);

        $encabezado = array();
        $encabezado['obj_contrato'] = new Contrato($_GET['id_contrato']); //si hay 2 o mas contratos, toma el 1ro.
        $encabezado['contratos'] = ReporteNovedades::getContratosList($_GET['id_contrato'])[0]['contrato'];
        //$encabezado['template'] = $encabezado['obj_contrato']->getNovTemplate();
        $encabezado['template'] = ($_GET['count_contrato'] < 7)? $encabezado['obj_contrato']->getNovTemplate() : 'GENERAL';

        $encabezado['id_compania'] = $encabezado['obj_contrato']->getIdCompania();
        $encabezado['obj_cliente'] = new Compania($encabezado['id_compania']);
        $encabezado['cliente'] = $encabezado['obj_cliente']->getRazonSocial();

        $encabezado['periodos_list'] = NovPeriodo::getPeriodosList($_GET['id_contrato'], $_GET['periodo'] );
        $encabezado['periodo'] = $encabezado['periodos_list'][0]['nombre1'];

        $encabezado['fecha_emision'] = date('d/m/Y H:i');

        $view->contentTemplate="view/novedades_partes/generador_rn03.php";
        break;


    case 'reporte_rn07': //ok
        $view->disableLayout=true;
        $id_contrato = ($_GET['id_contrato'])? $_GET['id_contrato'] : null;
        $periodo = ($_GET['periodo'])? $_GET['periodo'] : null;
        $view->resumen = $rta = ReporteNovedades::getReporteRn7Resumen($id_contrato, $periodo);

        $encabezado = array();
        //$encabezado['obj_contrato'] = new Contrato($_GET['id_contrato']); //si hay 2 o mas contratos, toma el 1ro.
        $encabezado['contratos'] = ReporteNovedades::getContratosList($_GET['id_contrato'])[0]['contrato'];
        //$encabezado['template'] = $encabezado['obj_contrato']->getNovTemplate();

        //$encabezado['id_compania'] = $encabezado['obj_contrato']->getIdCompania();
        //$encabezado['obj_cliente'] = new Compania($encabezado['id_compania']);
        //$encabezado['cliente'] = $encabezado['obj_cliente']->getRazonSocial();

        $encabezado['periodos_list'] = NovPeriodo::getPeriodosList($_GET['id_contrato'], $_GET['periodo'] );
        $encabezado['periodo'] = $encabezado['periodos_list'][0]['nombre1'];

        $encabezado['fecha_emision'] = date('d/m/Y H:i');

        $view->contentTemplate="view/novedades_partes/generador_rn07.php";
        break;


    case 'reporte_rn08': //ok
        $view->disableLayout=true;
        $id_contrato = ($_GET['id_contrato'])? $_GET['id_contrato'] : null;
        $_SESSION['cal_id_contrato'] = $id_contrato;
        $id_periodo = ($_GET['id_periodo'])? $_GET['id_periodo'] : null;
        $id_empleado = ($_GET['id_empleado'])? $_GET['id_empleado'] : null;
        $view->partes = $rta = ReporteNovedades::getReporteRn8($id_contrato, $id_periodo, $id_empleado);

        $encabezado = array();
        $encabezado['obj_contrato'] = new Contrato($_GET['id_contrato']);
        $encabezado['contrato'] = ($encabezado['obj_contrato']->getIdContrato() > 0)? $encabezado['obj_contrato']->getNroContrato().' '.$encabezado['obj_contrato']->getNombre() : 'Todos';
        $encabezado['id_compania'] = $encabezado['obj_contrato']->getIdCompania();
        $encabezado['obj_cliente'] = new Compania($encabezado['id_compania']);
        $encabezado['cliente'] = ($encabezado['obj_cliente']->getIdCompania() > 0)? $encabezado['obj_cliente']->getRazonSocial() : 'Todos';
        $encabezado['obj_periodo'] = new NovPeriodo($_GET['id_periodo']);
        $encabezado['periodo'] = substr($encabezado['obj_periodo']->getNombre(), 0, 8).' ('.$encabezado['obj_periodo']->getFechaDesde().' - '.$encabezado['obj_periodo']->getFechaHasta().')';

        $encabezado['obj_empleado'] = new Empleado($_GET['id_empleado']);
        $encabezado['empleado'] = ($encabezado['obj_empleado']->getIdEmpleado() > 0)? $encabezado['obj_empleado']->getLegajo().' '.$encabezado['obj_empleado']->getApellido().' '.$encabezado['obj_empleado']->getNombre() : 'Todos';

        $encabezado['obj_concepto_convenio_contrato'] = new ConceptoConvenioContrato($_GET['id_concepto']);
        $encabezado['obj_concepto'] = new Concepto($encabezado['obj_concepto_convenio_contrato']->getIdConcepto());
        $encabezado['concepto'] = ($encabezado['obj_concepto_convenio_contrato']->getIdConceptoConvenioContrato() > 0)? $encabezado['obj_concepto']->getNombre().' ('.$encabezado['obj_concepto_convenio_contrato']->getCodigo().')' : 'Todos';


        $encabezado['fecha_emision'] = date('d/m/Y H:i');

        $view->contentTemplate="view/novedades_partes/generador_rn08.php";
        break;



    case 'reporte_rn09':
        $view->disableLayout=true;
        $id_contrato = ($_GET['id_contrato'])? $_GET['id_contrato'] : null;
        $_SESSION['cal_id_contrato'] = $id_contrato;
        $id_periodo = ($_GET['id_periodo'])? $_GET['id_periodo'] : null;
        $view->partes = $rta = ReporteNovedades::getReporteRn9($id_contrato, $id_periodo);

        $encabezado = array();
        $encabezado['obj_contrato'] = new Contrato($_GET['id_contrato']);
        $encabezado['contrato'] = ($encabezado['obj_contrato']->getIdContrato() > 0)? $encabezado['obj_contrato']->getNroContrato().' '.$encabezado['obj_contrato']->getNombre() : 'Todos';
        $encabezado['id_compania'] = $encabezado['obj_contrato']->getIdCompania();
        $encabezado['obj_cliente'] = new Compania($encabezado['id_compania']);
        $encabezado['cliente'] = ($encabezado['obj_cliente']->getIdCompania() > 0)? $encabezado['obj_cliente']->getRazonSocial() : 'Todos';
        $encabezado['obj_periodo'] = new NovPeriodo($_GET['id_periodo']);
        $encabezado['periodo'] = substr($encabezado['obj_periodo']->getNombre(), 0, 8).' ('.$encabezado['obj_periodo']->getFechaDesde().' - '.$encabezado['obj_periodo']->getFechaHasta().')';

        $encabezado['obj_empleado'] = new Empleado($_GET['id_empleado']);
        $encabezado['empleado'] = ($encabezado['obj_empleado']->getIdEmpleado() > 0)? $encabezado['obj_empleado']->getLegajo().' '.$encabezado['obj_empleado']->getApellido().' '.$encabezado['obj_empleado']->getNombre() : 'Todos';

        $encabezado['obj_concepto_convenio_contrato'] = new ConceptoConvenioContrato($_GET['id_concepto']);
        $encabezado['obj_concepto'] = new Concepto($encabezado['obj_concepto_convenio_contrato']->getIdConcepto());
        $encabezado['concepto'] = ($encabezado['obj_concepto_convenio_contrato']->getIdConceptoConvenioContrato() > 0)? $encabezado['obj_concepto']->getNombre().' ('.$encabezado['obj_concepto_convenio_contrato']->getCodigo().')' : 'Todos';


        $encabezado['fecha_emision'] = date('d/m/Y H:i');

        $view->contentTemplate="view/novedades_partes/generador_rn09.php";
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
