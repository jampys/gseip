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

                echo $vS['operacion'];
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
