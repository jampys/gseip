<?php

include_once("model/vehiculosModel.php");
include_once("model/contratosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];


$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        $view->puestos = Puesto::getPuestos();
        $view->contentTemplate="view/puestosGrid.php";
        break;

    case 'savePuesto':
        $puesto = new Puesto($_POST['id_puesto']);
        $puesto->setNombre($_POST['nombre']);
        $puesto->setDescripcion($_POST['descripcion']);
        $puesto->setCodigo($_POST['codigo']);
        $puesto->setIdPuestoSuperior(($_POST['id_puesto_superior'])? $_POST['id_puesto_superior'] : null);
        $puesto->setIdArea($_POST['id_area']);
        $puesto->setIdNivelCompetencia($_POST['id_nivel_competencia']);

        $rta = $puesto->save();
        print_r(json_encode($rta));
        exit;
        break;

    case 'newVehiculo': //ok
        $view->label='Nuevo vehículo';
        $view->vehiculo = new Vehiculo();

        $view->marcas = Soporte::get_enum_values('vto_vehiculos', 'marca');
        $view->periodos = Soporte::getPeriodos(2000, date("Y"));
        $view->contratos = $view->vehiculo->getContratosByVehiculo();

        $view->disableLayout=true;
        $view->contentTemplate="view/vehiculosForm.php";
        break;

    case 'editVehiculo': //ok
        $view->label='Editar vehículo';
        $view->vehiculo = new Vehiculo($_POST['id_vehiculo']);

        $view->marcas = Soporte::get_enum_values('vto_vehiculos', 'marca');
        $view->periodos = Soporte::getPeriodos(2000, date("Y"));
        $view->contratos = $view->vehiculo->getContratosByVehiculo();

        $view->disableLayout=true;
        $view->contentTemplate="view/vehiculosForm.php";
        break;

    case 'deletePuesto':
        $puesto = new Puesto($_POST['id_puesto']);
        $rta = $puesto->deletePuesto();
        print_r(json_encode($rta));
        die;
        break;

    case 'autocompletarPuestos':
        $view->puesto = new Puesto();
        $rta=$view->puesto->autocompletarPuestos($_POST['term']);
        print_r(json_encode($rta));
        exit;
        break;

    default : //ok
        if ( PrivilegedUser::dhasPrivilege('VEH_VER', array(1)) ) {
            $view->vehiculos = Vehiculo::getVehiculos();
            $view->contentTemplate="view/vehiculosGrid.php";
        }else{
            $_SESSION['error'] = PrivilegedUser::dgetErrorMessage('PRIVILEGE', 'VEH_VER');
            header("Location: index.php?action=error");
            exit;
        }
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/vehiculosLayout.php');
}


?>
