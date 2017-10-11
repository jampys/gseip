<?php

include_once("model/evaluacionesModel.php");
include_once("model/evaluacionesCompetenciasModel.php");
//include_once("model/procesosModel.php");
//include_once("model/areasModel.php");
//include_once("model/contratosModel.php");
//include_once("model/subobjetivosModel.php");

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        //$view->periodos = Evaluacion::getPeriodos();
        $periodo = (isset($_POST['periodo']))? $_POST['periodo'] : Soporte::getPeriodoActual();
        $view->evaluaciones = Evaluacion::getEvaluaciones($periodo);
        $view->contentTemplate="view/evaluacionesGrid.php";
        break;


    case 'saveObjetivo':
        $flag=1;

        sQuery::dpBeginTransaction();

        try{

            $objetivo = new Objetivo($_POST['id_objetivo']);
            $objetivo->setPeriodo($_POST['periodo']);
            $objetivo->setNombre($_POST['nombre']);
            $objetivo->setIdProceso(($_POST['id_proceso'])? $_POST['id_proceso'] : null);
            $objetivo->setIdArea(($_POST['id_area'])? $_POST['id_area'] : null);
            $objetivo->setIdContrato(($_POST['id_contrato'])? $_POST['id_contrato'] : null);
            $objetivo->setMeta($_POST['meta']);
            $objetivo->setActividades($_POST['actividades']);
            $objetivo->setIndicador($_POST['indicador']);
            $objetivo->setFrecuencia($_POST['frecuencia']);
            $objetivo->setIdResponsableEjecucion($_POST['id_responsable_ejecucion']);
            $objetivo->setIdResponsableSeguimiento($_POST['id_responsable_seguimiento']);

            if($objetivo->save() < 0) $flag = -1;

            //si es un insert tomo el ultimo id insertado, si es un update, el id del contrato.
            $id_objetivo = (!$objetivo->getIdObjetivo())? sQuery::dpLastInsertId(): $objetivo->getIdObjetivo();

            $vSubobjetivos = json_decode($_POST["vSubobjetivos"], true);
            //print_r($vSubobjetivos);

            foreach ($vSubobjetivos as $vS) {

                //$c = new HabilidadEmpleado();
                //$c->setIdHabilidad($vH['id_habilidad']);
                //$c->setIdEmpleado($vE['id_empleado']);
                //if($c->insertHabilidadEmpleado() < 0) $flag = -1;  //si falla algun insert $flag = -1
                //echo "id_contrato :".$id." - id_empleado: ".$vE['id_empleado'];
                $subobjetivo = new Subobjetivo();
                $subobjetivo->setIdObjetivoSub($vS['id_objetivo_sub']);
                $subobjetivo->setNombre($vS['nombre']);
                $subobjetivo->setIdObjetivo($id_objetivo);
                $subobjetivo->setIdArea($vS['id_area']);

                //echo 'id objetivo sub: '.$vS['id_objetivo_sub'].'---';

                //echo $vS['operacion'];
                if($vS['operacion']=='insert') {if($subobjetivo->insertSubobjetivo() < 0) $flag = -1;}
                else if( $vS['operacion']=='update') {if($subobjetivo->updateSubobjetivo() < 0) $flag = -1;}
                else if( $vS['operacion']=='delete') {if($subobjetivo->deleteSubobjetivo() < 0) $flag = -1;}


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



    case 'loadEac': //Abre el formulario de evaluacion anual de competecias
        $view->label='Evaluacion de competencias';
        $view->evaluacion_competencia = new EvaluacionCompetencia($_POST['id_evaluacion_competencia']);

        $periodo = (isset($_POST['periodo']))? $_POST['periodo'] : Soporte::getPeriodoActual();
        $view->competencias = EvaluacionCompetencia::getCompetencias($_POST['id_empleado'], $periodo);
        $view->puntajes = EvaluacionCompetencia::getPuntajes();

        $view->disableLayout=true;
        $view->contentTemplate="view/evaluaciones-eacForm.php";
        break;


    case 'editObjetivoSubobjetivos':
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


    case 'loadSubObjetivo':  //abre la ventana modal para agregar y editar un subobjetivo del objetivo
        $view->label='Sub objetivo';
        $view->disableLayout=true;
        $view->areas = Area::getAreas();

        $view->contentTemplate="view/objetivosFormSubObjetivo.php";
        break;

    default :
        $view->periodos = Evaluacion::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->evaluaciones = Evaluacion::getEvaluaciones($view->periodo_actual);
        $view->contentTemplate="view/evaluacionesGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/evaluacionesLayout.php');
}


?>
