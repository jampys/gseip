<?php
include_once("model/nov_periodosModel.php");
include_once("model/contratosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
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




    default :  //ok
        //$view->postulacion = new Postulacion($_POST['id_postulacion']);
        //$view->label='Etapas de la postulación';
        //$view->etapas = Etapa::getEtapas($_POST['id_postulacion']);
        //$view->localidades = Localidad::getLocalidades();
        //$view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');
        //$view->disableLayout=true;
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos
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
