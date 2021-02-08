<?php

include_once("model/empleadosModel.php");
include_once("model/nov_eventosLiquidacionModel.php");
include_once("model/nov_sucesosModel.php");
include_once("model/contratosModel.php");
include_once("model/nov_periodosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        $id_empleado = ($_POST['id_empleado']!='')? $_POST['id_empleado'] : null;
        $eventos = ($_POST['eventos']!='')? implode(",", $_POST['eventos'])  : 'su.id_evento';
        $fecha_desde = ($_POST['search_fecha_desde']!='')? $_POST['search_fecha_desde'] : null;
        $fecha_hasta = ($_POST['search_fecha_hasta']!='')? $_POST['search_fecha_hasta'] : null;
        $id_contrato = ($_POST['search_contrato']!='')? $_POST['search_contrato'] : null;
        $view->sucesos = Suceso::getSucesos($id_empleado, $eventos, $fecha_desde, $fecha_hasta, $id_contrato);
        $view->contentTemplate="view/sucesos/sucesosGrid.php";
        break;

    case 'saveSuceso':

        $suceso = new Suceso($_POST['id_suceso']);
        $suceso->setIdEvento($_POST['id_evento']);
        $suceso->setIdEmpleado($_POST['id_empleado']);
        $suceso->setFechaDesde($_POST['fecha_desde']);
        $suceso->setFechaHasta($_POST['fecha_hasta']);
        $suceso->setObservaciones($_POST['observaciones']);
        $suceso->setCreatedBy($_SESSION['id_user']);
        $suceso->setIdPeriodo1($_POST['id_periodo1']);
        $suceso->setCantidad1($_POST['cantidad1']);
        $suceso->setIdPeriodo2( ($_POST['id_periodo2']!='')? $_POST['id_periodo2'] : null );
        $suceso->setCantidad2( ($_POST['cantidad2']!='')? $_POST['cantidad2'] : null );
        $suceso->setFd1( ($_POST['fd1'])? $_POST['fd1'] : null  );
        $suceso->setFh1( ($_POST['fh1'])? $_POST['fh1'] : null );
        $suceso->setFd2( ($_POST['fd2'])? $_POST['fd2'] : null );
        $suceso->setFh2( ($_POST['fh2'])? $_POST['fh2'] : null );
        $rta = $suceso->save();
        print_r(json_encode(sQuery::dpLastInsertId()));
        //print_r(json_encode($rta));
        exit;
        break;

    case 'newSuceso': //ok
        $view->label='Nuevo Suceso programado';
        $view->suceso = new Suceso($_POST['id_suceso']);

        $view->empleados = Empleado::getEmpleadosControl(null);
        $view->eventos = EventosLiquidacion::getEventosLiquidacion();
        //$view->empleado = $view->renovacion->getEmpleado()->getApellido()." ".$view->renovacion->getEmpleado()->getNombre();

        $view->disableLayout=true;
        $view->contentTemplate="view/sucesos/sucesosPForm.php";
        break;

    case 'editSuceso':
        $view->suceso = new Suceso($_POST['id_suceso']);
        $view->label = ($_POST['target']!='view')? 'Editar suceso' : 'Ver suceso';

        $view->empleados = Empleado::getEmpleadosControl(null);
        $view->eventos = EventosLiquidacion::getEventosLiquidacion();
        // Trae todos los periodos, luego en el formulario quedan habilitados solo los activos
        $view->periodos = NovPeriodo::getPeriodos1($view->suceso->getIdEmpleado()); ;

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/sucesos/sucesosForm.php";
        break;


    case 'deleteSuceso': //ok
        /*$suceso = new Suceso($_POST['id_suceso']);
        $rta = $suceso->deleteSuceso();
        print_r(json_encode($rta));
        die;
        break;*/

        try{
            sQuery::dpBeginTransaction();
            $suceso = new Suceso($_POST['id_suceso']);
            $uploads = Suceso::uploadsLoad($_POST['id_suceso']);
            $suceso->deleteSuceso();
            foreach($uploads as $up){
                if (!file_exists($up['directory'].$up['name'])) throw new Exception('Archivo no existe.');
            }

            sQuery::dpCommit();
            foreach($uploads as $up){
                unlink($up['directory'].$up['name']);
            }
            print_r(json_encode(1));

        }catch (Exception $e){
            sQuery::dpRollback();
            throw new Exception('Error en el query.'); //para que entre en el .fail de la peticion ajax
        }


        die;
        break;


    case 'checkRango':
        $view->suceso = new Suceso();
        $rta = $view->suceso->checkRango($_POST['fecha_desde'], $_POST['fecha_hasta'], $_POST['id_empleado'], $_POST['id_evento'], $_POST['id_suceso']);
        print_r(json_encode($rta));
        exit;
        break;

    case 'loadExport':  //abre ventana modal para exportar
        $view->disableLayout=true;
        $view->label = 'Exportar sucesos';
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos
        $view->periodos_sup = NovPeriodo::getPeriodosSup(); //carga el combo de periodos superiores

        $view->contentTemplate="view/sucesos/exportForm.php";
        break;


    default :
        $view->empleados = Empleado::getEmpleadosControl(null); //carga el combo para filtrar empleados
        $view->eventos = EventosLiquidacion::getEventosLiquidacion(); //carga el combo para filtrar eventos liquidacion
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/sucesos/sucesosGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/sucesos/sucesosLayout.php');
}


?>
