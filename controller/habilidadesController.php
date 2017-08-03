<?php

include_once("model/habilidadesModel.php");

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        $view->habilidades = Habilidad::getHabilidades();
        $view->contentTemplate="view/habilidadesGrid.php";
        break;

    case 'saveHabilidad':
        $habilidad = new Habilidad($_POST['id_habilidad']);
        $habilidad->setCodigo($_POST['codigo']);
        $habilidad->setNombre($_POST['nombre']);

        $rta = $habilidad->save();
        print_r(json_encode($rta));
        exit;
        break;

    case 'newHabilidad':
        $view->habilidad = new Habilidad();
        $view->label='Nueva Habilidad';
        $view->disableLayout=true;
        $view->contentTemplate="view/habilidadesForm.php";
        break;

    case 'editHabilidad':
        $view->label='Editar Habilidad';
        $view->habilidad = new Habilidad($_POST['id_habilidad']);
        $view->disableLayout=true;
        $view->contentTemplate="view/habilidadesForm.php";
        break;

    case 'deleteHabilidad':
        $id=intval($_POST['id']);
        $client=new Cliente($id);
        //$client->delete();
        $rta = $client->delete();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    default :
        $view->habilidades = Habilidad::getHabilidades();
        $view->contentTemplate="view/habilidadesGrid.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);}
else {
    include_once('view/habilidadesLayout.php');
}


?>
