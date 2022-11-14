<?php
include_once("model/contratosModel.php");
include_once("model/cap_empleadosModel.php");
include_once("model/cap_edicionesModel.php");
include_once("model/contrato-empleadoModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrp.id_vencimiento';
        //$id_puesto = ($_POST['search_puesto']!='')? $_POST['search_puesto'] : null;
        //$id_localidad = ($_POST['search_localidad']!='')? $_POST['search_localidad'] : null;
        //$id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        //$todas = ($_POST['renovado']== 0)? null : 1;
        //$view->busquedas = Busqueda::getBusquedas($id_puesto, $id_localidad, $id_contrato, $todas);
        $id_edicion = ($_POST['id_edicion']!='')? $_POST['id_edicion'] : null;
        $id_contrato = ($_POST['id_contrato']!='')? implode(",", $_POST['id_contrato'])  : 'ce.id_contrato';
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $rta = $view->empleados = CapacitacionEmpleado::getEmpleados($_POST['id_capacitacion'], $id_edicion, $id_contrato, $startDate, $endDate);
        //$view->contentTemplate="view/no_conformidad/accionesGrid.php";
        //break;
        print_r(json_encode($rta));
        exit;
        break;

    case 'saveEmpleado': //ok

        try{
            sQuery::dpBeginTransaction();

            foreach($_POST['id_empleado'] as $e){
                //echo $p." ";
                $empleado = new CapacitacionEmpleado($_POST['id_capacitacion_empleado']);
                $empleado->setIdEmpleado($e);
                $empleado->setIdCapacitacion($_POST['id_capacitacion']);
                $empleado->setIdContrato(($_POST['id_contrato'])? $_POST['id_contrato'] : null);
                $empleado->setIdEdicion(($_POST['id_edicion'])? $_POST['id_edicion'] : null);
                $empleado->setObservaciones($_POST['id_responsable_ejecucion']);
                $empleado->setAsistio(($_POST['asistio'] == 1)? 1 : 0);
                $empleado->setIdUser($_SESSION['id_user']);
                $rta = $empleado->save();
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

    case 'newEmpleado': //ok
        $view->label='Agregar empleado';
        $view->empleado = new CapacitacionEmpleado($_POST['id_capacitacion_empleado']);

        $view->empleados = (!$_POST['id_empleado'])? Empleado::getEmpleadosActivos(null) : Empleado::getEmpleados(); //carga el combo de empleados
        //$view->contratos = ContratoEmpleado::getContratosByEmpleado($view->empleado->getIdEmpleado(), 1);
        $view->contratos = Contrato::getContratosControl();
        $view->ediciones = Edicion::getEdiciones($_POST['id_capacitacion'], $_POST['startDate'], $_POST['endDate']);

        $view->disableLayout=true;
        $view->contentTemplate="view/capacitaciones/empleado_detailForm.php";
        break;

    case 'editEmpleado': //ok
        $view->label = ($_POST['target']!='view')? 'Editar empleado': 'Ver empleado';
        $view->empleado = new CapacitacionEmpleado($_POST['id_capacitacion_empleado']);

        $view->empleados = (!$_POST['id_empleado'])? Empleado::getEmpleadosActivos(null) : Empleado::getEmpleados(); //carga el combo de empleados
        $view->contratos = ContratoEmpleado::getContratosByEmpleado($view->empleado->getIdEmpleado(), 1);
        $view->ediciones = Edicion::getEdiciones($_POST['id_capacitacion'], $_POST['startDate'], $_POST['endDate']);

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/capacitaciones/empleado_detailForm.php";
        break;

    case 'deleteEmpleado': //ok
        $view->empleado = new CapacitacionEmpleado($_POST['id_capacitacion_empleado']);
        $rta = $view->empleado->deleteCapacitacionEmpleado();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    case 'getEmpleados': //select dependiente //ok
        $id_contrato = ($_POST['id_contrato'])? $_POST['id_contrato'] : null;
        $rta = Empleado::getEmpleadosActivos($id_contrato);
        print_r(json_encode($rta));
        exit;
        break;

    default : //carga la tabla de empleados de la capacitacion //ok
        $view->label='Empleados de la capacitación';
        //$view->acciones = Accion::getAcciones($_POST['id_no_conformidad']);
        $view->disableLayout=true;
        $view->contentTemplate="view/capacitaciones/empleadosForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>
