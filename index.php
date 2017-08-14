<?php
/*
session_start();
require_once("lib/config1.php");
require_once("model/seguridadModel.php");

if(isset($_SESSION["ses_id"])){


    if(!empty($_REQUEST["accion"])){
        $accion=$_REQUEST["accion"];
    }

    if(is_file("controller/".$accion."Controller.php")){
        require_once("controller/".$accion."Controller.php");
    }else
    {
        require_once("controller/errorController.php");
    }


}else
{
    if($_GET["accion"]=="error"){
        require_once("controller/errorController.php");
    }
    else{
        require_once("controller/loginController.php");
    }

}

require_once("view/layout.php");

*/
session_start();
require_once("config/config.php");
require_once("config/soporte.php");



//require_once("controller/clientesController.php");


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