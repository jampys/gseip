<?php

include_once("model/empleadosModel.php");
include_once("model/localidadesModel.php");
include_once("model/contrato-empleadoModel.php");
include_once("model/nov_conveniosModel.php");
include_once("model/empleado-vencimientoModel.php");

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->disableLayout=false;


switch ($operation)
{
    case 'refreshGrid':
        $view->disableLayout=true;
        $view->empleados = Empleado::getEmpleados();
        $view->contentTemplate="view/empleadosGrid.php";
        break;

    case 'saveEmpleado':
        $empleado = new Empleado($_POST['id_empleado']);
        $empleado->setLegajo($_POST['legajo']);
        $empleado->setApellido($_POST['apellido']);
        $empleado->setNombre($_POST['nombre']);
        $empleado->setDocumento($_POST['documento']);
        $empleado->setCuil($_POST['cuil']);
        $empleado->setFechaNacimiento($_POST['fecha_nacimiento']);
        $empleado->setFechaAlta($_POST['fecha_alta']);
        $empleado->setFechaBaja($_POST['fecha_baja']);
        $empleado->setDireccion($_POST['direccion']);
        $empleado->setIdLocalidad($_POST['localidad']);
        $empleado->setTelefono($_POST['telefono']);
        $empleado->setEmail($_POST['email']);
        $empleado->setSexo($_POST['sexo']);
        $empleado->setNacionalidad($_POST['nacionalidad']);
        $empleado->setEstadoCivil($_POST['estado_civil']);
        $empleado->setEmpresa($_POST['empresa']);
        $empleado->setIdConvenio( ($_POST['id_convenio'])? $_POST['id_convenio'] : null  );

        //$cliente->save();
        //break;
        $rta = $empleado->save($_POST['cambio_domicilio']);
        print_r(json_encode($rta));
        exit;
        break;

    case 'newEmpleado':
        $view->empleado = new Empleado();
        $view->label='Nuevo Empleado';

        $view->localidades = Localidad::getLocalidades();
        $view->sexos = Soporte::get_enum_values('empleados', 'sexo');
        $view->estados_civiles = Soporte::get_enum_values('empleados', 'estado_civil');
        $view->nacionalidades = Soporte::get_enum_values('empleados', 'nacionalidad');
        $view->empresas = Soporte::get_enum_values('empleados', 'empresa');
        $view->convenios = Convenio::getConvenios();

        $view->disableLayout=true;
        $view->contentTemplate="view/empleadosForm.php";
        break;

    case 'editEmpleado':
        $view->empleado = new Empleado($_POST['id']);
        $view->label = $view->empleado->getApellido().' '.$view->empleado->getNombre();

        $view->localidades = Localidad::getLocalidades();
        $view->sexos = Soporte::get_enum_values('empleados', 'sexo');
        $view->estados_civiles = Soporte::get_enum_values('empleados', 'estado_civil');
        $view->nacionalidades = Soporte::get_enum_values('empleados', 'nacionalidad');
        $view->empresas = Soporte::get_enum_values('empleados', 'empresa');
        $view->convenios = Convenio::getConvenios();

        $view->domicilios = $view->empleado->getDomiciliosByEmpleado();

        $view->disableLayout=true;
        $view->contentTemplate="view/empleadosForm.php";
        break;

    case 'checkEmpleadoCuil':
        $view->empleado = new Empleado();
        $id_empleado = ($_POST['id_empleado']!='')? $_POST['id_empleado'] : null;
        $cuil = ($_POST['cuil']!='')? $_POST['cuil'] : null;
        $rta = $view->empleado->checkEmpleadoCuil($cuil, $id_empleado);
        print_r(json_encode($rta));
        exit;
        break;

    case 'checkEmpleadoLegajo':
        $view->empleado = new Empleado();
        $rta = $view->empleado->checkEmpleadoLegajo($_POST['legajo'], $_POST['id_empleado']);
        print_r(json_encode($rta));
        exit;
        break;


    case 'loadContratos': //abre la ventana modal para mostrar los detalles del empleado
        $view->empleado = new Empleado($_POST['id_empleado']);
        $view->label = $view->empleado->getApellido().' '.$view->empleado->getNombre();

        $view->contratos = ContratoEmpleado::getContratosByEmpleado($_POST['id_empleado']);
        $view->vencimientos = EmpleadoVencimiento::getEmpleadoVencimiento($_POST['id_empleado']);

        $view->disableLayout=true;
        $view->contentTemplate="view/empleadosFormContratos.php";
        break;



    case 'saveVencimientos':

        try{

            sQuery::dpBeginTransaction();

            $empleado = new Empleado($_POST['id_empleado']);
            $id_empleado = $empleado->getIdEmpleado();

            $vVencimientos = json_decode($_POST["vEmpleados"], true);
            //print_r($vEmpleados);

            foreach ($vEmpleados as $vE) {

                //$c = new HabilidadEmpleado();
                //$c->setIdHabilidad($vH['id_habilidad']);
                //$c->setIdEmpleado($vE['id_empleado']);
                //if($c->insertHabilidadEmpleado() < 0) $flag = -1;  //si falla algun insert $flag = -1

                //echo "id_contrato :".$id." - id_empleado: ".$vE['id_empleado'];
                //echo "id_contrato :".$id." - procesos: ".$vE['id_proceso'];
                $empleado_contrato = new ContratoEmpleado();
                $empleado_contrato->setIdEmpleadoContrato($vE['id_empleado_contrato']);
                $empleado_contrato->setIdEmpleado($vE['id_empleado']);
                $empleado_contrato->setIdContrato($id_contrato);
                $empleado_contrato->setIdPuesto($vE['id_puesto']);
                $empleado_contrato->setFechaDesde($vE['fecha_desde']);
                $empleado_contrato->setFechaHasta(($vE['fecha_hasta'])? $vE['fecha_hasta']: null); //fecha_desde puede ser null
                $empleado_contrato->setIdLocalidad(($vE['id_localidad'])? $vE['id_localidad']: null);

                //echo 'id empleado contrato: '.$vE['id_empleado_contrato'].'---';

                //echo $vE['operacion'];
                if($vE['operacion']=='insert') {
                    $empleado_contrato->insertEmpleadoContrato();
                    $id_empleado_contrato = sQuery::dpLastInsertId();
                    if($vE['id_proceso']){
                        foreach($vE['id_proceso'] as $p){ //si se agregaron procesos
                            //echo $p." ";
                            $contrato_empleado_proceso = new ContratoEmpleadoProceso();
                            $contrato_empleado_proceso->setIdEmpleadoContrato($id_empleado_contrato);
                            $contrato_empleado_proceso->setIdProceso($p);
                            $contrato_empleado_proceso->contratoEmpleadoProceso(); //si falla sale por el catch
                        }

                    }


                }
                else if( $vE['operacion']=='update') {
                    $empleado_contrato->updateEmpleadoContrato();
                    $id_empleado_contrato = $empleado_contrato->getIdEmpleadoContrato();
                    if($vE['id_proceso']){ // si se editaron procesos
                        foreach($vE['id_proceso'] as $p){
                            //echo $p." ";
                            $contrato_empleado_proceso = new ContratoEmpleadoProceso();
                            $contrato_empleado_proceso->setIdEmpleadoContrato($id_empleado_contrato);
                            $contrato_empleado_proceso->setIdProceso($p);
                            $contrato_empleado_proceso->contratoEmpleadoProceso(); //si falla sale por el catch
                            //echo 'operacion: '.$vE['operacion'].' - id_empleado_contrato: '.$contrato_empleado_proceso->getIdEmpleadoContrato().' - id_proceso: '.$contrato_empleado_proceso->getIdProceso();
                        }

                    }


                }
                else if( $vE['operacion']=='delete') {
                    //Elimina en cascada los registros hijos de la tabla empleado_contrato_proceso
                    $empleado_contrato->deleteEmpleadoContrato(); //si falla sale por el catch
                }


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




    default :
        $view->empleados=Empleado::getEmpleados();
        $view->contentTemplate="view/empleadosGrid.php"; // seteo el template que se va a mostrar
        break;
}

if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);}
else {
    include_once('view/empleadosLayout.php');
}


?>
