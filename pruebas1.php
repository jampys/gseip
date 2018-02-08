<?php

include_once("config/config.php");
include_once("config/soporte.php");
include_once("model/empleadosModel.php");
include_once("model/localidadesModel.php");

include_once("model/securityModel2.php");
?>


*********** pruebas de seguridad ***************************
<br/>
<?php

//print_r(Role::getRolePerms(1));
//$s = new Role();
//$s = Role::getRolePerms(1);
//if($s->hasPerm('EMP_VER')) echo 'tiene permiso';
//else echo 'no tiene permiso';


$pu = new PrivilegedUser(1); //2: administrador, 1: RT
if($pu->hasPrivilege('RPE_ABM')) echo 'tiene privilegio';
else echo 'no tiene privilegio';
?>
<br/>

<?php
$pa = new PrivilegedUser(1);
if($pa->hasAction('RPE_SELECT')) echo 'tiene accion';
else echo 'no tiene accion';
?>








