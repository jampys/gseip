<?php

include_once("model/contratosModel.php");
include_once("model/contrato-empleadoModel.php");
include_once("model/localidadesModel.php");
include_once("model/companiasModel.php");
include_once("model/puestosModel.php");

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        $view->contratos = Contrato::getContratos();
        $view->contentTemplate="view/contratosGrid.php";
        break;

    case 'saveContrato': //ok
        /*$contrato = new Contrato($_POST['id_contrato']);
        $contrato->setNroContrato($_POST['nro_contrato']);
        $contrato->setFechaDesde($_POST['fecha_desde']);
        $contrato->setFechaHasta($_POST['fecha_hasta']);
        $contrato->setIdResponsable($_POST['id_responsable']);
        $contrato->setIdCompania($_POST['id_compania']);
        $rta = $contrato->save();
        print_r(json_encode($rta));
        exit;
        break;*/

        $flag=1;

        sQuery::dpBeginTransaction();

        try{

            $contrato = new Contrato($_POST['id_contrato']);
            $contrato->setNroContrato($_POST['nro_contrato']);
            $contrato->setFechaDesde($_POST['fecha_desde']);
            $contrato->setFechaHasta($_POST['fecha_hasta']);
            $contrato->setIdResponsable($_POST['id_responsable']);
            $contrato->setIdCompania($_POST['id_compania']);
            if($contrato->save() < 0) $flag = -1;

            //si es un insert tomo el ultimo id insertado, si es un update, el id del contrato.
            $id = (!$contrato->getIdContrato())? sQuery::dpLastInsertId(): $contrato->getIdContrato();

            $vEmpleados = json_decode($_POST["vEmpleados"], true);
            print_r($vEmpleados);

            foreach ($vEmpleados as $vE) {

                //$c = new HabilidadEmpleado();
                //$c->setIdHabilidad($vH['id_habilidad']);
                //$c->setIdEmpleado($vE['id_empleado']);
                //if($c->insertHabilidadEmpleado() < 0) $flag = -1;  //si falla algun insert $flag = -1
                echo "id_contrato :".$id." - id_empleado: ".$vE['id_empleado'];

            }

            //Devuelve el resultado a la vista
            if($flag > 0) sQuery::dpCommit();
            else sQuery::dpRollback();

            print_r(json_encode($flag));

        }
        catch(Exception $e){
            echo $e->getMessage();
            sQuery::dpRollback();
            print_r(json_encode($flag));
        }

        exit;
        break;



    case 'newContrato': //ok
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

    case 'editContratoEmpleado': //ok
        $view->contratoEmpleado = ContratoEmpleado::getContratoEmpleado($_POST['id_contrato']);
        print_r(json_encode($view->contratoEmpleado));
        exit;
        break;

    case 'addEmpleado':
        //$view->client=new Cliente();
        $view->label='Agregar Empleado';
        $view->disableLayout=true;

        $view->puesto = Puesto::getPuestos();

        $view->contentTemplate="view/contratosFormAddEmpleado.php";
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
