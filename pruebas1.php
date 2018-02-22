<?php
session_start();

include_once("config/config.php");
include_once("config/soporte.php");
include_once("model/empleadosModel.php");
include_once("model/localidadesModel.php");

include_once("model/securityModel.php");
?>


*********** array en php ************************
<br/>
<?php
$a = array();
$a ['VER_EMP']['1']= 15;
$a ['VER_EMP']['2']= 96;
$a ['UPDATE_EMP']['2']= 10;

print_r($a);



?>

<br/>
<br/>
*********** pruebas de seguridad (pasando multiples dominios)***************************
<br/>

<?php
// pruebas sobre PrivilegedUser, Role y Privilege
//$pa = new PrivilegedUser(1);
//if($pa->hasPrivilege('EMP_ABM', array(5)) ) echo 'tiene privilegio';
//else echo 'no tiene privilegio';
?>


<?php
// pruebas sobre PrivilegedUser y Role
$pu = new PrivilegedUser(3); //id_usuario 1:RT 2:administrador, id_dominio: 1:SEIP, 2:YPF GASMED
if($pu->hasAction('EMP_INSERT', array(6, 3) )) echo 'tiene accion';
else echo 'no tiene accion';
?>

<br/>
<br/>
*********** pruebas de mensajes de error ***************************
<br/>

<?php
echo PrivilegedUser::getErrorMessage('PRIVILEGE', 'PUE_VER');


?>









