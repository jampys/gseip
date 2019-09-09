<?php

include_once("model/contratosModel.php");
include_once("model/contrato-empleadoModel.php");
include_once("model/localidadesModel.php");
include_once("model/companiasModel.php");
include_once("model/puestosModel.php");
include_once("model/procesosModel.php");
include_once("model/contrato-empleado-procesoModel.php");
include_once("model/empleadosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        $view->contratos = Contrato::getContratos();
        $view->contentTemplate="view/contratos/contratosGrid.php";
        break;

    case 'saveContrato': //ok

        try{

            sQuery::dpBeginTransaction();

            $contrato = new Contrato($_POST['id_contrato']);
            $contrato->setNroContrato($_POST['nro_contrato']);
            $contrato->setNombre($_POST['nombre']);
            $contrato->setFechaDesde($_POST['fecha_desde']);
            $contrato->setFechaHasta($_POST['fecha_hasta']);
            $contrato->setIdResponsable($_POST['id_responsable']);
            $contrato->setIdCompania($_POST['id_compania']);
            $contrato->save(); //si falla sale por el catch

            //si es un insert tomo el ultimo id insertado, si es un update, el id del contrato.
            $id_contrato = (!$contrato->getIdContrato())? sQuery::dpLastInsertId(): $contrato->getIdContrato();

            $vEmpleados = json_decode($_POST["vEmpleados"], true);
            //print_r($vEmpleados);

            foreach ($vEmpleados as $vE) {

                //$c = new HabilidadEmpleado();
                //$c->setIdHabilidad($vH['id_habilidad']);
                //$c->setIdEmpleado($vE['id_empleado']);
                //if($c->insertHabilidadEmpleado() < 0) $flag = -1;  //si falla algun insert $flag = -1

                //echo "id_contrato :".$id." - id_empleado: ".$vE['id_empleado'];
                //echo "id_contrato :".$id." - procesos: ".$vE['id_proceso'];
                $empleado_contrato = new ContratoEmpleado();
                $empleado_contrato->setIdEmpleadoContrato($vE['id_empleado_contrato']);
                $empleado_contrato->setIdEmpleado($vE['id_empleado']);
                $empleado_contrato->setIdContrato($id_contrato);
                $empleado_contrato->setIdPuesto($vE['id_puesto']);
                $empleado_contrato->setFechaDesde($vE['fecha_desde']);
                $empleado_contrato->setFechaHasta(($vE['fecha_hasta'])? $vE['fecha_hasta']: null); //fecha_desde puede ser null
                $empleado_contrato->setIdLocalidad(($vE['id_localidad'])? $vE['id_localidad']: null);

                //echo 'id empleado contrato: '.$vE['id_empleado_contrato'].'---';

                //echo $vE['operacion'];
                if($vE['operacion']=='insert') {
                    $empleado_contrato->insertEmpleadoContrato();
                    $id_empleado_contrato = sQuery::dpLastInsertId();
                    if($vE['id_proceso']){
                        foreach($vE['id_proceso'] as $p){ //si se agregaron procesos
                            //echo $p." ";
                            $contrato_empleado_proceso = new ContratoEmpleadoProceso();
                            $contrato_empleado_proceso->setIdEmpleadoContrato($id_empleado_contrato);
                            $contrato_empleado_proceso->setIdProceso($p);
                            $contrato_empleado_proceso->contratoEmpleadoProceso(); //si falla sale por el catch
                        }

                    }


                }
                else if( $vE['operacion']=='update') {
                    $empleado_contrato->updateEmpleadoContrato();
                    $id_empleado_contrato = $empleado_contrato->getIdEmpleadoContrato();
                    if($vE['id_proceso']){ // si se editaron procesos
                        foreach($vE['id_proceso'] as $p){
                            //echo $p." ";
                            $contrato_empleado_proceso = new ContratoEmpleadoProceso();
                            $contrato_empleado_proceso->setIdEmpleadoContrato($id_empleado_contrato);
                            $contrato_empleado_proceso->setIdProceso($p);
                            $contrato_empleado_proceso->contratoEmpleadoProceso(); //si falla sale por el catch
                            //echo 'operacion: '.$vE['operacion'].' - id_empleado_contrato: '.$contrato_empleado_proceso->getIdEmpleadoContrato().' - id_proceso: '.$contrato_empleado_proceso->getIdProceso();
                        }

                    }


                }
                else if( $vE['operacion']=='delete') {
                    //Elimina en cascada los registros hijos de la tabla empleado_contrato_proceso
                    $empleado_contrato->deleteEmpleadoContrato(); //si falla sale por el catch
                }


            }

            //Devuelve el resultado a la vista
            sQuery::dpCommit();
            print_r(json_encode(1));

        }
        catch(Exception $e){
            //echo $e->getMessage(); //habilitar para ver el mensaje de error
            sQuery::dpRollback();
            print_r(json_encode(-1));
        }

        exit;
        break;



    case 'newContrato': //ok
        $view->label='Nuevo Contrato';
        $view->contrato = new Contrato();
        $view->empleados = Empleado::getEmpleadosActivos(null); //carga el combo de empleados
        //$view->responsable = $view->contrato->getResponsable()->getApellido()." ".$view->contrato->getResponsable()->getNombre();
        $view->localidades = Localidad::getLocalidades();
        $view->companias = Compania::getCompanias();

        $view->disableLayout=true;
        $view->contentTemplate="view/contratos/contratosForm.php";
        break;

    case 'editContrato': //ok
        $view->contrato = new Contrato($_POST['id']);
        $view->label = $view->contrato->getNombre().' '.$view->contrato->getNroContrato();

        $view->empleado = new Empleado();
        //$view->empleados = $view->empleado->getEmpleadosActivos(null); //carga el combo de empleados
        // si es uno nuevo: trae solo los empleados activos, si es una edicion: trae todos
        $view->empleados = (!$_POST['id'])? Empleado::getEmpleadosActivos(null) : Empleado::getEmpleados(); //carga el combo de empleados
        //$view->responsable = $view->contrato->getResponsable()->getApellido()." ".$view->contrato->getResponsable()->getNombre();
        $view->localidades = Localidad::getLocalidades();
        $view->companias = Compania::getCompanias();

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/contratos/contratosForm.php";
        break;

    case 'editContratoEmpleado': //ok
        $view->contratoEmpleado = ContratoEmpleado::getContratoEmpleado($_POST['id_contrato']);
        print_r(json_encode($view->contratoEmpleado));
        exit;
        break;

    case 'loadEmpleado': //ok //abre la ventana modal para agregar y editar un empleado del contrato
        $view->disableLayout=true;
        $view->empleado = new Empleado($_POST['id_empleado']);
        $view->label = (!$_POST['id_empleado'])? 'Nuevo empleado': $view->empleado->getApellido().' '.$view->empleado->getNombre();

        $temp = $view->empleado->getDomain(); //lo pongo antes en una variable temporal, sino da error en produccion
        if($temp[0] == '') $view->empleado->setDomain(1); //Si es un empleado nuevo (no tiene dominio).. le pongo el dominio 1.
        //echo '<script type="text/javascript"> alert('.sizeof($view->empleado->getDomain()).'); </script>';
        // si es uno nuevo: trae solo los empleados activos, si es una edicion: trae todos
        $view->empleados = (!$_POST['id_empleado'])? Empleado::getEmpleadosActivos(null) : Empleado::getEmpleados(); //carga el combo de empleados
        $view->localidades = Localidad::getLocalidades();
        $view->puestos = Puesto::getPuestos();
        $view->procesos = Proceso::getProcesos();

        $view->contentTemplate="view/contratos/contratosFormEmpleado.php";
        break;

    default : //ok
        $view->contratos = Contrato::getContratos();
        $view->contentTemplate="view/contratos/contratosGrid.php";
        break;
}

if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/contratos/contratosLayout.php');
}


?>
