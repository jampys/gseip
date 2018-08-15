<?php

include_once("model/puestosModel.php");
include_once("model/areasModel.php");
include_once("model/competenciasNivelesModel.php");
include_once("model/habilidad-puestoModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];


$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid': //ok
        $view->disableLayout=true;
        $view->puestos = Puesto::getPuestos();
        $view->contentTemplate="view/puestosGrid.php";
        break;

    case 'savePuesto': //ok
        $puesto = new Puesto($_POST['id_puesto']);
        $puesto->setNombre($_POST['nombre']);
        $puesto->setDescripcion($_POST['descripcion']);
        $puesto->setCodigo($_POST['codigo']);
        $puesto->setIdPuestoSuperior(($_POST['id_puesto_superior'])? $_POST['id_puesto_superior'] : null);
        $puesto->setIdArea($_POST['id_area']);
        $puesto->setIdNivelCompetencia($_POST['id_nivel_competencia']);

        $rta = $puesto->save();
        //print_r(json_encode($rta));
        print_r(json_encode(sQuery::dpLastInsertId()));
        exit;
        break;

    case 'newPuesto': //ok
        $view->puesto = new Puesto();
        $view->label='Nuevo Puesto de trabajo';

        $view->puesto_superior = Puesto::getPuestos();
        $view->areas = Area::getAreas();
        $view->nivelesCompetencias = CompetenciasNiveles::getNivelesCompetencias();

        $view->disableLayout=true;
        $view->contentTemplate="view/puestosForm.php";
        break;

    case 'editPuesto': //ok
        $view->label='Editar Puesto de trabajo';
        $view->puesto = new Puesto($_POST['id_puesto']);

        $view->puesto_superior = Puesto::getPuestos();
        $view->areas = Area::getAreas();
        $view->nivelesCompetencias = CompetenciasNiveles::getNivelesCompetencias();

        $view->disableLayout=true;
        $view->contentTemplate="view/puestosForm.php";
        break;

    case 'deletePuesto': //ok
        $puesto = new Puesto($_POST['id_puesto']);
        $rta = $puesto->deletePuesto();
        print_r(json_encode($rta));
        die;
        break;

    case 'autocompletarPuestos':
        $view->puesto = new Puesto();
        $rta=$view->puesto->autocompletarPuestos($_POST['term']);
        print_r(json_encode($rta));
        exit;
        break;


    case 'loadDetalles': //abre la ventana modal para mostrar los detalles del puesto
        //$view->label='Detalles del puesto';
        $view->disableLayout=true;
        $view->puestos = new Puesto($_POST['id_puesto']);
        $view->label= $view->puestos->getNombre();

        $view->empleados = Puesto::getEmpleadosByPuesto($_POST['id_puesto']);
        $view->habilidades = HabilidadPuesto::getHabilidadPuesto($_POST['id_puesto'], null);
        $view->contentTemplate="view/puestosFormDetalles.php";
        break;

    default : //ok
        if ( PrivilegedUser::dhasPrivilege('PUE_VER', array(1)) ) {
            $view->puestos = Puesto::getPuestos();
            $view->contentTemplate="view/puestosGrid.php";
        }else{
            $_SESSION['error'] = PrivilegedUser::dgetErrorMessage('PRIVILEGE', 'PUE_VER');
            header("Location: index.php?action=error");
            exit;
        }
        break;
}


if ($view->disableLayout==true) { //ok
    include_once ($view->contentTemplate);}
else {
    include_once('view/puestosLayout.php');
}


?>
