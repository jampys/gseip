﻿<?php

include_once("model/contratosModel.php");
include_once("model/localidadesModel.php");
include_once("model/companiasModel.php");

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        $view->empleados = Empleado::getEmpleados();
        $view->contentTemplate="view/empleadosGrid.php";
        break;

    case 'saveEmpleado':
        $empleado = new Empleado($_POST['id_empleado']);
        $empleado->setLegajo($_POST['legajo']);
        $empleado->setApellido($_POST['apellido']);
        $empleado->setNombre($_POST['nombre']);
        $empleado->setDocumento($_POST['documento']);
        $empleado->setCuil($_POST['cuil']);
        $empleado->setFechaNacimiento($_POST['fecha_nacimiento']);
        $empleado->setFechaAlta($_POST['fecha_alta']);
        $empleado->setFechaBaja($_POST['fecha_baja']);
        $empleado->setDireccion($_POST['direccion']);
        $empleado->setIdLocalidad($_POST['localidad']);
        $empleado->setTelefono($_POST['telefono']);
        $empleado->setEmail($_POST['email']);
        $empleado->setSexo($_POST['sexo']);
        $empleado->setNacionalidad($_POST['nacionalidad']);
        $empleado->setEstadoCivil($_POST['estado_civil']);
        $empleado->setEmpresa($_POST['empresa']);

        //$cliente->save();
        //break;
        $rta = $empleado->save($_POST['cambio_domicilio']);
        print_r(json_encode($rta));
        exit;
        break;

    case 'newContrato':
        $view->label='Nuevo Contrato';
        $view->contrato = new Contrato();
        $view->responsable = $view->contrato->getResponsable()->getApellido()." ".$view->contrato->getResponsable()->getNombre();
        $view->localidades = Localidad::getLocalidades();
        $view->companias = Compania::getCompanias();

        $view->disableLayout=true;
        $view->contentTemplate="view/contratosForm.php";
        break;

    case 'editContrato': //ok
        $view->label='Editar Contrato';
        $view->contrato = new Contrato($_POST['id']);
        $view->responsable = $view->contrato->getResponsable()->getApellido()." ".$view->contrato->getResponsable()->getNombre();
        $view->localidades = Localidad::getLocalidades();
        $view->companias = Compania::getCompanias();

        $view->disableLayout=true;
        $view->contentTemplate="view/ContratosForm.php";
        break;

    case 'checkEmpleadoCuil':
        $view->empleado = new Empleado();
        $rta = $view->empleado->checkEmpleadoCuil($_POST['cuil']);
        print_r(json_encode($rta));
        exit;
        break;

    case 'checkEmpleadoLegajo':
        $view->empleado = new Empleado();
        $rta = $view->empleado->checkEmpleadoLegajo($_POST['legajo']);
        print_r(json_encode($rta));
        exit;
        break;

    case 'autocompletarEmpleadosByCuil':
        $view->empleado = new Empleado();
        $rta=$view->empleado->autocompletarEmpleadosByCuil($_POST['term']);
        print_r(json_encode($rta));
        exit;
        break;


    default : //ok
        $view->contratos = Contrato::getContratos();
        $view->contentTemplate="view/contratosGrid.php";
        break;
}

if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/contratosLayout.php');
}


?>
