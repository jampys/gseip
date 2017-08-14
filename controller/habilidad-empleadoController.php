<?php

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


        try {

            sQuery::dpBeginTransaction();

            $stmt=new sQuery();
            $query="insert into clientes( nombre, apellido, fecha_nac,peso)values(:nombre, :apellido, STR_TO_DATE(:fecha, '%d/%m/%Y'), :peso)";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nombre', 'TOPO');
            $stmt->dpBind(':apellido', 'LOCO');
            $stmt->dpBind(':fecha', '01/01/2015');
            $stmt->dpBind(':peso', '75');
            $stmt->dpExecute();
            print_r($stmt->chupala());



            $stmt=new sQuery();
            $query="insert into clientes( nombre, apellido, fecha_nac,peso)values(:nombre, :apellido, STR_TO_DATE(:fecha, '%d/%m/%Y'), :peso)";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nombre', 'TAPA');
            $stmt->dpBind(':apellido', 'LOCA');
            $stmt->dpBind(':fecha', '01/01/2015');
            $stmt->dpBind(':peso', '75');
            $stmt->dpExecute();
            print_r($stmt->chupala());

            sQuery::dpCommit();

        } catch(PDOException $e) {

            sQuery::dpRollback();


        }



    case 'editHabilidad':
        $view->label='Editar Habilidad';
        $view->habilidad = new Habilidad($_POST['id_habilidad']);

        $view->tipos = Soporte::get_enum_values('habilidades', 'tipo');

        $view->disableLayout=true;
        $view->contentTemplate="view/habilidadesForm.php";
        break;

    case 'deleteHabilidad':
        $habilidad = new Habilidad($_POST['id_habilidad']);
        $rta = $habilidad->deleteHabilidad();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
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
