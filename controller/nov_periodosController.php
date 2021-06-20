<?php
include_once("model/nov_periodosModel.php");
include_once("model/contratosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{

    case 'refreshGrid':
        $view->disableLayout=true;
        $id_contrato = ($_POST['search_contrato']!='')? $_POST['search_contrato'] : null;
        $periodo = ($_POST['search_periodo_sup']!='')? $_POST['search_periodo_sup'] : null;
        $view->periodos = NovPeriodo::getPeriodosList($id_contrato, $periodo);
        $view->contentTemplate="view/nov_periodos/periodosGrid.php";
        break;

    case 'getPeriodos': //select dependiente //ok
        $id_contrato = (($_POST['id_contrato']!='')? $_POST['id_contrato'] : null );
        $activos = (($_POST['activos']!='')? $_POST['activos'] : null );
        $rta = NovPeriodo::getPeriodos($id_contrato, $activos);
        print_r(json_encode($rta));
        exit;
        break;

    case 'getPeriodos1': //select dependiente //ok
        $id_empleado = (($_POST['id_empleado']!='')? $_POST['id_empleado'] : null );
        $activos = (($_POST['activos']!='')? $_POST['activos'] : null );
        $rta = NovPeriodo::getPeriodos1($id_empleado, $activos);
        print_r(json_encode($rta));
        exit;
        break;

    case 'cerrarPeriodo': //ok
        $periodo = new NovPeriodo($_POST['id_periodo']);
        $periodo->setClosedDate(date('Y-m-d H:i:s'));
        $rta = $periodo->updatePeriodo();
        print_r(json_encode($rta));
        exit;
        break;

    case 'abrirPeriodo': //ok
        $periodo = new NovPeriodo($_POST['id_periodo']);
        $periodo->setClosedDate(null);
        $rta = $periodo->updatePeriodo();
        print_r(json_encode($rta));
        exit;
        break;




    default :  //ok
        $view->contratos = Contrato::getContratosControlNovedades(); //carga el combo para filtrar contratos
        $view->periodos_sup = NovPeriodo::getPeriodosSup();
        $view->contentTemplate="view/nov_periodos/periodosGrid.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    include_once('view/nov_periodos/periodosLayout.php');
}


?>
