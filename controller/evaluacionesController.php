<?php

include_once("model/evaluacionesModel.php");
include_once("model/evaluacionesCompetenciasModel.php");
include_once("model/contratosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        //$periodo = (isset($_POST['periodo']))? $_POST['periodo'] : Soporte::getPeriodoActual();
        $id_contrato = ($_POST['search_contrato']!='')? $_POST['search_contrato'] : null;

        $view->evaluaciones = (!$_POST['cerrado'])?  Evaluacion::getEvaluaciones($_POST['periodo']) : Evaluacion::getEvaluaciones1($_POST['periodo']);
        $view->contentTemplate="view/evaluacionesGrid.php";
        break;


    case 'saveEac': //Guarda una evaluacion de competencias //ok
        try{
            sQuery::dpBeginTransaction();

            $vCompetencias = json_decode($_POST["vCompetencias"], true);
            //print_r($vCompetencias);

            foreach ($vCompetencias as $vC) {

                //$c = new HabilidadEmpleado();
                //$c->setIdHabilidad($vH['id_habilidad']);
                //$c->setIdEmpleado($vE['id_empleado']);
                //if($c->insertHabilidadEmpleado() < 0) $flag = -1;  //si falla algun insert $flag = -1
                //echo "id_contrato :".$id." - id_empleado: ".$vE['id_empleado'];
                $evaluacion_competencia = new EvaluacionCompetencia();
                $evaluacion_competencia->setIdEvaluacionCompetencia($vC['id_evaluacion_competencia']);
                $evaluacion_competencia->setIdCompetencia($vC['id_competencia']);
                $evaluacion_competencia->setIdPuntaje($vC['id_puntaje']);
                $evaluacion_competencia->setIdEmpleado($vC['id_empleado']);
                $evaluacion_competencia->setIdPlanEvaluacion($vC['id_plan_evaluacion']);
                $evaluacion_competencia->setPeriodo($vC['periodo']);

                //echo 'id objetivo sub: '.$vS['id_objetivo_sub'].'---';

                //echo $vS['operacion'];
                $evaluacion_competencia->save(); //si falla sale por el catch

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



    case 'loadEac': //Abre el formulario de evaluacion anual de competecias //ok
        $view->label='Evaluacion de competencias';
        //$periodo = (isset($_POST['periodo']))? $_POST['periodo'] : Soporte::getPeriodoActual();

        //$view->competencias = EvaluacionCompetencia::getCompetencias($_POST['id_empleado'], $periodo);
        $view->competencias = (!$_POST['cerrado'])? EvaluacionCompetencia::getCompetencias($_POST['id_empleado'], $_POST['periodo']) : EvaluacionCompetencia::getCompetencias1($_POST['id_empleado'], $_POST['periodo']);

        $view->params = array('id_empleado' => $_POST['id_empleado'], 'id_plan_evaluacion' => $_POST['id_plan_evaluacion'], 'periodo'=> $_POST['periodo'], 'cerrado'=> $_POST['cerrado']);

        $view->puntajes = EvaluacionCompetencia::getPuntajes();

        $view->disableLayout=true;
        $view->contentTemplate="view/evaluaciones-eacForm.php";
        break;

    case 'loadEac_help': //ok
        $view->puntaje_competencia = EvaluacionCompetencia::getPuntajeCompetencia();
        print_r(json_encode($view->puntaje_competencia));
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

    default : //ok
        $view->periodos = Evaluacion::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->contratos = Contrato::getContratos(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/evaluacionesGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/evaluacionesLayout.php');
}


?>
