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
        $view->contentTemplate="view/habilidad-puestoForm.php";
        break;

    case 'select_requerida': //carga en el formulario el combo de requerida
        //$rta = "culo";
        $view->requerida = Soporte::get_enum_values('habilidad_puesto', 'requerida');
        //print_r(json_encode($view->requerida));
        print_r(json_encode(array('enum'=>$view->requerida['enum'], 'default'=>$view->requerida['default'])));
        exit;
        break;

    case 'insert':
        $flag=1;

        sQuery::dpBeginTransaction();

        try{

            $vEmpleados = json_decode($_POST["vEmpleados"], true);
            $vHabilidades = json_decode($_POST["vHabilidades"], true);
            //print_r($vHabilidades);

            foreach ($vEmpleados as $vE) {
                foreach ($vHabilidades as $vH) {
                    $c = new HabilidadEmpleado();
                    $c->setIdHabilidad($vH['id_habilidad']);
                    $c->setIdEmpleado($vE['id_empleado']);
                    if($c->insertHabilidadEmpleado() < 0) $flag = -1;  //si falla algun insert $flag = -1
                    //echo "id_empleado: ".$vE['id_empleado']." - id_habilidad: ".$vH['id_habilidad'];
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


    case 'editHabilidad':
        /*$view->label='Editar Habilidad';
        $view->habilidad = new Habilidad($_POST['id_habilidad']);

        $view->tipos = Soporte::get_enum_values('habilidades', 'tipo');

        $view->disableLayout=true;
        $view->contentTemplate="view/habilidadesForm.php";*/
        break;

    case 'deleteHabilidadEmpleado':
        $habilidad_empleado = new HabilidadEmpleado($_POST['id_habilidad_empleado']);
        $rta = $habilidad_empleado->deleteHabilidadEmpleado();
        print_r(json_encode($rta));
        die;
        break;

    default : //ok
        //$view->habilidades = Habilidad::getHabilidades();
        $view->contentTemplate="view/habilidad-puestoGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/habilidad-puestoLayout.php');
}


?>
