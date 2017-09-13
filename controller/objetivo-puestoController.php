<?php

include_once("model/objetivo-puestoModel.php");
include_once("model/contratosModel.php");

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view->disableLayout=false;


switch ($operation)
{
    case 'buscar': //ok
        $view->disableLayout=true;

        $id_puesto = ($_POST['id_puesto']!='')? $_POST['id_puesto'] : null;
        $id_objetivo = ($_POST['id_objetivo']!='')? $_POST['id_objetivo'] : null;
        $periodo = ($_POST['periodo']!='')? $_POST['periodo'] : null;

        $view->objetivoPuesto = ObjetivoPuesto::getObjetivoPuesto($id_puesto, $id_objetivo, $periodo);
        $view->contentTemplate="view/objetivo-puestoGrid.php";
        break;

    case 'new': //ok
        $view->label='Agregar objetivos';

        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->periodos = Soporte::getPeriodos($view->periodo_actual, $view->periodo_actual + 1); //periodo actual y el siguiente
        $view->contratos = Contrato::getContratos();

        $view->disableLayout=true;
        $view->contentTemplate="view/objetivo-puestoFormInsert.php";
        break;

    case 'loadObjetivo': //ok //abre la ventana modal para agregar y editar un objetivo
        $view->label='Objetivo';
        $view->disableLayout=true;
        //$view->puesto = Puesto::getPuestos();

        $view->contentTemplate="view/objetivo-puestoFormUpdate.php";
        break;

    case 'insert': //ok
        $flag=1;

        sQuery::dpBeginTransaction();

        try{

            $vPuestos = json_decode($_POST["vPuestos"], true);
            $vObjetivos = json_decode($_POST["vObjetivos"], true);
            //print_r($vObjetivos);

            foreach ($vPuestos as $vP) {
                foreach ($vObjetivos as $vO) {
                    $c = new ObjetivoPuesto();
                    $c->setIdObjetivo($vO['id_objetivo']);
                    $c->setIdPuesto($vP['id_puesto']);
                    $c->setPeriodo($_POST["periodo"]);
                    $c->setIdContrato($_POST["id_contrato"]);
                    $c->setValor($vO['valor']);
                    if($c->insertObjetivoPuesto() < 0) $flag = -1;  //si falla algun insert $flag = -1
                    //echo "id_puesto: ".$vP['id_puesto']." - id_objetivo: ".$vO['id_objetivo'];
                }

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


    case 'editHabilidadPuesto':
        $view->label='Editar Habilidad Puesto';
        $view->habilidadPuesto = new HabilidadPuesto($_POST['id_habilidad_puesto']);
        $view->requerida = Soporte::get_enum_values('habilidad_puesto', 'requerida');

        $view->disableLayout=true;
        $view->contentTemplate="view/habilidad-puestoFormUpdate.php";
        break;


    case 'saveHabilidadPuesto':  //guarda una habilidad-puesto editada

        $view->habilidadPuesto = new HabilidadPuesto($_POST['id_habilidad_puesto']);
        $view->habilidadPuesto->setRequerida($_POST['requerida']);

        $rta = $view->habilidadPuesto->updateHabilidadPuesto();
        print_r(json_encode($rta));
        exit;
        break;

    case 'deleteHabilidadPuesto':
        $habilidad_puesto = new HabilidadPuesto($_POST['id_habilidad_puesto']);
        $rta = $habilidad_puesto->deleteHabilidadPuesto();
        print_r(json_encode($rta));
        die;
        break;

    default : //ok
        $view->periodos = ObjetivoPuesto::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->contentTemplate="view/objetivo-puestoGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/objetivo-puestoLayout.php');
}


?>
