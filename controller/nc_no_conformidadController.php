<?php

include_once("model/vto_renovacionesPersonalModel.php");
include_once("model/vto_vencimientosPersonalModel.php");
include_once("model/contratosModel.php");
include_once("model/subcontratistasModel.php");
include_once("model/nc_no_conformidadModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        $id_empleado = ($_POST['id_empleado']!='')? $_POST['id_empleado'] : null;
        $id_grupo = ($_POST['id_grupo']!='')? $_POST['id_grupo'] : null;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? $_POST['id_vencimiento'] : null;
        $id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrp.id_vencimiento';
        $id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        $id_subcontratista = ($_POST['id_subcontratista']!='')? $_POST['id_subcontratista'] : null;
        $renovado = ($_POST['renovado']== 0)? null : 1;
        $view->renovaciones_personal = NoConformidad::getNoConformidades($id_empleado, $id_grupo, $id_vencimiento, $id_contrato, $id_subcontratista, $renovado);
        $view->contentTemplate="view/no_conformidad/no_conformidadGrid.php";
        break;

    case 'saveNoConformidad': //ok

        $no_conformidad = new NoConformidad($_POST['id_no_conformidad']);
        $no_conformidad->setNombre($_POST['nombre']);
        $no_conformidad->setDescripcion($_POST['descripcion']);
        $no_conformidad->setTipo($_POST['tipo']);
        $no_conformidad->setAnalisisCausa($_POST['analisis_causa']);
        $no_conformidad->setAnalisisCausaDesc( ($_POST['analisis_causa_desc']!='')? $_POST['analisis_causa_desc'] : null);
        $no_conformidad->setTipoAccion($_POST['tipo_accion']);
        $no_conformidad->setAccionInmediata( ($_POST['accion']!='')? $_POST['accion'] : null);
        $no_conformidad->setIdResponsableSeguimiento($_POST['id_responsable_seguimiento']);
        $no_conformidad->setFechaCierre( ($_POST['fecha_cierre']!='')? $_POST['fecha_cierre'] : null);

        $rta = $no_conformidad->save();
        //print_r(json_encode(sQuery::dpLastInsertId()));
        print_r(json_encode($rta));
        exit;
        break;

    case 'newNoConformidad': //ok
        $view->label='Nueva No conformidad';
        $view->no_conformidad = new NoConformidad();

        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal();
        $view->tipos = Soporte::get_enum_values('nc_no_conformidad', 'tipo');
        $view->analisis_causa = Soporte::get_enum_values('nc_no_conformidad', 'analisis_causa');
        $view->tipo_accion = Soporte::get_enum_values('nc_no_conformidad', 'tipo_accion');
        $view->empleados = (!$_POST['id_empleado'])? Empleado::getEmpleadosActivos(null) : Empleado::getEmpleados(); //carga el combo de empleados

        $view->disableLayout=true;
        $view->contentTemplate="view/no_conformidad/no_conformidadForm.php";
        break;

    case 'editNoConformidad': //ok
        $view->label = ($_POST['target'] == 'view')? 'Ver No conformidad':'Editar No conformidad';
        $view->no_conformidad = new NoConformidad($_POST['id_no_conformidad']);

        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal();
        $view->tipos = Soporte::get_enum_values('nc_no_conformidad', 'tipo');
        $view->analisis_causa = Soporte::get_enum_values('nc_no_conformidad', 'analisis_causa');
        $view->tipo_accion = Soporte::get_enum_values('nc_no_conformidad', 'tipo_accion');
        $view->empleados = (!$_POST['id_empleado'])? Empleado::getEmpleadosActivos(null) : Empleado::getEmpleados(); //carga el combo de empleados

        $view->disableLayout=true;
        $view->target = $_POST['target'];
        $view->contentTemplate="view/no_conformidad/no_conformidadForm.php";
        break;


    case 'deleteNoConformidad': //ok
        $no_conformidad = new NoConformidad($_POST['id_no_conformidad']);
        $rta = $no_conformidad->deleteNoConformidad();
        print_r(json_encode($rta));
        die;
        break;

    default :
        $view->empleados = Empleado::getEmpleadosActivos(null);
        $view->vencimientos = VencimientoPersonal::getVencimientosPersonal(); //carga el combo para filtrar vencimientos
        $view->contratos = Contrato::getContratosControl(); //carga el combo para filtrar contratos
        $view->subcontratistas = Subcontratista::getSubcontratistas(); //carga el combo para filtrar subcontratistas
        $view->contentTemplate="view/no_conformidad/no_conformidadGrid.php";
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);
}
else {
    include_once('view/no_conformidad/no_conformidadLayout.php');
}


?>
