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
        $habilidad = new Habilidad($_POST['id_habilidad']);
        $rta = $habilidad->deleteHabilidad();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    case 'autocompletarHabilidades':
        $view->habilidad = new Habilidad();
        $rta=$view->habilidad->autocompletarHabilidades($_POST['term']);
        print_r(json_encode($rta));
        exit;
        break;

    default :
        if ( PrivilegedUser::dhasPrivilege('HAB_VER', array(1)) ) {
            $view->habilidades = Habilidad::getHabilidades();
            $view->contentTemplate="view/habilidadesGrid.php";
        }else{
            $_SESSION['error'] = PrivilegedUser::dgetErrorMessage('PRIVILEGE', 'HAB_VER');
            header("Location: index.php?action=error");
            exit;
        }
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);}
else {
    include_once('view/habilidadesLayout.php');
}


?>
