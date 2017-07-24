<?php

include_once("model/empleadosModel.php");
if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}

$view->disableLayout=false;// marca si usa o no el layout , si no lo usa imprime directamente el template


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true; // no usa el layout
        $view->clientes=Cliente::getClientes();
        $view->contentTemplate="view/clientesGrid.php"; // seteo el template que se va a mostrar
        break;
    case 'saveClient':
        // limpio todos los valores antes de guardarlos
        // por ls dudas venga algo raro
        $id=intval($_POST['id']);
        $nombre=cleanString($_POST['nombre']);
        $apellido=cleanString($_POST['apellido']);
        $fecha=cleanString($_POST['fecha']);
        $peso=cleanString($_POST['peso']);
        $cliente=new Cliente($id);
        $cliente->setNombre($nombre);
        $cliente->setApellido($apellido);
        $cliente->setFecha($fecha);
        $cliente->setPeso($peso);
        //$cliente->save();
        //break;
        $rta = $cliente->save();
        print_r(json_encode($rta));
        exit;
        break;
    case 'newEmpleado':
        //$view->client=new Cliente();
        $view->label='Nuevo Empleado';
        $view->disableLayout=true;
        $view->contentTemplate="view/empleadosForm.php"; // seteo el template que se va a mostrar
        //include_once('view/empleadosForm.php');
        //exit;
        break;

    case 'editEmpleado':
        $editId=intval($_POST['id']);
        $view->label='Editar Empleado';
        $view->empleado = new Empleado($editId);
        $view->disableLayout=true;
        $view->contentTemplate="view/EmpleadosForm.php"; // seteo el template que se va a mostrar
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
