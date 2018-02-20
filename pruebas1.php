<?php
session_start();

include_once("config/config.php");
include_once("config/soporte.php");
include_once("model/empleadosModel.php");
include_once("model/localidadesModel.php");

include_once("model/securityModel2.php");
?>


*********** array en php ************************
<br/>
<?php
$arr = array(1,2);
foreach ($arr as $a) echo $a;
?>

<br/>
<br/>
*********** pruebas de seguridad (pasando un objeto)***************************
<br/>
<?php
// pruebas sobre PrivilegedUser y Role
//$pu = new PrivilegedUser(2); //id_usuario 1:RT 2:administrador, id_dominio: 1:SEIP, 2:YPF GASMED
//if($pu->hasPrivilege('EMP_VER', null )) echo 'tiene privilegio';
//else echo 'no tiene privilegio';
?>


<?php
// pruebas sobre PrivilegedUser, Role y Privilege
$pa = new PrivilegedUser(2);
if($pa->hasAction('EMP_SELECT', null)) echo 'tiene accion';
else echo 'no tiene accion';
?>







