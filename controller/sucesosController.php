<?php

//include_once("model/vto_renovacionesPersonalModel.php");
//include_once("model/vto_vencimientosPersonalModel.php");
//include_once("model/contratosModel.php");

include_once("model/empleadosModel.php");
include_once("model/nov_eventosLiquidacionModel.php");
include_once("model/nov_sucesosModel.php");

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
        $view->sucesos = Suceso::getSucesos($id_empleado, $eventos, $fecha_desde, $fecha_hasta);
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

        $view->empleados = Empleado::getEmpleados();
        $view->eventos = EventosLiquidacion::getEventosLiquidacion();
        //$view->empleado = $view->renovacion->getEmpleado()->getApellido()." ".$view->renovacion->getEmpleado()->getNombre();

        $view->disableLayout=true;
        $view->contentTemplate="view/sucesosForm.php";
        break;

    case 'editSuceso': //ok
        $view->label='Editar Suceso';
        $view->suceso = new Suceso($_POST['id_suceso']);

        $view->empleados = Empleado::getEmpleados();
        $view->eventos = EventosLiquidacion::getEventosLiquidacion();
        //$view->empleado = $view->renovacion->getEmpleado()->getApellido()." ".$view->renovacion->getEmpleado()->getNombre();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/sucesosForm.php";
        break;


    case 'deleteHabilidad':
        $habilidad = new Habilidad($_POST['id_habilidad']);
        $rta = $habilidad->deleteHabilidad();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
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


    case 'txt':
        $id_empleado = ($_GET['id_empleado']!='')? $_GET['id_empleado'] : null;
        $eventos = ($_GET['eventos']!='')? implode(",", $_GET['eventos'])  : 'su.id_evento';
        $fecha_desde = ($_GET['search_fecha_desde']!='')? $_GET['search_fecha_desde'] : null;
        $fecha_hasta = ($_GET['search_fecha_hasta']!='')? $_GET['search_fecha_hasta'] : null;
        $view->sucesos = Suceso::getSucesos($id_empleado, $eventos, $fecha_desde, $fecha_hasta);

        $handle = fopen("file.txt", "a");
        $view->sucesos = Suceso::getSucesos($id_empleado, $eventos, $fecha_desde, $fecha_hasta);
        foreach ($view->sucesos as $su) {
            //$su['id_empleado']
            fwrite($handle, str_pad($su['txt_evento'], 10).
                            str_pad(substr($su['txt_legajo'], 2), 10).
                            "\ttext1\r\n"

            );
        }

        fclose($handle);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename('file.txt'));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('file.txt'));
        readfile('file.txt'); //descarga el archivo

        unlink ('file.txt'); //borra el archivo una vez descargado

        exit;
        break;

    default : //ok
        $view->empleados = Empleado::getEmpleados(); //carga el combo para filtrar empleados
        $view->eventos = EventosLiquidacion::getEventosLiquidacion(); //carga el combo para filtrar eventos liquidacion
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
