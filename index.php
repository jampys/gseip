<?php
session_start();
require_once("config/config.php");
require_once("config/soporte.php");


if(isset($_SESSION["id_usuario"])){


    if(!empty($_REQUEST["action"])){
        $action = $_REQUEST["action"];
    }else{
        $action = 'index';
    }

    if(is_file("controller/".$action."Controller.php")){
        require_once("controller/".$action."Controller.php");
    }else
    {
        //require_once("controller/errorController.php");
    }


}else
{
    if($_GET["accion"]=="error"){
        //require_once("controller/errorController.php");
    }
    else{
        require_once("controller/loginController.php");
    }

}

?>