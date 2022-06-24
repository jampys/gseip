<?php
include_once("model/sec_user-roleModel.php");
include_once("model/sec_usersModel.php");
include_once("model/sec_rolesModel.php");
//include_once("model/localidadesModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrp.id_vencimiento';
        //$id_puesto = ($_POST['search_puesto']!='')? $_POST['search_puesto'] : null;
        //$id_localidad = ($_POST['search_localidad']!='')? $_POST['search_localidad'] : null;
        //$id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        //$todas = ($_POST['renovado']== 0)? null : 1;
        //$view->busquedas = Busqueda::getBusquedas($id_puesto, $id_localidad, $id_contrato, $todas);
        $rta = $view->roles = UsuarioRol::getRoles($_POST['id_user']);
        //$view->contentTemplate="view/usuarios/rolesGrid.php";
        //break;
        print_r(json_encode($rta));
        exit;
        break;

    case 'saveRole': //ok
        $gv = new UsuarioRol($_POST['id_user_role']);
        $gv->setIdUser($_POST['id_user']);
        $gv->setIdRole($_POST['id_role']);
        $gv->setFechaDesde($_POST['fecha_desde']);
        $gv->setFechaHasta( ($_POST['fecha_hasta']!='')? $_POST['fecha_hasta'] : null);
        $gv->setCreatedBy($_SESSION['id_user']);
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        $rta = $gv->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newRole': //ok
        $view->label='Nuevo rol';
        $view->role = new UsuarioRol($_POST['id_user_role']);

        //$view->etapas = Soporte::get_enum_values('sel_etapas', 'etapa');
        $view->roles = Rol::getRoles();

        $view->disableLayout=true;
        $view->contentTemplate="view/usuarios/role_detailForm.php";
        break;

    case 'editRole': //ok
        $view->label = ($_POST['target']!='view')? 'Editar rol': 'Ver rol';
        $view->role = new UsuarioRol($_POST['id_user_role']);

        //$view->etapas = Soporte::get_enum_values('sel_etapas', 'etapa');
        $view->roles = Rol::getRoles();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/usuarios/role_detailForm.php";
        break;

    case 'deleteRole': //ok
        $view->role = new UsuarioRol($_POST['id_user_role']);
        $rta = $view->role->deleteUserRole();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    case 'checkVehiculo':
        $view->contrato_vehiculo = new ContratoVehiculo();
        $rta = $view->contrato_vehiculo->checkVehiculo($_POST['id_vehiculo'], $_POST['id_contrato'], $_POST['id_contrato_vehiculo']);
        print_r(json_encode($rta));
        exit;
        break;


    default : //carga la tabla de roles del usuario //ok
        $view->disableLayout=true;
        $view->usuario = new Usuario($_POST['id_user']);
        $view->roles = UsuarioRol::getRoles($_POST['id_user']);
        $view->label= $view->usuario->getUser();
        $view->contentTemplate="view/usuarios/rolesForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>
