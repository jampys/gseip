<?php

include_once("model/habilidad-puestoModel.php");

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}


$view->disableLayout=false;


switch ($operation)
{
    case 'buscar': //ok
        $view->disableLayout=true;

        $id_puesto = ($_POST['id_puesto']!='')? $_POST['id_puesto'] : null;
        $id_habilidad = ($_POST['id_habilidad']!='')? $_POST['id_habilidad'] : null;

        $view->habilidadPuesto = HabilidadPuesto::getHabilidadPuesto($id_puesto, $id_habilidad);
        $view->contentTemplate="view/habilidad-puestoGrid.php";
        break;

    case 'new': //ok
        $view->label='Agregar habilidades';
        $view->disableLayout=true;
        $view->habilidades = Habilidad::getHabilidades();
        $view->puestos = Puesto::getPuestos();
        $view->contentTemplate="view/habilidad-puestoFormInsert.php";
        break;

    case 'select_requerida': //carga en el formulario el combo de requerida
        $view->requerida = Soporte::get_enum_values('habilidad_puesto', 'requerida');
        //print_r(json_encode($view->requerida));
        print_r(json_encode(array('enum'=>$view->requerida['enum'], 'default'=>$view->requerida['default'])));
        exit;
        break;

    case 'insert': //ok
        $flag=1;

        sQuery::dpBeginTransaction();

        try{

            $vPuestos = json_decode($_POST["vPuestos"], true);
            $vHabilidades = json_decode($_POST["vHabilidades"], true);
            //print_r($vHabilidades);

            foreach ($vPuestos as $vP) {
                foreach ($vHabilidades as $vH) {
                    $c = new HabilidadPuesto();
                    $c->setIdHabilidad($vH['id_habilidad']);
                    $c->setIdPuesto($vP['id_puesto']);
                    $c->setRequerida($vH['requerida']);
                    if($c->insertHabilidadPuesto() < 0) $flag = -1;  //si falla algun insert $flag = -1
                    //echo "id_puesto: ".$vP['id_puesto']." - id_habilidad: ".$vH['id_habilidad'];
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


    case 'editHabilidadPuesto': //ok
        $view->label='Editar Habilidad Puesto';
        $view->habilidadPuesto = new HabilidadPuesto($_POST['id_habilidad_puesto']);
        $view->requerida = Soporte::get_enum_values('habilidad_puesto', 'requerida');

        $view->disableLayout=true;
        $view->contentTemplate="view/habilidad-puestoFormUpdate.php";
        break;


    case 'saveHabilidadPuesto': //ok //guarda una habilidad-puesto editada

        $view->habilidadPuesto = new HabilidadPuesto($_POST['id_habilidad_puesto']);
        $view->habilidadPuesto->setRequerida($_POST['requerida']);

        $rta = $view->habilidadPuesto->updateHabilidadPuesto();
        print_r(json_encode($rta));
        exit;
        break;

    case 'deleteHabilidadPuesto': //ok
        $habilidad_puesto = new HabilidadPuesto($_POST['id_habilidad_puesto']);
        $rta = $habilidad_puesto->deleteHabilidadPuesto();
        print_r(json_encode($rta));
        die;
        break;

    default : //ok
        $view->habilidades = Habilidad::getHabilidades();
        $view->puestos = Puesto::getPuestos();
        $view->contentTemplate="view/habilidad-puestoGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/habilidad-puestoLayout.php');
}


?>
