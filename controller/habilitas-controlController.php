<?php
include_once("model/habilitasModel.php");


$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //para la grilla de consulta de habilitas //ok
        $view->disableLayout=true;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrp.id_vencimiento';

        $view->habilita = new Habilita();
        $view->habilita->setOt( ($_POST['search_busqueda']=='ot')? $_POST['search_input'] : null  );
        $view->habilita->setHabilita( ($_POST['search_busqueda']=='habilita')? $_POST['search_input'] : null  );
        $view->habilita->setCertificado( ($_POST['search_busqueda']=='certificado')? $_POST['search_input'] : null  );

        $view->habilitas =  $view->habilita->getHabilitas();
        $view->contentTemplate="view/habilitas-control/habilitasGrid.php";
        break;

    case 'getHijos': //ok
        //$view->puesto = new Puesto();
        $rta = Habilita::getHijos($_POST['id']);
        print_r(json_encode($rta));
        exit;
        break;



    default : //para la grilla de consulta de habilitas
        //$view->busquedas = Busqueda::getBusquedasActivas(); //carga el combo para filtrar busquedas
        //$view->postulantes = Postulante::getPostulantesActivos(); //carga el combo para filtrar postulantes
        //$view->contratos = Contrato::getContratos(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/habilitas-control/habilitasGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/habilitas-control/habilitasLayout.php');
}


?>
