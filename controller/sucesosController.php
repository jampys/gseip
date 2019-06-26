<?php

include_once("model/empleadosModel.php");
include_once("model/nov_eventosLiquidacionModel.php");
include_once("model/nov_sucesosModel.php");
include_once("model/contratosModel.php");
include_once("model/nov_periodosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        $id_empleado = ($_POST['id_empleado']!='')? $_POST['id_empleado'] : null;
        $eventos = ($_POST['eventos']!='')? implode(",", $_POST['eventos'])  : 'su.id_evento';
        $fecha_desde = ($_POST['search_fecha_desde']!='')? $_POST['search_fecha_desde'] : null;
        $fecha_hasta = ($_POST['search_fecha_hasta']!='')? $_POST['search_fecha_hasta'] : null;
        $id_contrato = ($_POST['search_contrato']!='')? $_POST['search_contrato'] : null;
        $view->sucesos = Suceso::getSucesos($id_empleado, $eventos, $fecha_desde, $fecha_hasta, $id_contrato);
        $view->contentTemplate="view/sucesos/sucesosGrid.php";
        break;

    case 'saveSuceso': //ok

        $suceso = new Suceso($_POST['id_suceso']);
        $suceso->setIdEvento($_POST['id_evento']);
        $suceso->setIdEmpleado($_POST['id_empleado']);
        $suceso->setFechaDesde($_POST['fecha_desde']);
        $suceso->setFechaHasta($_POST['fecha_hasta']);
        $suceso->setObservaciones($_POST['observaciones']);
        $suceso->setCreatedBy($_SESSION['id_user']);
        $suceso->setIdPeriodo1($_POST['id_periodo1']);
        $suceso->setCantidad1($_POST['cantidad1']);
        $suceso->setIdPeriodo2( ($_POST['id_periodo2']!='')? $_POST['id_periodo2'] : null );
        $suceso->setCantidad2( ($_POST['cantidad2']!='')? $_POST['cantidad2'] : null );
        $suceso->setFd1( ($_POST['fd1'])? $_POST['fd1'] : null  );
        $suceso->setFh1( ($_POST['fh1'])? $_POST['fh1'] : null );
        $suceso->setFd2( ($_POST['fd2'])? $_POST['fd2'] : null );
        $suceso->setFh2( ($_POST['fh2'])? $_POST['fh2'] : null );
        $rta = $suceso->save();
        print_r(json_encode(sQuery::dpLastInsertId()));
        //print_r(json_encode($rta));
        exit;
        break;

    case 'newSuceso': //ok
        $view->label='Nuevo Suceso';
        $view->suceso = new Suceso($_POST['id_suceso']);

        $view->empleados = Empleado::getEmpleadosControl(null);
        $view->eventos = EventosLiquidacion::getEventosLiquidacion();
        //$view->empleado = $view->renovacion->getEmpleado()->getApellido()." ".$view->renovacion->getEmpleado()->getNombre();

        $view->disableLayout=true;
        $view->contentTemplate="view/sucesos/sucesosForm.php";
        break;

    case 'editSuceso': //ok
        $view->suceso = new Suceso($_POST['id_suceso']);
        $view->label = ($_POST['target']!='view')? 'Editar suceso' : 'Ver suceso';

        $view->empleados = Empleado::getEmpleadosControl(null);
        $view->eventos = EventosLiquidacion::getEventosLiquidacion();
        // Si es view: trae todos los periodos, si es una edicion: trae solo los periodos activos
        $view->periodos = ($_POST['target']=='view')? NovPeriodo::getPeriodos1($view->suceso->getIdEmpleado()) : NovPeriodo::getPeriodos1($view->suceso->getIdEmpleado(), 1) ;

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/sucesos/sucesosForm.php";
        break;


    case 'deleteSuceso': //ok
        $suceso = new Suceso($_POST['id_suceso']);
        $rta = $suceso->deleteSuceso();
        print_r(json_encode($rta));
        die;
        break;


    /*case 'checkFechaDesde': //obsoleto desde 17/05/2019
        $view->suceso = new Suceso();
        $rta = $view->suceso->checkFechaDesde($_POST['fecha_desde'], $_POST['id_empleado'], $_POST['id_evento'], $_POST['id_suceso']);
        print_r(json_encode($rta));
        exit;
        break;

    case 'checkFechaHasta': //obsoleto desde 17/05/2019
        $view->suceso = new Suceso();
        $rta = $view->suceso->checkFechaHasta($_POST['fecha_hasta'], $_POST['id_empleado'], $_POST['id_evento'], $_POST['id_suceso']);
        print_r(json_encode($rta));
        exit;
        break;*/

    case 'checkRango': //ok
        $view->suceso = new Suceso();
        $rta = $view->suceso->checkRango($_POST['fecha_desde'], $_POST['fecha_hasta'], $_POST['id_empleado'], $_POST['id_evento'], $_POST['id_suceso']);
        print_r(json_encode($rta));
        exit;
        break;

    case 'loadExport': //ok  //abre ventana modal para exportar
        $view->disableLayout=true;
        $view->label = 'Exportar sucesos';
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos
        $view->eventos = EventosLiquidacion::getEventosLiquidacion(); //carga el combo para filtrar eventos liquidacion

        $view->contentTemplate="view/sucesos/exportForm.php";
        break;


    case 'txt': //ok
        $id_contrato = ($_GET['id_contrato']!='')? $_GET['id_contrato'] : null;
        $id_periodo = ($_GET['id_periodo']!='')? $_GET['id_periodo'] : null;

        //$file_name = "sucesos_c".$id_contrato."_e".$id_empleado."_fd".str_replace("/", "", $fecha_desde)."_fh".str_replace("/", "", $fecha_hasta).".txt";
        $file_name = "sucesos_c".$id_contrato."_p".$id_periodo.".txt";
        $filepath = "uploads/files/".$file_name;
        $handle = fopen($filepath, "w");
        $view->sucesos = Suceso::exportTxt($id_contrato, $id_periodo);

        foreach ($view->sucesos as $su) {
            $fd = new DateTime($su['fecha_desde']);
            $fh = new DateTime($su['fecha_hasta']);
            $pd = $ph = new DateTime($su['periodo_hasta']); //para calcular el periodo se toma el periodo_hasta
            //$d = (string)$fh->diff($fd)->days;
            //$d = (string)($fh->diff($fd)->days+1);
            $d = $su['cantidad'];

            $line = str_pad($su['evento'], 10). //evento
                str_pad(substr($su['legajo'], 2), 10). //legajo
                str_pad($pd->format('01/m/Y'), 10). //periodo desde
                str_pad($ph->format('01/m/Y'), 10). //periodo hasta
                str_pad($fd->format('d/m/Y'), 10). //fecha desde
                str_pad($fh->format('d/m/Y'), 10). //fecha hasta
                str_pad($d, 10). //dias
                str_pad($d, 10). //prorrateo dias
                str_pad("L", 10). //tipo liquidacion
                str_pad("MEN", 10). //tipo liquidacion
                str_pad("00/01/1900", 10). //fecha prevista notificacion
                str_pad("00/01/1900", 10). //fecha notificacion
                //str_pad(substr($su['observaciones'], 0, 10), 10). //observaciones
                "\r\n";

            $line_no_bom = trim($line, "\\xef\\xbb\\xbf"); //remover el bom

            fwrite($handle, $line_no_bom);
        }

        fclose($handle);
        ob_end_clean(); //remover el bom

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($filepath));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath); //descarga el archivo

        unlink ($filepath); //borra el archivo una vez descargado

        exit;
        break;

    default : //ok
        $view->empleados = Empleado::getEmpleadosControl(null); //carga el combo para filtrar empleados
        $view->eventos = EventosLiquidacion::getEventosLiquidacion(); //carga el combo para filtrar eventos liquidacion
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/sucesos/sucesosGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/sucesos/sucesosLayout.php');
}


?>
