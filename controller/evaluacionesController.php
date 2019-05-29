﻿<?php

include_once("model/evaluacionesModel.php");
include_once("model/evaluacionesCompetenciasModel.php");
include_once("model/evaluacionesAspectosGeneralesModel.php");
include_once("model/evaluacionesObjetivosModel.php");
include_once("model/evaluacionesConclusionesModel.php");

include_once("model/contratosModel.php");
include_once("model/empleadosModel.php");
include_once("model/puestosModel.php");
include_once("model/niveles_competenciasModel.php");
include_once("model/localidadesModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        //$periodo = (isset($_POST['periodo']))? $_POST['periodo'] : Soporte::getPeriodoActual();
        $id_contrato = ($_POST['search_contrato']!='')? $_POST['search_contrato'] : null;
        $id_puesto = ($_POST['search_puesto']!='')? $_POST['search_puesto'] : null;
        $id_nivel_competencia = ($_POST['search_nivel_competencia']!='')? $_POST['search_nivel_competencia'] : null;
        $id_localidad = ($_POST['search_localidad']!='')? $_POST['search_localidad'] : null;

        $view->evaluaciones = (!$_POST['cerrado'])?  Evaluacion::getEvaluaciones($_POST['periodo'], $id_contrato, $id_puesto, $id_nivel_competencia, $id_localidad) : Evaluacion::getEvaluaciones1($_POST['periodo'], $id_contrato, $id_puesto, $id_nivel_competencia, $id_localidad);
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



    case 'saveEao': //Guarda una evaluacion de objetivos
        try{
            sQuery::dpBeginTransaction();

            $vObjetivos = json_decode($_POST["vObjetivos"], true);
            //print_r($vCompetencias);

            foreach ($vObjetivos as $vO) {

                //$c = new HabilidadEmpleado();
                //$c->setIdHabilidad($vH['id_habilidad']);
                //$c->setIdEmpleado($vE['id_empleado']);
                //if($c->insertHabilidadEmpleado() < 0) $flag = -1;  //si falla algun insert $flag = -1
                //echo "id_contrato :".$id." - id_empleado: ".$vE['id_empleado'];
                $evaluacion_objetivo = new EvaluacionObjetivo();
                $evaluacion_objetivo->setIdEvaluacionObjetivo($vO['id_evaluacion_objetivo']);
                $evaluacion_objetivo->setIdObjetivo($vO['id_objetivo']);
                //$evaluacion_objetivo->setIdPuntajeObjetivo($vO['id_puntaje_objetivo']);
                $evaluacion_objetivo->setIdPuntajeObjetivo(($vO['id_puntaje_objetivo'] !='')? $vO['id_puntaje_objetivo'] : null);
                $evaluacion_objetivo->setIdEmpleado($vO['id_empleado']);
                $evaluacion_objetivo->setIdEvaluador($_SESSION["id_user"]);
                $evaluacion_objetivo->setIdPlanEvaluacion($vO['id_plan_evaluacion']);
                $evaluacion_objetivo->setPeriodo($vO['periodo']);
                $evaluacion_objetivo->setPonderacion($vO['ponderacion']);

                //echo 'id objetivo sub: '.$vS['id_objetivo_sub'].'---';

                //echo $vS['operacion'];
                $evaluacion_objetivo->save(); //si falla sale por el catch

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


    case 'saveEaconcl': //Guarda una evaluacion conclusion
        $conclusion = new EvaluacionConclusion($_POST['id_empleado'], $_POST['id_plan_evaluacion']);
        $conclusion->setIdEvaluador($_SESSION["id_user"]);
        $conclusion->setIdEmpleado($_POST['id_empleado']);
        $conclusion->setIdPlanEvaluacion($_POST['id_plan_evaluacion']);
        //$puesto->setIdPuestoSuperior(($_POST['id_puesto_superior'])? $_POST['id_puesto_superior'] : null);
        $conclusion->setPeriodo($_POST['periodo']);
        $conclusion->setFortalezas($_POST['fortalezas']);
        $conclusion->setAspectosMejorar($_POST['aspectos_mejorar']);

        $rta = $conclusion->save();
        print_r(json_encode($rta));
        exit;
        break;



    case 'loadEac': //Abre el formulario de evaluacion anual de competecias //ok
        $view->empleado = new Empleado($_POST['id_empleado']);
        $view->label = 'Evaluación de competencias: '.$view->empleado->getApellido().' '.$view->empleado->getNombre();
        //$periodo = (isset($_POST['periodo']))? $_POST['periodo'] : Soporte::getPeriodoActual();

        $view->competencias = EvaluacionCompetencia::getCompetencias($_POST['id_empleado'], $_POST['periodo']);

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
        $view->label = 'Evaluación de aspectos generales: '.$view->empleado->getApellido().' '.$view->empleado->getNombre();

        $view->aspectos_generales = EvaluacionAspectoGeneral::getAspectosGenerales($_POST['id_empleado'], $_POST['periodo']);
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


    case 'loadEao': //Abre el formulario de evaluacion anual de objetivos
        $view->empleado = new Empleado($_POST['id_empleado']);
        $view->label = 'Evaluación de objetivos: '.$view->empleado->getApellido().' '.$view->empleado->getNombre();

        $view->objetivos = EvaluacionObjetivo::getObjetivos($_POST['id_empleado'], $_POST['periodo']);
        $view->params = array('id_empleado' => $_POST['id_empleado'], 'id_plan_evaluacion' => $_POST['id_plan_evaluacion'], 'periodo'=> $_POST['periodo'], 'cerrado'=> $_POST['cerrado']);
        $view->puntajes = EvaluacionObjetivo::getPuntajes();

        //$view->dias_paro = EvaluacionAspectoGeneral::getDiasParo($_POST['id_empleado']);

        $view->disableLayout=true;
        $view->contentTemplate="view/evaluaciones/evaluaciones-eaoForm.php";
        break;

    case 'loadEaconcl': //Abre el formulario de conclusiones //ok
        $view->empleado = new Empleado($_POST['id_empleado']);
        $view->label = 'Comentarios de la evaluación: '.$view->empleado->getApellido().' '.$view->empleado->getNombre();

        //$view->conclusion = new EvaluacionConclusion($_POST['id_evaluacion_conclusion']);
        $view->conclusion = new EvaluacionConclusion($_POST['id_empleado'], $_POST['id_plan_evaluacion'] );
        $view->params = array('id_empleado' => $_POST['id_empleado'], 'id_plan_evaluacion' => $_POST['id_plan_evaluacion'], 'periodo'=> $_POST['periodo']);

        $view->disableLayout=true;
        $view->contentTemplate="view/evaluaciones/evaluaciones-eaconclForm.php";
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

    case 'loadGauss':
        $view->evaluaciones = new Evaluacion();

        $id_contrato = ($_POST['search_contrato']!='')? $_POST['search_contrato'] : null;
        $id_puesto = ($_POST['search_puesto']!='')? $_POST['search_puesto'] : null;
        $id_nivel_competencia = ($_POST['search_nivel_competencia']!='')? $_POST['search_nivel_competencia'] : null;
        $id_localidad = ($_POST['search_localidad']!='')? $_POST['search_localidad'] : null;

        $view->rta = $view->evaluaciones->graficarGauss($_POST['periodo'], $id_contrato, $id_puesto, $id_nivel_competencia, $id_localidad);
        //$view->puntajes = json_encode($view->rta);

        $detalle = array();

        foreach($view->rta as $row){
            $puntaje = "";
            $array_puntajes = explode(' ', $row['puntaje']);
            if($_POST['categoria']==0) {$puntaje = $array_puntajes[0]; $view->categoria= 'todas';} //pje total
            elseif($_POST['categoria']==1) {$puntaje = $array_puntajes[2]; $view->categoria= 'aspectos generales';}  //aspectos generales
            elseif($_POST['categoria']==2) {$puntaje = $array_puntajes[4]; $view->categoria= 'competencias';}  //competencias
            elseif($_POST['categoria']==3) {$puntaje = $array_puntajes[6]; $view->categoria= 'objetivos';}  //objetivos


            $detalle[] = array( 'id_empleado'=>$row['id_empleado'],
                'legajo'=>$row['legajo'],
                'apellido'=>$row['apellido'],
                'nombre'=>$row['nombre'],
                'id_empleado_contrato'=>$row['id_empleado_contrato'],
                'id_contrato'=>$row['id_contrato'],
                'id_puesto'=>$row['id_puesto'],
                'contrato'=>$row['contrato'],
                'puesto'=>$row['puesto'],
                'id_plan_evaluacion'=>$row['id_plan_evaluacion'],
                'periodo'=>$row['periodo'],
                'cerrado'=>$row['cerrado'],
                'puntaje'=>$puntaje,
                'isInSup'=>$row['isInSup'],
                'isSup'=>$row['isSup'],

            );
        }

        $view->puntajes_json = json_encode($detalle);
        $view->puntajes = $detalle;







        $view->label = 'Función de densidad';
        $view->periodo = $_POST['periodo'];
        $view->c = new Contrato($_POST['search_contrato']);
        $view->contrato = ($_POST['search_contrato'])? $view->c->getNombre() : 'todos';
        $view->p = new Puesto($_POST['search_puesto']);
        $view->puesto = ($_POST['search_puesto'])? $view->p->getNombre() : 'todos';
        $view->n = new NivelCompetencia($_POST['search_nivel_competencia']);
        $view->nivel_competencia = ($_POST['search_nivel_competencia'])? $view->n->getNombre() : 'todos';

        $view->l = new Localidad($_POST['search_localidad']);
        $view->localidad = ($_POST['search_localidad'])? $view->l->getCiudad() : 'todas';


        $view->disableLayout=true;
        $view->contentTemplate="view/evaluaciones/evaluaciones-gauss1.php";
        //print_r(json_encode($rta));
        //exit;
        break;


    /*case 'graficarGauss':
        $view->evaluaciones = new Evaluacion();
        $rta = $view->evaluaciones->graficarGauss($_POST['periodo'], $_POST['id_contrato']);
        print_r(json_encode($rta));
        exit;
        break;*/


    default : //ok
        $view->periodos = Evaluacion::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->contratos = Contrato::getContratos(); //carga el combo para filtrar contratos
        $view->puestos = Puesto::getPuestos();
        $view->niveles_competencias = NivelCompetencia::getNivelesCompetencias();
        $view->localidades = Localidad::getLocalidades();

        $view->contentTemplate="view/evaluaciones/evaluacionesGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/evaluaciones/evaluacionesLayout.php');
}


?>
