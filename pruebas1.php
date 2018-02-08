<?php

include_once("config/config.php");
include_once("config/soporte.php");
include_once("model/empleadosModel.php");
include_once("model/localidadesModel.php");

include_once("model/securityModel.php");
?>


*********** pruebas de seguridad ***************************
<br/>
<?php

//print_r(Role::getRolePerms(1));
//$s = new Role();
//$s = Role::getRolePerms(1);
//if($s->hasPerm('EMP_VER')) echo 'tiene permiso';
//else echo 'no tiene permiso';

// pruebas sobre PrivilegedUser y Role
//$pu = new PrivilegedUser(1); //id_usuario 1:RT 2:administrador, id_dominio: 1:SEIP, 2:YPF GASMED
//if($pu->hasPrivilege('RPE_VER', 1)) echo 'tiene privilegio';
//else echo 'no tiene privilegio';
?>
<br/>

<?php
// pruebas sobre PrivilegedUser, Role y Privilege
$pa = new PrivilegedUser(1);
if($pa->hasAction('RPE_INSERT', 5)) echo 'tiene accion';
else echo 'no tiene accion';
?>








