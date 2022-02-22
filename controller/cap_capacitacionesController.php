<?php
include_once("model/obj_objetivosModel.php");
include_once("model/obj_tareasModel.php");
include_once("model/obj_avancesModel.php");
include_once("model/evaluacionesModel.php");
include_once("model/puestosModel.php");
include_once("model/areasModel.php");
include_once("model/contratosModel.php");

include_once("model/cap_capacitacionesModel.php");
include_once("model/cap_categoriasModel.php");
include_once("model/cap_modalidadesModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        //$view->disableLayout=true;
        $periodo = ($_POST['periodo']!='')? $_POST['periodo'] : null;
        $id_categoria = ($_POST['id_categoria']!='')? $_POST['id_categoria'] : null;
        $mes_programada = ($_POST['mes_programada']!='')? $_POST['mes_programada'] : null;
        $id_contrato = ($_POST['id_contrato']!='')? implode(",", $_POST['id_contrato'])  : 'ce.id_contrato';

        $rta = $view->capacitaciones = Capacitacion::getCapacitaciones($periodo, $id_categoria, $mes_programada, $id_contrato);
        //$view->contentTemplate="view/objetivos/objetivosGrid.php";
        //break;
        print_r(json_encode($rta));
        exit;
        break;

    case 'saveCapacitacion': //ok
        $capacitacion = new Capacitacion($_POST['id_capacitacion']);
        $capacitacion->setPeriodo($_POST['periodo']);
        $capacitacion->setIdPlanCapacitacion($_POST['id_plan_capacitacion']);
        $capacitacion->setIdCategoria($_POST['id_categoria']);
        $capacitacion->setTema($_POST['tema']);
        $capacitacion->setDescripcion($_POST['descripcion']);
        $capacitacion->setMesProgramada(($_POST['mes_programada'])? $_POST['mes_programada'] : null);
        $capacitacion->setObservaciones(($_POST['observaciones'])? $_POST['observaciones'] : null);
        $capacitacion->setIdUser($_SESSION['id_user']);

        $rta = $capacitacion->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newCapacitacion': //ok
        $view->label='Nueva capacitación';
        $view->capacitacion = new Capacitacion();

        $view->periodos = Capacitacion::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->categorias = Categoria::getCategorias();
        $view->modalidades = Modalidad::getModalidades();
        $view->meses = Soporte::get_enum_values('obj_avances', 'periodo');

        $view->disableLayout=true;
        $view->contentTemplate="view/capacitaciones/capacitacionesForm.php";
        break;

    case 'editCapacitacion': //ok
        $view->capacitacion = new Capacitacion($_POST['id_capacitacion']);

        if($_POST['target'] == 'edit' or $_POST['target'] == 'view' ) $view->label = $view->capacitacion->getTema();
        else if ($_POST['target'] == 'clone') {
            $view->label = '<h4><span class="label label-warning">CLONAR</span> '.$view->capacitacion->getTema().'</h4>';
            $view->capacitacion->setIdCapacitacion(null); //pone el id_capacitacion en null para al guardar insertar uno nuevo
            //if($_POST['cerrado']) $view->objetivo->setPeriodo(null);
        }

        $view->periodos = Capacitacion::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->categorias = Categoria::getCategorias();
        $view->modalidades = Modalidad::getModalidades();
        $view->meses = Soporte::get_enum_values('obj_avances', 'periodo');

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/capacitaciones/capacitacionesForm.php";
        break;


    case 'deleteCapacitacion': //ok
        $capacitacion = new Capacitacion($_POST['id_capacitacion']);
        $rta = $capacitacion->deleteCapacitacion();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;


    case 'pdf':

        /*$f = Pdf::getCertificadoCalibracion($_GET['id_calib']);
        $fila = $f[0];
        $f1 = Pdf::getCertificadoPpt($_GET['id_calib'], $_GET['Nro_Serie']);
        $fila1 = $f1[0];
        $f2 = Pdf::getCertificadoOT($_GET['id_calib'], $_GET['Nro_Serie']);
        $fila2 = $f2[0];
        $f3 = Pdf::getGrafico($_GET['id_calib']);
        $fila3 = $f3[0];*/

        //$id_contrato = ($_GET['id_contrato'])? $_GET['id_contrato'] : null;
        $id_contrato = ($_POST['id_contrato']!='')? implode(",", $_POST['id_contrato'])  : 'ce.id_contrato';
        $c = Capacitacion::getCapacitacionPdf($_GET['id_capacitacion'], $id_contrato);
        $cap = $c[0];
        $fila4 = array();
        /*$fila5 = Accion::getAcciones($_GET['id_no_conformidad']);
        $f6 = Verificacion::getVerificaciones($_GET['id_no_conformidad']);
        $fila6 = end($f6);
        $rs = new Empleado($nc->getIdResponsableSeguimiento());*/

        include_once ('view/capacitaciones/generador.php');
        break;


    default : //ok //muestra la grilla de capacitaciones
        $view->periodos = Capacitacion::getPeriodos();
        $view->periodo_actual = Soporte::getPeriodoActual();
        $view->categorias = Categoria::getCategorias();
        //$view->contratos = Contrato::getContratos();
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos

        $view->contentTemplate="view/capacitaciones/capacitacionesGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/capacitaciones/capacitacionesLayout.php');
}


?>
