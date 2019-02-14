<?php
include_once("model/nov_partesModel.php");
include_once("model/nov_parte-empleadoModel.php");
include_once("model/nov_parte-ordenModel.php");
include_once("model/nov_parte-empleado-conceptoModel.php");

include_once("model/nov_areasModel.php");
include_once("model/contratosModel.php");

include_once("model/cuadrillasModel.php");
include_once("model/vehiculosModel.php");
include_once("model/nov_eventosCuadrillaModel.php");
include_once("model/empleadosModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrp.id_vencimiento';
        $fecha_desde = ($_POST['search_fecha_desde']!='')? $_POST['search_fecha_desde'] : null;
        $fecha_hasta = ($_POST['search_fecha_hasta']!='')? $_POST['search_fecha_hasta'] : null;
        $id_contrato = ($_POST['search_contrato']!='')? $_POST['search_contrato'] : null;
        $todas = null; //($_POST['renovado']== 0)? null : 1;
        $view->partes = Parte::getPartes($fecha_desde, $fecha_hasta, $id_contrato, $todas);
        $view->contentTemplate="view/novedades_partes/partesGrid.php";
        break;

    case 'insertPartes': //guarda de manera masiva los partes seleccionados //ok

        try{
            sQuery::dpBeginTransaction();

            $vCuadrillas = json_decode($_POST["vCuadrillas"], true);

            foreach ($vCuadrillas as $vC) {

                //echo "id_cuadrilla: ".$vC['id_cuadrilla']." - fecha: ".$_POST["fecha_parte"];

                $p = new Parte();
                $p->setFechaParte($_POST["fecha_parte"]);
                $p->setCuadrilla(($vC['cuadrilla'] != '')? $vC['cuadrilla'] : null);
                $p->setIdArea(($vC['id_area'] != '')? $vC['id_area'] : null);
                $p->setIdVehiculo(($vC['id_vehiculo'] != '')? $vC['id_vehiculo'] : null);
                //$p->setIdEvento($vC['id_evento']);
                $p->setIdEvento(($vC['id_evento']!='')? $vC['id_evento'] : null);
                $p->setIdContrato($vC['id_contrato']);
                $p->setCreatedBy($_SESSION['id_user']);
                $p->insertParte();  //si falla sale por el catch

                //tomo el ultimo id insertado, para insertar luego los empleados del parte
                $id_parte = sQuery::dpLastInsertId();

                //se insertan los empleados de la cuadrilla

                if($vC['id_empleado_1']){ //conductores
                    foreach($vC['id_empleado_1'] as $co){
                        $pe1 = new ParteEmpleado();
                        $pe1->setIdParte($id_parte);
                        //$pe1->setIdEmpleado($vC['id_empleado_1']);
                        $pe1->setIdEmpleado($co);
                        $pe1->setConductor(1);
                        $pe1->setCreatedBy($_SESSION['id_user']);
                        $pe1->insertParteEmpleado();
                    }
                }

                if($vC['id_empleado_2']){ //acompañantes
                    foreach($vC['id_empleado_2'] as $ac){
                        $pe2 = new ParteEmpleado();
                        $pe2->setIdParte($id_parte);
                        $pe2->setIdEmpleado($ac);
                        $pe2->setConductor(0);
                        $pe2->setCreatedBy($_SESSION['id_user']);
                        $pe2->insertParteEmpleado();
                    }
                }

            }

            //exit;

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

    case 'calcularParte': //ok  //guarda un parte despues de ser editado (boton calcular)
        $parte = new Parte($_POST['id_parte']);
        $parte->setIdArea($_POST['id_area']);
        $parte->setIdVehiculo($_POST['id_vehiculo']);
        $parte->setIdEvento( ($_POST['id_evento']!='')? $_POST['id_evento'] : null );
        //$busqueda->setDisabled ( ($_POST['disabled'] == 1)? date('d/m/Y') : null);
        $parte->setHsNormal( ($_POST['hs_normal']!='')? $_POST['hs_normal'] : 0);
        $parte->setHs50( ($_POST['hs_50']!='')? $_POST['hs_50'] : 0);
        $parte->setHs100( ($_POST['hs_100']!='')? $_POST['hs_100'] : 0);
        $parte->setCreatedBy($_SESSION['id_user']);

        $rta = $parte->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        //print_r(json_encode($rta));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newParte': //ok
        $view->label='Nuevo parte: '.$_POST['fecha_parte'].' '.$_POST['contrato'];
        $view->parte = new Parte();

        $view->empleados = Empleado::getEmpleados();
        $view->areas = NovArea::getAreas();
        $view->vehiculos = Vehiculo::getVehiculos();
        $view->eventos = EventosCuadrilla::getEventosCuadrilla();
        $view->cuadrillas = Cuadrilla::getCuadrillasForPartes($_POST['add_contrato'], $_POST['fecha_parte']);

        $view->disableLayout=true;
        $view->contentTemplate="view/novedades_partes/partesFormInsert.php";
        break;

    case 'editParte': //ok
        $view->parte = new Parte($_POST['id_parte']);
        $view->label='Editar parte: '.$view->parte->getFechaParte().' '; //falta el nombre del contrato

        $view->empleados = Empleado::getEmpleados();
        $view->areas = NovArea::getAreas();
        $view->vehiculos = Vehiculo::getVehiculos();
        $view->eventos = EventosCuadrilla::getEventosCuadrilla();
        //$view->cuadrillas = Cuadrilla::getCuadrillasForPartes($_POST['add_contrato'], $_POST['fecha_parte']);

        $view->empleados = ParteEmpleado::getParteEmpleado($_POST['id_parte']);
        $view->ordenes = ParteOrden::getParteOrden($_POST['id_parte']);
        $view->conceptos = ParteEmpleadoConcepto::getParteEmpleadoConcepto($_POST['id_parte']);

        $view->disableLayout=true;
        $view->contentTemplate="view/novedades_partes/partesFormUpdate.php";
        break;

    case 'loadExportTxt': //ok  //abre ventana modal para exportar
        $view->disableLayout=true;
        $view->label = 'Exportar a txt';
        $view->contratos = Contrato::getContratos(); //carga el combo para filtrar contratos

        $view->contentTemplate="view/novedades_partes/export_txtForm.php";
        break;

    case 'checkExportTxt': //ok //chequea que no existan partes sin calcular
        //$parte = new Parte($_POST['id_parte']);
        //$rta = $parte->save();
        $rta = Parte::checkExportTxt($_POST['id_contrato'], $_POST['fecha_desde'], $_POST['fecha_hasta']);
        //print_r(json_encode(sQuery::dpLastInsertId()));
        //print_r(json_encode($rta));
        print_r(json_encode($rta));
        exit;
        break;

    case 'exportTxt': //exportacion propiamente dicha

        $id_contrato = ($_GET['id_contrato']!='')? $_GET['id_contrato'] : null;
        $fecha_desde = ($_GET['fecha_desde']!='')? $_GET['fecha_desde'] : null;
        $fecha_hasta = ($_GET['fecha_hasta']!='')? $_GET['fecha_hasta'] : null;

        $filepath = "uploads/files/file.txt";
        $handle = fopen($filepath, "w");
        $view->sucesos = Parte::exportTxt($id_contrato, $fecha_desde, $fecha_hasta);

        foreach ($view->sucesos as $su) {
            //$fd = new DateTime($su['txt_fecha_desde']);
            //$fh = new DateTime($su['txt_fecha_hasta']);
            //$d = (string)$fh->diff($fd)->days;

            fwrite($handle, str_pad(substr($su['legajo'], 2), 10). //legajo
                //str_pad($fd->format('01/m/Y'), 10). //periodo desde
                //str_pad($fh->format('01/m/Y'), 10). //periodo hasta
                //str_pad($fd->format('d/m/Y'), 10). //fecha desde
                //str_pad($fh->format('d/m/Y'), 10). //fecha hasta
                //str_pad($d, 10). //dias
                //str_pad($d, 10). //prorrateo dias
                str_pad("L", 10). //tipo liquidacion
                str_pad("MEN", 10). //tipo liquidacion
                str_pad("01/01/1970", 10). //fecha prevista notificacion
                str_pad("01/01/1970", 10). //fecha notificacion
                str_pad(substr($su['observaciones'], 0, 10), 10). //observaciones


                "\r\n");
        }

        fclose($handle);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($filepath));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath); //descarga el archivo

        unlink ($filepath); //borra el archivo una vez descargado

        exit;
        break;

    case 'deleteHabilidad':
        $habilidad = new Habilidad($_POST['id_habilidad']);
        $rta = $habilidad->deleteHabilidad();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    default : //ok
        $view->areas = NovArea::getAreas(); //carga el combo para filtrar Areas
        $view->contratos = Contrato::getContratos(); //carga el combo para filtrar contratos
        $view->contentTemplate="view/novedades_partes/partesGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/novedades_partes/partesLayout.php');
}


?>
