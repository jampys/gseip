<?php
include_once("model/nov_reportesModel.php");
include_once("model/contratosModel.php");
include_once("model/companiasModel.php");
include_once("model/nov_periodosModel.php");


$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{

    case 'reporte_rn5':
        $view->disableLayout=true;
        $id_contrato = ($_GET['id_contrato'])? $_GET['id_contrato'] : null;
        $id_periodo = ($_GET['id_periodo'])? $_GET['id_periodo'] : null;

        $view->partes = $rta = ReporteNovedades::getPdf($id_contrato, $id_periodo);

        $encabezado = array();
        $encabezado['obj_contrato'] = new Contrato($_GET['id_contrato']);
        $encabezado['contrato'] = ($encabezado['obj_contrato']->getIdContrato() > 0)? $encabezado['obj_contrato']->getNroContrato().' '.$encabezado['obj_contrato']->getNombre() : 'Todos';
        $encabezado['id_compania'] = $encabezado['obj_contrato']->getIdCompania();
        $encabezado['obj_cliente'] = new Compania($encabezado['id_compania']);
        $encabezado['cliente'] = ($encabezado['obj_cliente']->getIdCompania() > 0)? $encabezado['obj_cliente']->getRazonSocial() : 'Todos';
        $encabezado['obj_periodo'] = new NovPeriodo($_GET['id_periodo']);
        $encabezado['periodo'] = $encabezado['obj_periodo']->getFechaDesde().' - '.$encabezado['obj_periodo']->getFechaHasta();
        $encabezado['fecha_emision'] = date('d/m/Y H:i');

        $view->contentTemplate="view/novedades_partes/generador_rn5.php";
        break;


    default : //ok
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
