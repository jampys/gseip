<?php
require_once("model/usuariosModel.php");

require("resources/libraries/phpmailer/PHPMailer.php");
require("resources/libraries/phpmailer/SMTP.php");
require("resources/libraries/phpmailer/Exception.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$operation = "";
if(isset($_REQUEST['operation'])) $operation=$_REQUEST['operation'];

$view->u=new Usuario();

$view->disableLayout=false;

switch($operation){

    case 'login':

        if (isset($_POST['usuario']) && isset($_POST['contraseña']) ){

            $id = $view->u->isAValidUser($_POST['usuario'],$_POST['contraseña']);

            if($id >= 1){
                $_SESSION["id_user"] = $view->u->getIdUser(); //$id;
                $_SESSION["user"] = $view->u->getUser(); //$_POST['usuario'];
                $_SESSION["id_empleado"] = $view->u->getIdEmpleado();
                $_SESSION["profile_picture"] = $view->u->getProfilePicture();

                $obj = new PrivilegedUser($_SESSION["id_user"]);
                $_SESSION['loggedUser'] = serialize($obj);

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

    case 'recuperar-contraseña':
        //session_destroy();
        //$view->content="view/login.php";
        //header("Location: index.php");
        //para evitar los tipicos errores del header location =>lo hago con javascript
        $view->contentTemplate="view/login/recuperarForm.php";
        break;


    case 'check-user-exists':

        if (isset($_POST['usuario']) ){

            $id = $view->u->checkUserExists($_POST['usuario']);

            if($id >= 1){ //usuario existe
                $_SESSION["id_user_recup"] = $view->u->getIdUser(); //$id;
                //$_SESSION["user"] = $view->u->getUser(); //$_POST['usuario'];
                //$_SESSION["id_empleado"] = $view->u->getIdEmpleado();
                //$_SESSION["profile_picture"] = $view->u->getProfilePicture();

                //$obj = new PrivilegedUser($_SESSION["id_user"]);
                //$_SESSION['loggedUser'] = serialize($obj);

                //se genera codigo
                $code = substr( md5(microtime()), 1, 4); //genera codigo aleatorio de 4 digitos



                // se envia codigo por email
                try{

                    ob_start();
                    include ('email/password.php');
                    $body= ob_get_contents();
                    ob_get_clean();

                    $target = "dario.picon@innsa.com";


                    //require("resources/libraries/phpmailer/class.phpmailer.php");
                    $mail = new PHPMailer();
                    $mail->CharSet = "UTF-8";
                    $mail->IsSMTP();
                    $mail->SMTPAuth = true;
                    $mail->Host = "seip.com.ar"; // SMTP a utilizar. Por ej. smtp.elserver.com
                    $mail->Username = "gestion@seip.com.ar"; // Correo completo a utilizar
                    $mail->Password = "Ada21_Dos"; // Contraseña
                    $mail->Port = 26; // Puerto a utilizar

                    $mail->From = "gestion@seip.com.ar"; // Desde donde enviamos (Para mostrar)
                    $mail->FromName = "Gestión de RRHH SEIP";

                    $mail->AddAddress($target); // Esta es la dirección a donde enviamos
                    $mail->IsHTML(true); // El correo se envía como HTML
                    $mail->AddEmbeddedImage('resources/img/seip140x40.png', 'logo_2u');
                    $mail->Subject = "Restablecimiento de contraseña"; // Este es el titulo del email.
                    $mail->SMTPAutoTLS = false;
                    //$body = "Hola mundo. Esta es la primer línea<br />";
                    //$body .= "Acá continuo el <strong>mensaje</strong>";
                    $mail->Body = $body; // Mensaje a enviar
                    $exito = $mail->Send(); // Envía el correo.


                    $e = array();

                    //$e['msg'] = $rta;


                    if($exito){
                        $e['msg'] = "El correo fue enviado correctamente.";
                        $e['id'] = $id;
                    }else{
                        $e['msg'] = "Hubo un inconveniente. Contacta a un administrador.";
                        $e['id'] = -2;
                    }



                } catch (PhpmailerException $e) {
                    //echo $e->errorMessage(); //Pretty error messages from PHPMailer
                    $e['msg'] = "entro catch de php mailer";
                    $e['id'] = -4;
                } catch(Exception $e){
                    //echo $e->getMessage(); //habilitar para ver el mensaje de error
                    //sQuery::dpRollback();
                    //print_r(json_encode(-1));
                    $e['msg'] = "entro catch general";
                    $e['id'] = -4;
                }


                //se inserta el codigo enviado en el usuario
                $view->u->updateCode($code);


            }
            else if($id == 0){ //usuario inhabilitado
                $e = array();
                $e['msg']= "Usuario inhabilitado. No es posible recuperar la contraseña.";
                $e['id'] = $id;
            }
            else if($id == -1){ //Usuario o contraseña inválidos
                $e = array();
                $e['msg']= "El email ingresado no existe";
                $e['id'] = $id;
            }

        }

        print_r(json_encode($e));
        exit;
        break;

    case 'send-code':
        //session_destroy();
        //$view->content="view/login.php";
        //header("Location: index.php");
        //para evitar los tipicos errores del header location =>lo hago con javascript
        $view->contentTemplate="view/login/codeForm.php";
        break;





    case 'check-code':

        if (isset($_POST['code']) ){

            $id = $view->u->checkCode($_SESSION["id_user_recup"], $_POST['code']);


            $e = array();

            if($id >= 1){ //usuario existe
                //$_SESSION["id_user_recup"] = $view->u->getIdUser(); //$id;
                $e['msg']= "Codigo ingresado ok";
                $e['id'] = $id;
            }
            else if($id == -1){ //Usuario o contraseña inválidos
                $e['msg']= "Codigo ingresado invalido";
                $e['id'] = $id;
            }

        }

        print_r(json_encode($e));
        exit;
        break;


    case 'toNewPasswordform':
        //session_destroy();
        //$view->content="view/login.php";
        //header("Location: index.php");
        //para evitar los tipicos errores del header location =>lo hago con javascript
        $view->contentTemplate="view/login/newPasswordForm.php";
        break;


    case 'saveNewPassword':
        $puesto = new Usuario($_SESSION["id_user_recup"]);
        $puesto->setPassword($_POST['password']);


        $rta = $puesto->updatePassword();
        //print_r(json_encode($rta));
        $e = array();
        if($rta >= 1){ //reseteo exitoso
            $e['msg']= "Se ha restablecido la contraseña de manera correcta.";
            $e['id'] = $rta;
        }
        else { //Reseteo falló
            $e['msg']= "Error al restablecer la contraseña.";
            $e['id'] = $rta;
        }

        print_r(json_encode($e));
        exit;
        break;





    default:
        //$view->e=new Empleado();
        //$view->companias=$view->e->getCompanias();
        //$view->content="view/login.php";
        //include_once('view/loginLayout.php');
        $view->contentTemplate="view/login/loginForm.php";
        break;


}


if ($view->disableLayout==true) {
    include_once ($view->contentTemplate);}
else {
    include_once('view/login/loginLayout.php'); // el layout incluye el template adentro
}


?>

