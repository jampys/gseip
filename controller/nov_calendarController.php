<?php
include_once("model/calendarModel.php");
include_once("model/nov_eventosLiquidacionModel.php");
include_once("model/contratosModel.php");
include_once("model/nov_eventosCuadrillaModel.php");
include_once("model/empleadosModel.php");
include_once("model/cuadrillasModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{

    case 'get': //trae los feriados //ok

        $start = date("Y-m-d", $_POST['start']/1000); //$_POST['start']; //convierte de milisegundos a yyyy-mm-dd
        $end = date("Y-m-d", $_POST['end']/1000); //$_POST['end'];
        //$empleados = ($_POST['empleados']!='')? implode(",", $_POST['empleados'])  : 'su.id_empleado';
        //$selectedEventos = ($_POST['eventos']!='')? implode(",", $_POST['eventos'])  : 'su.id_evento';
        $id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        $selected_sucesos = ($_POST['sucesos']!='')? implode(",", $_POST['sucesos'])  : 'su.id_evento';
        $selected_conceptos = ($_POST['conceptos']!='')? implode(",", $_POST['conceptos'])  : 'nccc.id_concepto_convenio_contrato';


        //mientras no se seleccione un contrato, solo cargará los feriados
        if(!$_POST['id_contrato']){
            print_r(json_encode(array(
                'feriados'=>Calendar::getFeriados($start, $end),
                'sucesos'=>'',
                'novedades_empleado'=>'',
                'novedades_cuadrilla'=>''
            )));
            exit;
        }



        print_r(json_encode(array(
            'feriados'=>Calendar::getFeriados($start, $end),
            'sucesos'=>($_POST['radio_vista']=='empleado' && $_POST['check_suceso']==1)? Calendar::getSucesos($_POST['empleados'], $selected_sucesos, $start, $end, $id_contrato) : '',
            'novedades_empleado'=>($_POST['radio_vista']=='empleado' && $_POST['check_concepto']==1)? Calendar::getNovedadesEmpleado($_POST['empleados'], $_POST['eventos'], $start, $end, $id_contrato, $selected_conceptos) : '',
            'novedades_cuadrilla'=>($_POST['radio_vista']=='cuadrilla' && $_POST['check_concepto']==1)? Calendar::getNovedadesCuadrilla($_POST['cuadrillas'], $_POST['eventos'], $start, $end, $id_contrato) : ''
        )));
        exit;
        break;

    case 'getEmpleados': //select dependiente //ok
        $id_contrato = $_POST['id_contrato'];
        $rta = Empleado::getEmpleadosControl($id_contrato);
        print_r(json_encode($rta));
        exit;
        break;

    case 'getCuadrillas': //select dependiente //ok
        $id_contrato = $_POST['id_contrato'];
        $rta = Cuadrilla::getCuadrillas($id_contrato, null);
        print_r(json_encode($rta));
        exit;
        break;




    default : //carga la tabla de etapas de la postulacion
        $view->contratos = Contrato::getContratosControl();
        $view->eventos = EventosCuadrilla::getEventosCuadrilla();
        $view->sucesos = EventosLiquidacion::getEventosLiquidacion();
        //$view->postulacion = new Postulacion($_POST['id_postulacion']);
        //$view->label='Etapas de la postulación';
        //$view->etapas = Etapa::getEtapas($_POST['id_postulacion']);
        //$view->localidades = Localidad::getLocalidades();
        //$view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');
        //$view->disableLayout=true;
        $view->contentTemplate="view/calendar/calendarGrid.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    include_once('view/calendar/calendarLayout.php');
}


?>
