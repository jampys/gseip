<?php
include_once("model/nov_partesModel.php");
include_once("model/nov_parte-empleadoModel.php");
include_once("model/nov_parte-ordenModel.php");
include_once("model/nov_parte-empleado-conceptoModel.php");

include_once("model/nov_areasModel.php");
include_once("model/contratosModel.php");

include_once("model/cuadrillasModel.php");
include_once("model/cuadrilla-empleadoModel.php");
//include_once("model/vehiculosModel.php");
include_once("model/nov_eventosCuadrillaModel.php");
include_once("model/empleadosModel.php");
include_once("model/nov_periodosModel.php");
include_once("model/nov_sucesosModel.php");
include_once("model/nov_concepto-convenio-contratoModel.php");
include_once("model/nov_rutasModel.php");
include_once("model/nov_ruta-conceptoModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{


    case 'saveParte': //ok

        $rta = array();

        try{

            sQuery::dpBeginTransaction();

            $parte = new Parte($_POST['id_parte']);
            $parte->setFechaParte($_POST['fecha_parte']);
            $parte->setIdContrato($_POST['id_contrato']);
            $parte->setIdArea( ($_POST['id_area'])? $_POST['id_area'] : null );
            $parte->setIdVehiculo(null);
            $parte->setIdCuadrilla( ($_POST['id_cuadrilla'])? $_POST['id_cuadrilla'] : null );
            $parte->setIdPeriodo($_POST['id_periodo']);
            //$parte->setComentarios($_POST['comentarios']);
            $parte->setCreatedBy($_SESSION['id_user']);

            $id_parte_empleado = $_POST['id_parte_empleado'];
            $id_empleado = $_POST['id_empleado'];
            $id_evento = ($_POST['id_evento'])? $_POST['id_evento'] : null;
            $conductor = $_POST['conductor'];
            $comentario = $_POST['comentario'];
            $rta = $parte->updateParte2($id_parte_empleado, $id_empleado, $id_evento, $conductor, $comentario);

            //obtengo el id_parte y id_parte_empleado devueltos por el SP
            $id_parte = $rta[0]['id_parte'];
            $id_parte_empleado = $rta[0]['id_parte_empleado'];
            //print_r($rta);

            $vConceptos = json_decode($_POST["vConceptos"], true);
            //print_r($vConceptos);
            //throw new Exception();


            foreach ($vConceptos as $vC) {

                //$c = new HabilidadEmpleado();
                //$c->setIdHabilidad($vH['id_habilidad']);
                //$c->setIdEmpleado($vE['id_empleado']);
                //if($c->insertHabilidadEmpleado() < 0) $flag = -1;  //si falla algun insert $flag = -1

                //echo "id_contrato :".$id." - id_empleado: ".$vE['id_empleado'];
                //echo "id_contrato :".$id." - procesos: ".$vE['id_proceso'];
                $c = new ParteEmpleadoConcepto();
                $c->setIdParteEmpleadoConcepto($vC['id_parte_empleado_concepto']);
                $c->setIdParteEmpleado($id_parte_empleado);
                $c->setIdConceptoConvenioContrato($vC['id_concepto_convenio_contrato']);
                $c->setCantidad($vC['cantidad']);
                $c->setCreatedBy($_SESSION['id_user']);
                //$c->setCreatedDate();
                $c->setTipoCalculo($vC['tipo_calculo']);
                $c->setMotivo(null);
                //$c->setFechaHasta(($vE['fecha_hasta'])? $vE['fecha_hasta']: null); //fecha_desde puede ser null
                //$c->setIdLocalidad(($vE['id_localidad'])? $vE['id_localidad']: null);

                //echo 'id empleado contrato: '.$vE['id_empleado_contrato'].'---';

                //echo 'AAAAAAAAAAAAAAAAAAAA: '.$vC['operacion'];
                if($vC['operacion']=='insert') {
                    $c->insertParteEmpleadoConcepto();
                    //$id_empleado_contrato = sQuery::dpLastInsertId();
                }
                else if( $vC['operacion']=='update') {
                    $c->updateParteEmpleadoConcepto();
                    //$id_empleado_contrato = $empleado_contrato->getIdEmpleadoContrato();
                }
                else if( $vC['operacion']=='delete') {
                    //Elimina en cascada los registros hijos de la tabla empleado_contrato_proceso
                    $c->deleteParteEmpleadoConcepto(); //si falla sale por el catch
                }


            }

            //Devuelve el resultado a la vista
            sQuery::dpCommit();
            print_r(json_encode($rta));
            //print_r(json_encode(1));

        }
        catch(Exception $e){
            //echo $e->getMessage(); //habilitar para ver el mensaje de error
            sQuery::dpRollback();
           //print_r(json_encode(-1));
            print_r(json_encode($rta));
        }

        exit;
        break;



    case 'saveParteR':

        $rta = array();

        try {

            sQuery::dpBeginTransaction();

            //throw new Exception();
            $startDate = DateTime::createFromFormat('d/m/Y', $_POST['fecha_parte']);
            $endDate = DateTime::createFromFormat('d/m/Y', '19/02/2020');

            while ($startDate <= $endDate) {

                //chequear que ya no exista una novedad para esa fecha y empleado
                //chequear si solo se inserte de lunes a viernes

                $parte = new Parte($_POST['id_parte']);
                //$parte->setFechaParte($_POST['fecha_parte']);
                $parte->setFechaParte($startDate->format('d/m/Y'));
                $parte->setIdContrato($_POST['id_contrato']);
                $parte->setIdArea(($_POST['id_area']) ? $_POST['id_area'] : null);
                $parte->setIdVehiculo(null);
                $parte->setIdCuadrilla(($_POST['id_cuadrilla']) ? $_POST['id_cuadrilla'] : null);
                $parte->setIdPeriodo($_POST['id_periodo']);
                $parte->setCreatedBy($_SESSION['id_user']);

                $id_parte_empleado = $_POST['id_parte_empleado'];
                $id_empleado = $_POST['id_empleado'];
                $id_evento = ($_POST['id_evento']) ? $_POST['id_evento'] : null;
                $conductor = $_POST['conductor'];
                $comentario = $_POST['comentario'];
                $rta = $parte->updateParte2($id_parte_empleado, $id_empleado, $id_evento, $conductor, $comentario);

                //obtengo el id_parte y id_parte_empleado devueltos por el SP
                $id_parte = $rta[0]['id_parte'];
                $id_parte_empleado = $rta[0]['id_parte_empleado'];
                //print_r($rta);


                $vConceptos = json_decode($_POST["vConceptos"], true);
                foreach ($vConceptos as $vC) {
                    $c = new ParteEmpleadoConcepto();
                    $c->setIdParteEmpleadoConcepto($vC['id_parte_empleado_concepto']);
                    $c->setIdParteEmpleado($id_parte_empleado);
                    $c->setIdConceptoConvenioContrato($vC['id_concepto_convenio_contrato']);
                    $c->setCantidad($vC['cantidad']);
                    $c->setCreatedBy($_SESSION['id_user']);
                    $c->setTipoCalculo($vC['tipo_calculo']);
                    $c->setMotivo(null);

                    if ($vC['operacion'] == 'insert') {$c->insertParteEmpleadoConcepto();}
                    else if ($vC['operacion'] == 'update') {$c->updateParteEmpleadoConcepto();}
                    else if ($vC['operacion'] == 'delete') {$c->deleteParteEmpleadoConcepto();}
                }

                $startDate->modify('+1 day');
            }  //while

            //Devuelve el resultado a la vista
            sQuery::dpCommit();
            print_r(json_encode(1));

        } //try
        catch(Exception $e){
            echo $e->getMessage(); //habilitar para ver el mensaje de error
            sQuery::dpRollback();
            //print_r(json_encode(-1));
        }

        exit;
        break;






    case 'newParte': //ok //carga novedades por persona
        //$view->parte = new Parte();
        $view->empleados = Empleado::getEmpleadosActivos($_POST['add_contrato']);
        $view->periodo = New NovPeriodo($_POST['id_periodo']);
        $view->contrato = New Contrato($_POST['add_contrato']);
        $view->label='<i class="fas fa-male fa-lg"></i> '.$view->contrato->getNombre().' '.$view->periodo->getNombre().' ('.$view->periodo->getFechaDesde()." - ".$view->periodo->getFechaHasta().")";
        //$view->vehiculos = Vehiculo::getVehiculos();
        //$view->eventos = EventosCuadrilla::getEventosCuadrilla();
        //$view->cuadrillas = Cuadrilla::getCuadrillasForPartes($_POST['add_contrato'], $_POST['fecha_parte']);
        //$view->params = array('fecha_parte' => $_POST['fecha_parte'], 'id_periodo' => $_POST['id_periodo']);

        //$view->disableLayout=true;
        $view->contentTemplate="view/novedades2/2empleadoForm.php";
        break;


    case 'newParte1': //ok  //carga novedades por cuadrilla
        //$view->parte = new Parte();
        $view->empleados = Empleado::getEmpleadosActivos($_POST['add_contrato']);
        $view->periodo = New NovPeriodo($_POST['id_periodo']);
        $view->contrato = New Contrato($_POST['add_contrato']);
        $view->label='<i class="fas fa-car fa-lg"></i> '.$view->contrato->getNombre().' '.$view->periodo->getNombre().' ('.$view->periodo->getFechaDesde()." - ".$view->periodo->getFechaHasta().")";
        //$view->vehiculos = Vehiculo::getVehiculos();
        //$view->eventos = EventosCuadrilla::getEventosCuadrilla();
        //$view->cuadrillas = Cuadrilla::getCuadrillasForPartes($_POST['add_contrato'], $_POST['fecha_parte']);
        //$view->params = array('fecha_parte' => $_POST['fecha_parte'], 'id_periodo' => $_POST['id_periodo']);

        //$view->disableLayout=true;
        $view->contentTemplate="view/novedades2/2empleadoForm1.php";
        break;


    case 'tableEmpleados': //ok
        //$view->label='Nuevo parte: '.$_POST['fecha_parte'].' '.$_POST['contrato'];
        //$view->parte = new Parte();

        $view->empleados = Parte::getEmpleados($_POST['fecha'], $_POST['id_contrato']);


        $view->disableLayout=true;
        $view->contentTemplate="view/novedades2/empleadosGrid.php";
        break;




    case 'editParte': //ok
        $view->empleado = New Empleado($_POST['id_empleado']);
        $view->label = $view->empleado->getLegajo().' '.$view->empleado->getApellido()." ".$view->empleado->getNombre();
        $view->label.= "&nbsp";
        $view->label.= ($_POST['id_parte'])? "<span class='dp_blue'>[Parte Nro. ".$_POST['id_parte']."]</span>": "<span class='dp_yellow'>[sin parte]</span>";

        $view->cuadrillas = Cuadrilla::getCuadrillas($_POST['id_contrato'], null);
        $view->eventos = EventosCuadrilla::getEventosCuadrilla();
        $view->ordenes = ParteOrden::getParteOrden($_POST['id_parte']); //2104
        $view->periodo = New NovPeriodo($_POST['id_periodo']);
        $view->parte_empleado = new ParteEmpleado($_POST['id_parte_empleado']);
        $view->parte = new Parte($_POST['id_parte']);
        $id_convenio = ($view->empleado->getIdConvenio())? $view->empleado->getIdConvenio() : -1;
        $view->conceptos = ConceptoConvenioContrato::getConceptoConvenioContrato($_POST['id_contrato'], $id_convenio);
        $view->rutas = Ruta::getRutas($_POST['id_contrato'], $id_convenio);
        $view->areas = NovArea::getAreas($_POST['id_contrato']);
        $view->defaults = CuadrillaEmpleado::getEmpleadoDefaults($_POST['id_empleado']);
        //$view->conceptos = ParteEmpleadoConcepto::getParteEmpleadoConcepto2($_POST['id_parte_empleado']);

        $eventos = ($_POST['eventos']!='')? implode(",", $_POST['eventos'])  : 'su.id_evento';
        $fecha_desde = $view->periodo->getFechaDesde(); //($_POST['fecha']!='')? $_POST['fecha'] : null;
        $fecha_hasta = $view->periodo->getFechaHasta(); //($_POST['fecha']!='')? $_POST['fecha'] : null;
        $id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        $view->sucesos = Suceso::getSucesos($_POST['id_empleado'], $eventos, $fecha_desde, $fecha_hasta, $id_contrato);
        //$view->params = array('fecha_parte' => $_POST['fecha_parte'], 'id_periodo' => $_POST['id_periodo']);
        $view->params = array('id_parte_empleado' => $_POST['id_parte_empleado'], 'id_contrato' => $_POST['id_contrato']);

        $view->disableLayout=true;
        $view->contentTemplate="view/novedades2/3conceptoForm.php";
        break;


    case 'loadConceptos': //ok
        //$view->contratoEmpleado = ContratoEmpleado::getContratoEmpleado($_POST['id_contrato']);
        $view->conceptos = ParteEmpleadoConcepto::getParteEmpleadoConcepto2($_POST['id_parte_empleado']);
        print_r(json_encode($view->conceptos));
        exit;
        break;


    case 'loadConceptosRutas': //ok
        //$view->contratoEmpleado = ContratoEmpleado::getContratoEmpleado($_POST['id_contrato']);
        $view->conceptos = RutaConcepto::getConceptos($_POST['id_ruta']);
        print_r(json_encode($view->conceptos));
        exit;
        break;

    case 'loadDiaAnterior': //ok
        $view->parte = Parte::getParteAnterior($_POST['id_empleado'], $_POST['fecha_parte'], $_POST['id_contrato']);
        $view->conceptos = ParteEmpleadoConcepto::getParteEmpleadoConcepto2($view->parte[0]['id_parte_empleado']);
        //print_r(json_encode($view->conceptos));
        print_r(json_encode(array('parte'=>$view->parte, 'conceptos'=>$view->conceptos)));
        exit;
        break;


    case 'sucesosRefreshGrid': //ok
        $view->disableLayout=true;
        //$id_empleado = ($_POST['id_empleado']!='')? $_POST['id_empleado'] : null;
        //$eventos = ($_POST['eventos']!='')? implode(",", $_POST['eventos'])  : 'su.id_evento';
        //$fecha_desde = ($_POST['search_fecha_desde']!='')? $_POST['search_fecha_desde'] : null;
        //$fecha_hasta = ($_POST['search_fecha_hasta']!='')? $_POST['search_fecha_hasta'] : null;
        //$id_contrato = ($_POST['search_contrato']!='')? $_POST['search_contrato'] : null;
        $view->periodo = New NovPeriodo($_POST['id_periodo']);
        $id_empleado = $_POST['id_empleado'];
        $eventos = ($_POST['eventos']!='')? implode(",", $_POST['eventos'])  : 'su.id_evento';
        $fecha_desde = $view->periodo->getFechaDesde(); //($_POST['fecha']!='')? $_POST['fecha'] : null;
        $fecha_hasta = $view->periodo->getFechaHasta(); //($_POST['fecha']!='')? $_POST['fecha'] : null;
        $id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        $view->sucesos = Suceso::getSucesos($id_empleado, $eventos, $fecha_desde, $fecha_hasta, $id_contrato);
        $view->contentTemplate="view/novedades2/sucesosGrid.php";
        break;



    default : //ok
        //$view->areas = NovArea::getAreas(); //carga el combo para filtrar Areas
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/novedades2/1contratoForm.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/novedades2/novedadesLayout.php');
}


?>
