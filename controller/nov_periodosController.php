<?php
include_once("model/nov_periodosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'getPeriodos': //select dependiente //ok
        $rta = NovPeriodo::getPeriodosActivos($_POST['id_contrato']);
        print_r(json_encode($rta));
        exit;
        break;




    default : //carga la tabla de etapas de la postulacion //ok
        //$view->postulacion = new Postulacion($_POST['id_postulacion']);
        //$view->label='Etapas de la postulación';
        //$view->etapas = Etapa::getEtapas($_POST['id_postulacion']);
        //$view->localidades = Localidad::getLocalidades();
        //$view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');
        $view->disableLayout=true;
        $view->contentTemplate="view/postulaciones/etapasForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>
