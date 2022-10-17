<?php
include_once("model/vto_gruposVehiculosModel.php");
include_once("model/vto_vencimientosVehiculosModel.php");


$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        $rta = $view->grupos = Grupo::getGrupos();
        //$view->contentTemplate="view/grupos_vehiculos/gruposGrid.php";
        //break;
        print_r(json_encode($rta));
        exit;
        break;

    case 'saveGrupo': //ok
        $grupo = new Grupo($_POST['id_grupo']);
        $grupo->setNombre($_POST['nombre']);
        $grupo->setNroReferencia($_POST['nro_referencia']);
        $grupo->setIdVencimiento($_POST['id_vencimiento']);
        $grupo->setFechaBaja( ($_POST['fecha_baja']!='')? $_POST['fecha_baja'] : null);

        $rta = $grupo->save();
        print_r(json_encode($rta));
        exit;
        break;

    case 'newGrupo': //ok
        $view->grupo = new Grupo();
        $view->label='Nueva flota';

        $view->vencimientos = VencimientoVehicular::getVencimientosVehiculos();

        $view->disableLayout=true;
        $view->contentTemplate="view/grupos_vehiculos/gruposForm.php";
        break;

    case 'editGrupo': //ok
        $view->grupo = new Grupo($_POST['id_grupo']);
        $view->label= $view->grupo->getNombre().' '.$view->grupo->getNroReferencia();

        $view->vencimientos = VencimientoVehicular::getVencimientosVehiculos();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/grupos_vehiculos/gruposForm.php";
        break;


    case 'deleteGrupo': //ok
        $grupo = new Grupo($_POST['id_grupo']);
        $rta = $grupo->deleteGrupo();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;


    default : //ok
        $view->grupos = Grupo::getGrupos();
        $view->contentTemplate="view/grupos_vehiculos/gruposGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/grupos_vehiculos/gruposLayout.php');
}


?>
