<?php

if(isset($_REQUEST['operation']))
{$operation=$_REQUEST['operation'];}

require_once("model/usuariosModel.php");
$view->u=new Usuario();

switch($operation){

    case 'login':

        if (isset($_POST['usuario']) && isset($_POST['contraseña']) ){

            $id = $view->u->isAValidUser($_POST['usuario'],$_POST['contraseña']);

            if($id >= 1){
                $_SESSION["id_usuario"] = $view->u->getIdUsuario(); //$id;
                $_SESSION["usuario"] = $view->u->getUsuario(); //$_POST['usuario'];
                $e = array();
                $e['id'] = $id;
                /*if($id[5]!=1) { //si se ha limpiado el password  hay que cambiarlo...
                    $_SESSION["id_usuario"] = $id[0];
                    $_SESSION["usuario"] = $id[1];


                    //http://stackoverflow.com/questions/1442177/storing-objects-in-php-session
                    //$obj = new PrivilegedUser($_SESSION["ses_id"]);
                    //$_SESSION['loggedUser'] = serialize($obj);
                }*/
                /*else{
                    $view->id_usuario = (int)$id[0];
                    $view->content="view/login_clear_pass.php";
                }*/


                //header("Location: ".Conexion::ruta()."?accion=index");
                //echo "EL USUARIO SE SE LOGUEO OK";
                //IMPORTANTE: probar mas que un header location cargar una $view->content error
                //Por el momento no se le carga ninguna vista para cuando ingresa ok.
            }
            else if($id == 0){ //usuario inhabilitado
                $e = array();
                $e['msg']= "Usuario inhabilitado";
                $e['id'] = $id;
            }
            else if($id == -1){ //Usuario o contraseña inválidos
                $e = array();
                $e['msg']= "Usuario o contraseña inválidos";
                $e['id'] = $id;
            }

        }

        print_r(json_encode($e));
        exit;
        break;



    case 'clear_pass': //2do paso al blanquear password. Obliga al usuaria a cambiar el password
        $view->u->setIdUsuario($_POST['id_usuario']);
        $view->u->setPassword($_POST['password']);
        $view->u->setClearPass(0);

        //$rta=$view->u->updatePassword();
        if(!$view->u->updatePassword()){
            $_SESSION["error"]="Error al modificar la contraseña. Inténtelo mas tarde";
            $view->content="view/error.php";
        }
        else{
            sQueryOracle::hacerCommit();
            header("Location: index.php");
        }



        break;


    case 'clear_pass_first': //1er paso al blanquear password. Envia mail al usuario con nuevo password
        $rta=1;
        $view->u->setIdUsuario($_POST['id_usuario']);
        $view->u->setClearPass(1);
        $pass = substr( md5(microtime()), 1, 8); //genero un password aleatorio de 8 caracteres
        $view->u->setPassword($pass);
        if(!$view->u->updatePassword()) $rta=0;

        $usuario=$view->u->getUsuarioById($_POST['id_usuario']);
        $id_empleado=$usuario[0]['ID_EMPLEADO'];


        /***********************EMAIL**********************************/
        //Obtener datos para enviar el mail
        $view->e=new Empleado();
        $emp=$view->e->getEmpleadoById($id_empleado); //id_empleado

        if(!$emp){$rta=0; } //Si la consulta SQL no devuelve ningun registro
        else{ //si la consulta SQL devuelve 1 registro

            //codigo para el envio de e-mail
            $para=$emp[0]['EMAIL'];
            $asunto = 'INNSA - Blanqueo de contraseña';
            $asunto = '=?UTF-8?B?'.base64_encode($asunto).'?=';
            $body_usuario=$usuario[0]['LOGIN'];
            $body_pass=$pass;

            //codigo para incluir en la variable $mensaje el template de correo de la comunicacion, que se encuentra en email/comunicacion.php
            ob_start();
            include ('email/password.php');
            $mensaje= ob_get_contents();
            ob_get_clean();

            //Cabecera que especifica que es un HMTL
            $headers = 'From: INNSA Capacitacion <no-reply@innsa.com>' . "\r\n";
            $headers.= 'MIME-Version: 1.0' . "\r\n";
            $headers.= 'Content-type: text/html; charset=utf-8' . "\r\n";

            if(!@mail($para, utf8_decode($asunto), $mensaje, $headers)) $rta=-1; //envia email (por mas que la direccion sea incorrecta y lo rebote)

            if($rta>0){ //update usuario ok y envio mail ok
                $respuesta = array ('response'=>'success','comment'=>'Contraseña enviada correctamente');
                sQueryOracle::hacerCommit();
            }
            else{ //update usuario fail o envio mail fail
                if($rta==0)$respuesta = array ('response'=>'error','comment'=>'Error al blanquear la contraseña');
                if($rta==-1) $respuesta = array ('response'=>'error','comment'=>'Error al blanquear la contraseña. Servidor de e-mail inactivo');
                sQueryOracle::hacerRollback();
            }

        }
        //------------------FIN EMAIL----------------------------------------

        print_r(json_encode($respuesta));
        exit;
        break;


    case 'salir':
        session_destroy();
        //$view->content="view/login.php";
        //header("Location: index.php");
        //para evitar los tipicos errores del header location =>lo hago con javascript
        echo "<script>window.location='index.php';</script>";
        break;

    default:
        //$view->e=new Empleado();
        //$view->companias=$view->e->getCompanias();
        //$view->content="view/login.php";
        //include_once('view/loginLayout.php');
        $view->contentTemplate="view/loginForm.php";
        break;


}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);}
else {
    include_once('view/loginLayout.php'); // el layout incluye el template adentro
}


?>

