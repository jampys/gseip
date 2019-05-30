<?php
include_once("model/nov_partesModel.php");
include_once("model/nov_parte-empleadoModel.php");
include_once("model/nov_parte-ordenModel.php");
include_once("model/nov_parte-empleado-conceptoModel.php");

include_once("model/nov_areasModel.php");
include_once("model/contratosModel.php");

include_once("model/cuadrillasModel.php");
include_once("model/vehiculosModel.php");
include_once("model/nov_eventosCuadrillaModel.php");
include_once("model/empleadosModel.php");
include_once("model/nov_periodosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{

    case 'loadExportTxt': //  //abre ventana modal para exportar
        $view->disableLayout=true;
        $view->label = 'Exportar novedades';
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos

        $view->contentTemplate="view/novedades_partes/export_txtForm.php";
        break;

    case 'checkExportTxt': // //chequea que no existan partes sin calcular
        //$parte = new Parte($_POST['id_parte']);
        //$rta = $parte->save();
        $rta = Parte::checkExportTxt($_POST['id_contrato'], $_POST['id_periodo']);
        //print_r(json_encode(sQuery::dpLastInsertId()));
        //print_r(json_encode($rta));
        print_r(json_encode($rta));
        exit;
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
