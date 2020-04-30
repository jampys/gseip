<?php
include_once("model/calendarModel.php");

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
        //$id_empleado = (($_POST['id_empleado']!='')? $_POST['id_empleado'] : null );
        //$activos = (($_POST['activos']!='')? $_POST['activos'] : null );
        $start = $_POST['start'];
        $end = $_POST['end'];
        $feriados = Calendar::getFeriados($start, $end);
        print_r(json_encode(array(
            'feriados'=>$feriados
        )));
        exit;
        break;




    default : //carga la tabla de etapas de la postulacion
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
