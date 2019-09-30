<?php
include_once("model/busquedasModel.php");
include_once("model/postulacionesModel.php");
include_once("model/postulantesModel.php");
include_once("model/localidadesModel.php");
include_once("model/sel_especialidadesModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        //$id_vencimiento = ($_POST['id_vencimiento']!='')? implode(",", $_POST['id_vencimiento'])  : 'vrp.id_vencimiento';
        //$id_puesto = ($_POST['search_puesto']!='')? $_POST['search_puesto'] : null;
        //$id_localidad = ($_POST['search_localidad']!='')? $_POST['search_localidad'] : null;
        //$id_contrato = ($_POST['id_contrato']!='')? $_POST['id_contrato'] : null;
        //$todas = ($_POST['renovado']== 0)? null : 1;
        //$view->busquedas = Busqueda::getBusquedas($id_puesto, $id_localidad, $id_contrato, $todas);
        $view->postulaciones = Postulacion::getPostulaciones($_POST['id_busqueda'], null, null);
        $view->contentTemplate="view/busquedas/nPostulacionesGrid.php";
        break;

    case 'savePostulacion': //ok

        try{

            sQuery::dpBeginTransaction();

            if($_POST['id_postulacion']){ //postulacion existente. La edita

                $postulacion = new Postulacion($_POST['id_postulacion']);
                $postulacion->setIdBusqueda($_POST['id_busqueda']);
                $postulacion->setIdPostulante($_POST['id_postulante']);
                $postulacion->setOrigenCv($_POST['origen_cv']);
                $postulacion->setExpectativas($_POST['expectativas']);
                $postulacion->setPropuestaEconomica($_POST['propuesta_economica']);

                $rta = $postulacion->save();
                //print_r(json_encode(sQuery::dpLastInsertId()));
                sQuery::dpCommit();
                print_r(json_encode($rta));

            }elseif ($_POST['apellido'] && $_POST['nombre']){ //trae un nuevo postulante

                //inserta postulante
                $postulante = new Postulante($_POST['id_postulante']);
                $postulante->setApellido($_POST['apellido']);
                $postulante->setNombre($_POST['nombre']);
                $postulante->setDni($_POST['dni']);
                $postulante->setListaNegra( ($_POST['lista_negra'] == 1)? 1 : null);
                $postulante->setTelefono($_POST['telefono']);
                $postulante->setFormacion($_POST['formacion']);
                $postulante->setIdEspecialidad( ($_POST['id_especialidad']!='')? $_POST['id_especialidad'] : null);
                $postulante->setIdLocalidad( ($_POST['id_localidad']!='')? $_POST['id_localidad'] : null);
                $postulante->setComentarios( ($_POST['comentarios']!='')? $_POST['comentarios'] : null);
                $postulante->save();
                $id_postulante = sQuery::dpLastInsertId();

                //inserta postulacion
                $postulacion = new Postulacion($_POST['id_postulacion']);
                $postulacion->setIdBusqueda($_POST['id_busqueda']);
                $postulacion->setIdPostulante($id_postulante);
                $postulacion->setOrigenCv($_POST['origen_cv']);
                $postulacion->setExpectativas($_POST['expectativas']);
                $postulacion->setPropuestaEconomica($_POST['propuesta_economica']);
                $postulacion->save();
                print_r(json_encode(sQuery::dpLastInsertId()));


            }else{ //no trae datos
                throw new PDOException();
            }

        }catch (PDOException $e){

            //if($e->errorInfo[1] == 1062) $rta['duplicates']++;
            //else $rta['others']++;
            sQuery::dpRollback();
            print_r(json_encode(-1));

        }












        exit;
        break;

    case 'newPostulacion': //ok
        $view->label='Nueva postulación';
        $view->postulacion = new Postulacion($_POST['id_postulacion']);

        $view->postulantes = Postulante::getPostulantesActivos();
        $view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');

        $view->disableLayout=true;
        $view->contentTemplate="view/busquedas/nPostulacion_detailForm.php";
        break;


    case 'newPostulante': //ok
        //$view->label='Nuevo postulante';
        $view->postulante = new Postulante();

        $view->formaciones = Soporte::get_enum_values('sel_postulantes', 'formacion');
        $view->localidades = Localidad::getLocalidades();
        $view->especialidades = Especialidad::getEspecialidades();

        $view->disableLayout=true;
        $view->contentTemplate="view/busquedas/nPostulante_detailForm.php";
        break;

    case 'editPostulacion': //ok
        $view->label = ($_POST['target']!='view')? 'Editar postulación': 'Ver postulación';
        $view->postulacion = new Postulacion($_POST['id_postulacion']);

        $view->postulantes = Postulante::getPostulantesActivos();
        $view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');

        $view->disableLayout=true;
        $view->contentTemplate="view/busquedas/nPostulacion_detailForm.php";
        break;

    case 'deleteVehiculo':
        $view->contrato_vehiculo = new ContratoVehiculo($_POST['id_contrato_vehiculo']);
        $rta = $view->contrato_vehiculo->deleteVehiculoContrato();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    case 'checkVehiculo':
        $view->contrato_vehiculo = new ContratoVehiculo();
        $rta = $view->contrato_vehiculo->checkVehiculo($_POST['id_vehiculo'], $_POST['id_contrato'], $_POST['id_contrato_vehiculo']);
        print_r(json_encode($rta));
        exit;
        break;


    default : //carga la tabla de postulaciones //ok
        $view->disableLayout=true;
        $view->busqueda = new Busqueda($_POST['id_busqueda']);
        $view->label= $view->busqueda->getNombre();
        $view->postulaciones = Postulacion::getPostulaciones($_POST['id_busqueda'], null, null);
        $view->contentTemplate="view/busquedas/nPostulacionesForm.php";
        break;
}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);
}
else {
    //include_once('view/busquedas/busquedasLayout.php');
}


?>
