<?php

include_once("model/vehiculosModel.php");
include_once("model/contratosModel.php");
include_once("model/companiasModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];


$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        $view->vehiculos = Vehiculo::getVehiculos();
        $view->contentTemplate="view/vehiculosGrid.php";
        break;

    case 'saveVehiculo': //ok
        $vehiculo = new Vehiculo($_POST['id_vehiculo']);
        $vehiculo->setNroMovil($_POST['nro_movil']);
        $vehiculo->setMatricula($_POST['matricula']);
        $vehiculo->setMarca($_POST['marca']);
        $vehiculo->setModelo($_POST['modelo']);
        $vehiculo->setModeloAno($_POST['modelo_ano']);
        $vehiculo->setPropietario($_POST['propietario']);
        $vehiculo->setLeasing($_POST['leasing']);
        $vehiculo->setFechaBaja(($_POST['fecha_baja'])? $_POST['fecha_baja'] : null);

        $rta = $vehiculo->save();
        print_r(json_encode($rta));
        exit;
        break;

    case 'newVehiculo': //ok
        $view->label='Nuevo vehículo';
        $view->vehiculo = new Vehiculo();

        $view->marcas = Soporte::get_enum_values('vto_vehiculos', 'marca');
        $view->periodos = Soporte::getPeriodos(2000, date("Y"));
        $view->contratos = $view->vehiculo->getContratosByVehiculo();
        $view->companias = Compania::getCompanias();

        $view->disableLayout=true;
        $view->contentTemplate="view/vehiculosForm.php";
        break;

    case 'editVehiculo': //ok
        $view->label='Editar vehículo';
        $view->vehiculo = new Vehiculo($_POST['id_vehiculo']);

        $view->marcas = Soporte::get_enum_values('vto_vehiculos', 'marca');
        $view->periodos = Soporte::getPeriodos(2000, date("Y"));
        $view->contratos = $view->vehiculo->getContratosByVehiculo();
        $view->companias = Compania::getCompanias();

        $view->disableLayout=true;
        $view->contentTemplate="view/vehiculosForm.php";
        break;

    case 'deleteVehiculo': //ok
        $vehiculo = new Vehiculo($_POST['id_vehiculo']);
        $rta = $vehiculo->deleteVehiculo();
        print_r(json_encode($rta));
        die;
        break;

    case 'checkVehiculoNroMovil': //ok
        $view->vehiculo = new Vehiculo();
        $id_vehiculo = ($_POST['id_vehiculo']!='')? $_POST['id_vehiculo'] : null;
        $nro_movil = ($_POST['nro_movil']!='')? $_POST['nro_movil'] : null;
        $rta = $view->vehiculo->checkVehiculoNroMovil($nro_movil, $id_vehiculo);
        print_r(json_encode($rta));
        exit;
        break;

    case 'checkVehiculoMatricula': //ok
        $view->vehiculo = new Vehiculo();
        $id_vehiculo = ($_POST['id_vehiculo']!='')? $_POST['id_vehiculo'] : null;
        $matricula = ($_POST['matricula']!='')? $_POST['matricula'] : null;
        $rta = $view->vehiculo->checkVehiculoMatricula($matricula, $id_vehiculo);
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
