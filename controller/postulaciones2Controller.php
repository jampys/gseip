<?php
include_once("model/busquedasModel.php");
include_once("model/postulacionesModel.php");
include_once("model/postulantesModel.php");
include_once("model/localidadesModel.php");
include_once("model/sel_especialidadesModel.php");
include_once("model/empleadosModel.php");

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
        $rta = $view->postulaciones = Postulacion::getPostulaciones($_POST['id_busqueda'], null, null);
        //$view->contentTemplate="view/busquedas/nPostulacionesGrid.php";
        //break;
        print_r(json_encode($rta));
        exit;
        break;

    case 'savePostulacion': //ok

        try{

            $rta = array();
            $rta['msg']= "";
            $rta['id_postulante'] = "";

            sQuery::dpBeginTransaction();

            if($_POST['id_postulante']){
            //inserta o edita una postulacion (con postulante ya existente)
                $postulacion = new Postulacion($_POST['id_postulacion']);
                $postulacion->setIdBusqueda($_POST['id_busqueda']);
                $postulacion->setIdPostulante($_POST['id_postulante']);
                $postulacion->setOrigenCv($_POST['origen_cv']);
                $postulacion->setExpectativas($_POST['expectativas']);
                $postulacion->setPropuestaEconomica($_POST['propuesta_economica']);
                $postulacion->setIdUser($_SESSION['id_user']);

                $rta['msg'] = $postulacion->save();
                //print_r(json_encode(sQuery::dpLastInsertId()));
                sQuery::dpCommit();
                print_r(json_encode($rta));

            }elseif ($_POST['apellido'] && $_POST['nombre']){
                //inserta una postulacion (creando simultaneamente un nuevo postulante)

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
                $rta['id_postulante'] = sQuery::dpLastInsertId();

                //inserta postulacion
                $postulacion = new Postulacion($_POST['id_postulacion']);
                $postulacion->setIdBusqueda($_POST['id_busqueda']);
                $postulacion->setIdPostulante($rta['id_postulante']);
                $postulacion->setOrigenCv($_POST['origen_cv']);
                $postulacion->setExpectativas($_POST['expectativas']);
                $postulacion->setPropuestaEconomica($_POST['propuesta_economica']);
                $postulacion->setIdUser($_SESSION['id_user']);
                $postulacion->save();

                $rta['msg'] = 1;
                sQuery::dpCommit();
                print_r(json_encode($rta));


            }else{ //no trae datos
                throw new PDOException('Error dario: no trae datos');
            }

        }catch (PDOException $e){

            //if($e->errorInfo[1] == 1062) $rta['duplicates']++;
            //else $rta['others']++;
            $rta['msg'] = -1;
            sQuery::dpRollback();
            print_r(json_encode($rta));

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
        //$view->label = ($_POST['target']!='view')? 'Editar postulación': 'Ver postulación';
        $view->postulacion = new Postulacion($_POST['id_postulacion']);
        $view->id_postulante = new Empleado($view->postulacion->getIdPostulante());
        $view->postulante = $view->id_postulante->getApellido().' '.$view->id_postulante->getNombre();
        $view->label= $view->postulante;

        $view->postulantes = Postulante::getPostulantesActivos();
        $view->origenes_cv = Soporte::get_enum_values('sel_postulaciones', 'origen_cv');
        $view->target = $_POST['target'];

        $view->disableLayout=true;
        $view->contentTemplate="view/busquedas/nPostulacion_detailForm.php";
        break;

    case 'deletePostulacion': //ok
        $view->postulacion = new Postulacion($_POST['id_postulacion']);
        /*try{
            $rta = $view->postulacion->deletePostulacion();
        }catch (PDOException $e){
            $rta = -1;
        }*/
        $rta = $view->postulacion->deletePostulacion();
        print_r(json_encode($rta));
        die; // no quiero mostrar nada cuando borra , solo devuelve el control.
        break;

    case 'checkPostulacion': //ok
        $view->postulacion = new Postulacion();
        $rta = $view->postulacion->checkPostulacion($_POST['id_postulante'], $_POST['id_busqueda'], $_POST['id_postulacion']);
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
