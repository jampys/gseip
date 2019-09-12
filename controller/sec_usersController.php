<?php

include_once("model/usuariosModel.php");
include_once("model/empleadosModel.php");

include_once("model/competenciasNivelesModel.php");
include_once("model/habilidad-puestoModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];


$view->disableLayout=false;


switch ($operation) {
    case 'refreshGrid': //ok
        $view->disableLayout = true;
        $view->usuarios = Usuario::getUsuarios();
        $view->contentTemplate = "view/usuarios/usuariosGrid.php";
        break;

    case 'saveUsuario': //ok
        $usuario = new Usuario($_POST['id_user']);
        $usuario->setUser($_POST['user']);
        $usuario->setFechaBaja(($_POST['fecha_baja']) ? $_POST['fecha_baja'] : null);
        $usuario->setEnabled ( ($_POST['enabled'] == 1)? 1 : null);
        $usuario->setIdEmpleado($_POST['id_empleado']); //solo para insert
        $usuario->setProfilePicture('uploads/profile_pictures/default.png'); //solo para insert
        //
        //$puesto->setIdArea($_POST['id_area']);
        //$puesto->setIdNivelCompetencia($_POST['id_nivel_competencia']);

        $rta = $usuario->save();
        //print_r(json_encode($rta));
        print_r(json_encode(sQuery::dpLastInsertId()));
        exit;
        break;

    case 'newUsuario': //ok
        $view->usuario = new Usuario();
        $view->label = 'Nuevo Usuario';

        $view->empleados = Empleado::getEmpleadosActivos(null);

        $view->disableLayout = true;
        $view->contentTemplate = "view/usuarios/usuariosForm.php";
        break;

    case 'editUsuario': //ok
        $view->usuario = new Usuario($_POST['id_user']);
        $view->label = $view->usuario->getUser();

        $view->empleados = Empleado::getEmpleados();

        $view->disableLayout = true;
        $view->contentTemplate = "view/usuarios/usuariosForm.php";
        break;

    case 'deleteUsuario': //ok

        try{
            sQuery::dpBeginTransaction();
            $usuario = new Usuario($_POST['id_user']);
            $rta = $usuario->deleteUsuario();
            //if (file_exists($usuario->getProfilePicture())) {
            unlink($usuario->getProfilePicture());
            //}
            sQuery::dpCommit();
        }catch (PDOException $e){ //error en el query
            sQuery::dpRollback();
            $rta = -1;
        }catch(Exception $e){ //error en el unlink
            sQuery::dpRollback();
            $rta = -1;
        }

        print_r(json_encode($rta));
        die;
        break;

    case 'checkEmpleado': //ok
        $view->usuario = new Usuario();
        $rta = $view->usuario->checkEmpleado($_POST['id_user'], $_POST['id_empleado']);
        print_r(json_encode($rta));
        exit;
        break;

    default :
        $view->usuarios = Usuario::getUsuarios();
        $view->contentTemplate = "view/usuarios/usuariosGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/usuarios/usuariosLayout.php');
}


?>
