<?php


include_once("model/clientesModel.php");
//$action='index';
if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view= new stdClass(); // creo una clase standard para contener la vista
$view->disableLayout=false;// marca si usa o no el layout , si no lo usa imprime directamente el template



// para no utilizar un framework y simplificar las cosas uso este switch, la idea
// es que puedan apreciar facilmente cuales son las operaciones que se realizan
switch ($operation)
{
    case 'index':
        $view->clientes=Cliente::getClientes(); // tree todos los clientes
        $view->contentTemplate="view/clientesGrid.php"; // seteo el template que se va a mostrar
        break;
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
    case 'newClient':
        $view->client=new Cliente();
        $view->label='Nuevo Cliente';
        $view->disableLayout=true;
        $view->contentTemplate="view/clientesForm.php"; // seteo el template que se va a mostrar
        break;
    case 'editClient':
        $editId=intval($_POST['id']);
        $view->label='Editar Cliente';
        $view->client=new Cliente($editId);
        $view->disableLayout=true;
        $view->contentTemplate="view/clientesForm.php"; // seteo el template que se va a mostrar
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
}

// si esta deshabilitado el layout solo imprime el template
if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);}
else {
    include_once('view/clientesLayout.php'); // el layout incluye el template adentro
}


?>
