<?php

include_once("model/evaluacionesModel.php");
include_once("model/evaluacionesCompetenciasModel.php");
include_once("model/evaluacionesAspectosGeneralesModel.php");
include_once("model/contratosModel.php");
include_once("model/empleadosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        //$periodo = (isset($_POST['periodo']))? $_POST['periodo'] : Soporte::getPeriodoActual();
        $id_contrato = ($_POST['search_contrato']!='')? $_POST['search_contrato'] : null;

        $view->evaluaciones = (!$_POST['cerrado'])?  Evaluacion::getEvaluaciones($_POST['periodo'], $id_contrato) : Evaluacion::getEvaluaciones1($_POST['periodo'], $id_contrato);
        $view->contentTemplate="view/evaluaciones/evaluacionesGrid.php";
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
                $evaluacion_competencia->setIdPuntajeCompetencia($vC['id_puntaje_competencia']);
                $evaluacion_competencia->setIdEmpleado($vC['id_empleado']);
                $evaluacion_competencia->setIdEvaluador($_SESSION["id_user"]);
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


    case 'saveEaag': //Guarda una evaluacion de aspectos generales //ok
        try{
            sQuery::dpBeginTransaction();

            $vAspectosGenerales = json_decode($_POST["vAspectosGenerales"], true);
            //print_r($vCompetencias);

            foreach ($vAspectosGenerales as $vAG) {

                //$c = new HabilidadEmpleado();
                //$c->setIdHabilidad($vH['id_habilidad']);
                //$c->setIdEmpleado($vE['id_empleado']);
                //if($c->insertHabilidadEmpleado() < 0) $flag = -1;  //si falla algun insert $flag = -1
                //echo "id_contrato :".$id." - id_empleado: ".$vE['id_empleado'];
                $evaluacion_aspecto_general = new EvaluacionAspectoGeneral();
                $evaluacion_aspecto_general->setIdEvaluacionAspectoGeneral($vAG['id_evaluacion_aspecto_general']);
                $evaluacion_aspecto_general->setIdAspectoGeneral($vAG['id_aspecto_general']);
                $evaluacion_aspecto_general->setIdPuntajeAspectoGeneral($vAG['id_puntaje_aspecto_general']);
                $evaluacion_aspecto_general->setIdEmpleado($vAG['id_empleado']);
                $evaluacion_aspecto_general->setIdEvaluador($_SESSION["id_user"]);
                $evaluacion_aspecto_general->setIdPlanEvaluacion($vAG['id_plan_evaluacion']);
                $evaluacion_aspecto_general->setPeriodo($vAG['periodo']);

                //echo 'id objetivo sub: '.$vS['id_objetivo_sub'].'---';

                //echo $vS['operacion'];
                $evaluacion_aspecto_general->save(); //si falla sale por el catch

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
        $view->empleado = new Empleado($_POST['id_empleado']);
        $view->label = 'Evaluacion de competencias: '.$view->empleado->getApellido().' '.$view->empleado->getNombre();
        //$periodo = (isset($_POST['periodo']))? $_POST['periodo'] : Soporte::getPeriodoActual();

        //$view->competencias = EvaluacionCompetencia::getCompetencias($_POST['id_empleado'], $periodo);
        $view->competencias = (!$_POST['cerrado'])? EvaluacionCompetencia::getCompetencias($_POST['id_empleado'], $_POST['periodo']) : EvaluacionCompetencia::getCompetencias1($_POST['id_empleado'], $_POST['periodo']);

        $view->params = array('id_empleado' => $_POST['id_empleado'], 'id_plan_evaluacion' => $_POST['id_plan_evaluacion'], 'periodo'=> $_POST['periodo'], 'cerrado'=> $_POST['cerrado']);

        $view->temp = EvaluacionCompetencia::getPuntajes(); //trae todas las competencias con todos sus puntajes
        $view->puntajes = array();

        //este foreach genera un array asociativo... donde cada competencia contiene un array por cada puntaje
        foreach ($view->temp as $pu){
            //$view->puntajes[$pu['id_competencia']][] = array('id_puntaje' => $pu['id_puntaje'], 'nro_orden' => $pu['nro_orden']);
            $view->puntajes[$pu['id_competencia']][] = array('id_puntaje_competencia' => $pu['id_puntaje_competencia'], 'puntaje' => $pu['puntaje']);
        }

        $view->disableLayout=true;
        $view->contentTemplate="view/evaluaciones/evaluaciones-eacForm.php";
        break;


    case 'loadEaag': //Abre el formulario de evaluacion anual de aspectos generales //ok
        $view->empleado = new Empleado($_POST['id_empleado']);
        $view->label = 'Evaluacion de aspectos generales: '.$view->empleado->getApellido().' '.$view->empleado->getNombre();

        $view->aspectos_generales = (!$_POST['cerrado'])? EvaluacionAspectoGeneral::getAspectosGenerales($_POST['id_empleado'], $_POST['periodo']) : EvaluacionAspectoGeneral::getAspectosGenerales1($_POST['id_empleado'], $_POST['periodo']);
        $view->params = array('id_empleado' => $_POST['id_empleado'], 'id_plan_evaluacion' => $_POST['id_plan_evaluacion'], 'periodo'=> $_POST['periodo'], 'cerrado'=> $_POST['cerrado']);

        $view->temp = EvaluacionAspectoGeneral::getPuntajes(); //trae todos los aspectos generales con todos sus puntajes
        $view->puntajes = array();

        //este foreach genera un array asociativo... donde cada aspecto general contiene un array por cada puntaje
        foreach ($view->temp as $pu){
            $view->puntajes[$pu['id_aspecto_general']][] = array('id_puntaje_aspecto_general' => $pu['id_puntaje_aspecto_general'], 'puntaje' => $pu['puntaje']);
        }

        $view->dias_paro = EvaluacionAspectoGeneral::getDiasParo($_POST['id_empleado']);

        $view->disableLayout=true;
        $view->contentTemplate="view/evaluaciones/evaluaciones-eaagForm.php";
        break;


    case 'loadEac_help': //ok
        $view->puntaje_competencia = EvaluacionCompetencia::getPuntajesHelp();
        print_r(json_encode($view->puntaje_competencia));
        exit;
        break;

    case 'loadEaag_help': //ok
        $view->puntaje_aspecto_general = EvaluacionAspectoGeneral::getPuntajesHelp();
        print_r(json_encode($view->puntaje_aspecto_general));
        exit;
        break;


    default : //ok
        $view->periodos = Evaluacion::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->contratos = Contrato::getContratos(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/evaluaciones/evaluacionesGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/evaluaciones/evaluacionesLayout.php');
}


?>
