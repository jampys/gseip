<?php
include_once("model/calendarModel.php");
//include_once("model/nov_sucesosModel.php");
include_once("model/contratosModel.php");
include_once("model/nov_eventosCuadrillaModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'getPeriodos': //select dependiente
        $id_contrato = (($_POST['id_contrato']!='')? $_POST['id_contrato'] : null );
        $activos = (($_POST['activos']!='')? $_POST['activos'] : null );
        $rta = NovPeriodo::getPeriodos($id_contrato, $activos);
        print_r(json_encode($rta));
        exit;
        break;

    case 'get': //trae los feriados //ok

        $start = date("Y-m-d", $_POST['start']/1000); //$_POST['start']; //convierte de milisegundos a yyyy-mm-dd
        $end = date("Y-m-d", $_POST['end']/1000); //$_POST['end'];

        $feriados = Calendar::getFeriados($start, $end);

        $id_empleado = ($_POST['id_empleado']!='')? $_POST['id_empleado'] : null;
        $eventos = ($_POST['eventos']!='')? implode(",", $_POST['eventos'])  : 'su.id_evento';
        $fecha_desde = ($_POST['search_fecha_desde']!='')? $_POST['search_fecha_desde'] : null;
        $fecha_hasta = ($_POST['search_fecha_hasta']!='')? $_POST['search_fecha_hasta'] : null;
        $id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        $sucesos = Calendar::getSucesos($id_empleado, $eventos, $start, $end, $id_contrato);

        print_r(json_encode(array(
            'feriados'=>$feriados,
            'sucesos'=>$sucesos
        )));
        exit;
        break;




    default : //carga la tabla de etapas de la postulacion
        $view->contratos = Contrato::getContratosControl();
        $view->eventos = EventosCuadrilla::getEventosCuadrilla();
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
