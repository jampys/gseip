﻿<?php

include_once("model/habilidad-empleadoModel.php");

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view->disableLayout=false;


switch ($operation)
{
    case 'buscar': //ok
        $view->disableLayout=true;

        $cuil = ($_POST['cuil']!='')? $_POST['cuil'] : null;
        $id_habilidad = ($_POST['id_habilidad']!='')? $_POST['id_habilidad'] : null;

        $view->habilidadEmpleado = HabilidadEmpleado::getHabilidadEmpleado($cuil, $id_habilidad);
        $view->contentTemplate="view/habilidad-empleadoGrid.php";
        break;

    case 'new': //ok
        $view->label='Agregar habilidades';
        $view->disableLayout=true;
        $view->contentTemplate="view/habilidad-empleadoForm.php";
        break;

    case 'insert': //ok
        $rta=1;

        /*$habilidad = new Habilidad($_POST['id_habilidad']);
                $habilidad->setCodigo($_POST['codigo']);
                $habilidad->setNombre($_POST['nombre']);
                $habilidad->setTipo($_POST['tipo']);

                $rta = $habilidad->save();
                print_r(json_encode($rta));*/


        sQuery::dpBeginTransaction();

        try{

            $vEmpleados = json_decode($_POST["vEmpleados"], true);
            $vHabilidades = json_decode($_POST["vHabilidades"], true);
            print_r($vHabilidades);

            foreach ($vEmpleados as $vE) {
                foreach ($vHabilidades as $vH) {

                    /*$c = new HabilidadEmpleado();
                    $c->setIdHabilidad($vH['id_habilidad']);
                    $c->setIdEmpleado($vE['id_empleado']);
                    if($c->insertHabilidadEmpleado() < 0) $rta = -1;*/
                    //echo $vE['id_empleado'];
                    //echo $vH['id_habilidad'];
                }

            }

            //Devuelve el resultado a la vista
            if($rta > 0) sQuery::dpCommit();
            else sQuery::dpRollback();

            print_r(json_encode($rta));

        }
        catch(Exception $e){
            echo 'entro por la excepcion';

            //echo $e->getMessage();
            sQuery::dpRollback();
            print_r(json_encode(-1));
        }


        exit;
        break;


    case 'editHabilidad':
        /*$view->label='Editar Habilidad';
        $view->habilidad = new Habilidad($_POST['id_habilidad']);

        $view->tipos = Soporte::get_enum_values('habilidades', 'tipo');

        $view->disableLayout=true;
        $view->contentTemplate="view/habilidadesForm.php";*/
        break;

    case 'deleteHabilidad':
        /*$habilidad = new Habilidad($_POST['id_habilidad']);
        $rta = $habilidad->deleteHabilidad();
        print_r(json_encode($rta));
        die; */
        break;

    default :
        //$view->habilidades = Habilidad::getHabilidades();
        $view->contentTemplate="view/habilidad-empleadoGrid.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);}
else {
    include_once('view/habilidad-empleadoLayout.php');
}


?>
