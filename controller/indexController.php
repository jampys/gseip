<?php

if(isset($_POST['operation']))
{$operation=$_POST['operation'];}


$view= new stdClass(); // creo una clase standard para contener la vista
$view->disableLayout=false;// marca si usa o no el layout , si no lo usa imprime directamente el template



switch ($operation)
{
    case 'about': //ok
        $view->label='Acerca de';
        $view->disableLayout=true;
        $view->contentTemplate="view/indexFormAbout.php";
        break;

    default:
        //$view->disableLayout=true;
        //$view->clientes=Cliente::getClientes();
        //$view->contentTemplate="view/clientesGrid.php"; // seteo el template que se va a mostrar
        break;

}

// si esta deshabilitado el layout solo imprime el template
if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);}
else {
    include_once('view/indexLayout.php');
}


?>
