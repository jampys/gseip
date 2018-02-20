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
$str = '1,2,3';
$arr = explode(',',$str);
foreach ($arr as $a) echo 'elemento: '.$a.'<br/>';
?>

<br/>
<br/>
*********** pruebas de seguridad (pasando multiples dominios)***************************
<br/>
<?php
// pruebas sobre PrivilegedUser y Role
//$pu = new PrivilegedUser(2); //id_usuario 1:RT 2:administrador, id_dominio: 1:SEIP, 2:YPF GASMED
//if($pu->hasPrivilege('RPE_VER', array(3, 6) )) echo 'tiene privilegio';
//else echo 'no tiene privilegio';
?>


<?php
// pruebas sobre PrivilegedUser, Role y Privilege
$pa = new PrivilegedUser(1);
if($pa->hasAction('EMP_INSERT', array(8, 1)) ) echo 'tiene privilegio';
else echo 'no tiene privilegio';
?>







