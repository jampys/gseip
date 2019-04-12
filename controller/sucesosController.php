<?php

include_once("model/empleadosModel.php");
include_once("model/nov_eventosLiquidacionModel.php");
include_once("model/nov_sucesosModel.php");
include_once("model/contratosModel.php");

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
        $view->contentTemplate="view/sucesosGrid.php";
        break;

    case 'saveSuceso': //ok

        $suceso = new Suceso($_POST['id_suceso']);
        $suceso->setIdEvento($_POST['id_evento']);
        $suceso->setIdEmpleado($_POST['id_empleado']);
        $suceso->setFechaDesde($_POST['fecha_desde']);
        $suceso->setFechaHasta($_POST['fecha_hasta']);
        $suceso->setObservaciones($_POST['observaciones']);
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
        $view->contentTemplate="view/sucesosForm.php";
        break;

    case 'editSuceso': //ok
        $view->label='Editar Suceso';
        $view->suceso = new Suceso($_POST['id_suceso']);

        $view->empleados = Empleado::getEmpleadosControl(null);
        $view->eventos = EventosLiquidacion::getEventosLiquidacion();
        //$view->empleado = $view->renovacion->getEmpleado()->getApellido()." ".$view->renovacion->getEmpleado()->getNombre();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/sucesosForm.php";
        break;


    case 'deleteSuceso': //ok
        $suceso = new Suceso($_POST['id_suceso']);
        $rta = $suceso->deleteSuceso();
        print_r(json_encode($rta));
        die;
        break;


    case 'checkFechaDesde': //ok
        $view->suceso = new Suceso();
        $rta = $view->suceso->checkFechaDesde($_POST['fecha_desde'], $_POST['id_empleado'], $_POST['id_evento'], $_POST['id_suceso']);
        print_r(json_encode($rta));
        exit;
        break;

    case 'checkFechaHasta': //ok
        $view->suceso = new Suceso();
        $rta = $view->suceso->checkFechaHasta($_POST['fecha_hasta'], $_POST['id_empleado'], $_POST['id_evento'], $_POST['id_suceso']);
        print_r(json_encode($rta));
        exit;
        break;


    case 'txt': //ok
        $id_empleado = ($_GET['id_empleado']!='')? $_GET['id_empleado'] : null;
        //$eventos = ($_GET['eventos']!='')? implode(",", $_GET['eventos'])  : 'su.id_evento';
        $eventos = ($_GET['eventos']!='')? $_GET['eventos']  : 'su.id_evento'; //con get los multiples eventos ya vienen separados por comas, en cambio con post vienen en un array
        $fecha_desde = ($_GET['search_fecha_desde']!='')? $_GET['search_fecha_desde'] : null;
        $fecha_hasta = ($_GET['search_fecha_hasta']!='')? $_GET['search_fecha_hasta'] : null;
        $id_contrato = ($_GET['search_contrato']!='')? $_GET['search_contrato'] : null;

        $file_name = "sucesos_c".$id_contrato."_e".$id_empleado."_fd".str_replace("/", "", $fecha_desde)."_fh".str_replace("/", "", $fecha_hasta).".txt";
        $filepath = "uploads/files/".$file_name;
        $handle = fopen($filepath, "w");
        $view->sucesos = Suceso::getSucesos($id_empleado, $eventos, $fecha_desde, $fecha_hasta, $id_contrato);

        foreach ($view->sucesos as $su) {
            $fd = new DateTime($su['txt_fecha_desde']);
            $fh = new DateTime($su['txt_fecha_hasta']);
            //$d = (string)$fh->diff($fd)->days;
            $d = (string)($fh->diff($fd)->days+1);

            $line = str_pad($su['txt_evento'], 10). //evento
                str_pad(substr($su['txt_legajo'], 2), 10). //legajo
                str_pad($fd->format('01/m/Y'), 10). //periodo desde
                str_pad($fh->format('01/m/Y'), 10). //periodo hasta
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
        $view->contentTemplate="view/sucesosGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/sucesosLayout.php');
}


?>
