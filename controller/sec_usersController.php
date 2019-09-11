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
        //$puesto->setDescripcion($_POST['descripcion']);
        //$puesto->setCodigo($_POST['codigo']);
        //$puesto->setIdPuestoSuperior(($_POST['id_puesto_superior']) ? $_POST['id_puesto_superior'] : null);
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

    case 'deletePuesto':
        $puesto = new Puesto($_POST['id_puesto']);
        $rta = $puesto->deletePuesto();
        print_r(json_encode($rta));
        die;
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
