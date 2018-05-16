<?php
session_start();
require_once("config/config.php");
require_once("config/soporte.php");
require_once("model/securityModel.php");

if(isset($_SESSION["id_user"])){


    if(!empty($_REQUEST["action"])){ //se puede usar !empty o isset
        $action = $_REQUEST["action"];
    }else{
        $action = 'index';
    }

    if(is_file("controller/".$action."Controller.php")){
        require_once("controller/".$action."Controller.php");
    }else
    {
        //require_once("controller/errorController.php");
        require_once("controller/indexController.php");
    }


}else {

    if(isset($_REQUEST["action"]) && $_REQUEST["action"]=="login"){
        require_once("controller/loginController.php");
    }
    else{
        //header("Location: index.php?action=culito");
        echo '<script type="text/javascript"> window.location.href = "index.php?action=login"; </script>';
    }

}

?>