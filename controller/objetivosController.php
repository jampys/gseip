<?php

include_once("model/objetivosModel.php");
include_once("model/procesosModel.php");
include_once("model/areasModel.php");
include_once("model/contratosModel.php");
include_once("model/subobjetivosModel.php");

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        //$view->periodos = Objetivo::getPeriodos();
        $periodo = (isset($_POST['periodo']))? $_POST['periodo'] : Soporte::getPeriodoActual();
        $view->objetivos = Objetivo::getObjetivos($periodo);
        $view->contentTemplate="view/objetivosGrid.php";
        break;


    case 'saveObjetivo':
        $flag=1;

        sQuery::dpBeginTransaction();

        try{

            $objetivo = new Objetivo($_POST['id_objetivo']);
            $objetivo->setNroContrato($_POST['nro_contrato']);
            $contrato->setFechaDesde($_POST['fecha_desde']);
            $contrato->setFechaHasta($_POST['fecha_hasta']);
            $contrato->setIdResponsable($_POST['id_responsable']);
            $contrato->setIdCompania($_POST['id_compania']);
            if($contrato->save() < 0) $flag = -1;

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
                $empleado_contrato = new ContratoEmpleado();
                $empleado_contrato->setIdEmpleadoContrato($vE['id_empleado_contrato']);
                $empleado_contrato->setIdEmpleado($vE['id_empleado']);
                $empleado_contrato->setIdContrato($id_contrato);
                $empleado_contrato->setIdPuesto($vE['id_puesto']);
                $empleado_contrato->setFechaDesde($vE['fecha_desde']);
                $empleado_contrato->setFechaHasta($vE['fecha_hasta']);

                //echo 'id empleado contrato: '.$vE['id_empleado_contrato'].'---';

                //echo $vE['operacion'];
                if($vE['operacion']=='insert') {if($empleado_contrato->insertEmpleadoContrato() < 0) $flag = -1;}
                else if( $vE['operacion']=='update') {if($empleado_contrato->updateEmpleadoContrato() < 0) $flag = -1;}
                else if( $vE['operacion']=='delete') {if($empleado_contrato->deleteEmpleadoContrato() < 0) $flag = -1;}


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

    case 'newObjetivo': //ok
        $view->objetivo = new Objetivo();
        $view->label='Nuevo objetivo';

        $view->periodos = Objetivo::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->procesos = Proceso::getProcesos();
        $view->areas = Area::getAreas();
        $view->contratos = Contrato::getContratos();
        $view->frecuencias = Soporte::get_enum_values('objetivos', 'frecuencia');

        $view->responsable_ejecucion = $view->objetivo->getResponsableEjecucion()->getApellido()." ".$view->objetivo->getResponsableEjecucion()->getNombre();
        $view->responsable_seguimiento = $view->objetivo->getResponsableSeguimiento()->getApellido()." ".$view->objetivo->getResponsableSeguimiento()->getNombre();

        $view->disableLayout=true;
        $view->contentTemplate="view/objetivosForm.php";
        break;

    case 'editObjetivo': //ok
        $view->objetivo = new Objetivo($_POST['id_objetivo']);
        $view->label='Editar Objetivo';


        $view->periodos = Objetivo::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->procesos = Proceso::getProcesos();
        $view->areas = Area::getAreas();
        $view->contratos = Contrato::getContratos();
        $view->frecuencias = Soporte::get_enum_values('objetivos', 'frecuencia');

        $view->responsable_ejecucion = $view->objetivo->getResponsableEjecucion()->getApellido()." ".$view->objetivo->getResponsableEjecucion()->getNombre();
        $view->responsable_seguimiento = $view->objetivo->getResponsableSeguimiento()->getApellido()." ".$view->objetivo->getResponsableSeguimiento()->getNombre();

        $view->disableLayout=true;
        $view->contentTemplate="view/objetivosForm.php";
        break;


    case 'editObjetivoSubobjetivos': //ok
        $view->subobjetivos = Subobjetivo::getSubobjetivos($_POST['id_objetivo']);
        print_r(json_encode($view->subobjetivos));
        exit;
        break;

    case 'deleteObjetivo':
        $objetivo = new Objetivo($_POST['id_objetivo']);
        $rta = $objetivo->deleteObjetivo();
        print_r(json_encode($rta));
        die;
        break;

    case 'autocompletarObjetivos': //ok
        $view->objetivo = new Objetivo();
        $rta=$view->objetivo->autocompletarObjetivos($_POST['term']);
        print_r(json_encode($rta));
        exit;
        break;

    case 'loadSubObjetivo': //ok //abre la ventana modal para agregar y editar un subobjetivo del objetivo
        $view->label='Sub objetivo';
        $view->disableLayout=true;
        $view->areas = Area::getAreas();

        $view->contentTemplate="view/objetivosFormSubObjetivo.php";
        break;

    default : //ok
        $view->periodos = Objetivo::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->objetivos = Objetivo::getObjetivos($view->periodo_actual);
        $view->contentTemplate="view/objetivosGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/objetivosLayout.php');
}


?>
