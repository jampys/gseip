<?php

include_once("model/empleadosModel.php");
include_once("model/localidadesModel.php");

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

    case 'newEmpleado':
        $view->empleado = new Empleado();
        $view->label='Nuevo Empleado';

        $view->localidades = Localidad::getLocalidades();
        $view->sexos = Soporte::get_enum_values('empleados', 'sexo');
        $view->estados_civiles = Soporte::get_enum_values('empleados', 'estado_civil');
        $view->nacionalidades = Soporte::get_enum_values('empleados', 'nacionalidad');
        $view->empresas = Soporte::get_enum_values('empleados', 'empresa');

        $view->disableLayout=true;
        $view->contentTemplate="view/empleadosForm.php";
        break;

    case 'editEmpleado':
        $view->label='Editar Empleado';
        $view->empleado = new Empleado($_POST['id']);

        $view->localidades = Localidad::getLocalidades();
        $view->sexos = Soporte::get_enum_values('empleados', 'sexo');
        $view->estados_civiles = Soporte::get_enum_values('empleados', 'estado_civil');
        $view->nacionalidades = Soporte::get_enum_values('empleados', 'nacionalidad');
        $view->empresas = Soporte::get_enum_values('empleados', 'empresa');

        $view->disableLayout=true;
        $view->contentTemplate="view/EmpleadosForm.php";
        break;

    case 'deleteClient':
        $id=intval($_POST['id']);
        $client=new Cliente($id);
        //$client->delete();
        $rta = $client->delete();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    default :
        $view->empleados=Empleado::getEmpleados();
        $view->contentTemplate="view/empleadosGrid.php"; // seteo el template que se va a mostrar
        break;
}

if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);}
else {
    include_once('view/empleadosLayout.php');
}


?>
