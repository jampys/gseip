<?php
include_once("model/obj_objetivosModel.php");
include_once("model/empleadosModel.php");
include_once("model/vto_renovacionesPersonalModel.php");

if(isset($_POST['operation']))
{$operation=$_POST['operation'];}


$view= new stdClass(); // creo una clase standard para contener la vista
$view->disableLayout=false;// marca si usa o no el layout , si no lo usa imprime directamente el template



switch ($operation)
{
    case 'about': //ok
        //$view->label='Acerca de';
        $view->disableLayout=true;
        $view->contentTemplate="view/indexFormAbout.php";
        break;

    case 'getObjetivo': //trae los datos del objetivo para mostrar el detalle en el dashboard.
        $view->objetivo = new Objetivo($_POST['id_objetivo']);
        $rta = $view->objetivo->getObjetivo();
        print_r(json_encode($rta));
        exit;
        break;

    case 'getProfile': //trae el perfil del empleado para mostrar en el detalle del cumpleaños
        $view->empleado = new Empleado($_POST['id_empleado']);
        $rta = $view->empleado->getProfile();
        print_r(json_encode($rta));
        exit;
        break;

    default:
        $view->disableLayout=false;
        $view->dias = 7;


        $id_responsable_ejecucion = $_SESSION["id_empleado"];
        $view->objetivos = Objetivo::getObjetivos(date('Y'), null, null, null, null, $_SESSION['id_empleado'], null);
        $view->objetivos1 = Objetivo::getObjetivos(date('Y'), null, null, null, null, null, $_SESSION['id_empleado']);
        $view->cumpleaños = Empleado::getProximosCumpleaños($view->dias);
        $view->vencimientos = RenovacionPersonal::getRenovacionesPersonal($_SESSION['id_empleado'], null, 'vrp.id_vencimiento', null, null, null);




        $view->contentTemplate="view/indexDashboard.php"; // seteo el template que se va a mostrar
        break;

}

// si esta deshabilitado el layout solo imprime el template
if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);}
else {
    include_once('view/indexLayout.php');
}


?>
