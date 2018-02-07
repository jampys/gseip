<?php

include_once("config/config.php");
include_once("config/soporte.php");
include_once("model/empleadosModel.php");
include_once("model/localidadesModel.php");

include_once("model/securityModel1.php");



$stmt=new sQuery();
$query = "SHOW COLUMNS FROM empleados WHERE Field = 'sexo'";
$stmt->dpPrepare($query);
$stmt->dpExecute();
$p = $stmt->dpFetchAll();





$type = $p[0]['Type'];
preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
//$vals = explode("','", $matches[1]);
//print_r( $type);

$vals=array();
$vals[0]= explode("','", $matches[1]);
$vals[1] = $p[0]['Default'];
print_r( $vals[1]);


?>

<hr/>

<?php

print_r( Soporte::getPeriodos(2015, 2017));
?>

<hr/>

<?php

echo str_pad(2, 5, 0, STR_PAD_LEFT);
?>


<br/>
*********** pruebas de seguridad ***************************
<br/>
<?php

//print_r(Role::getRolePerms(1));
//$s = new Role();
$s = Role::getRolePerms(1);
//if($s->hasPerm('EMP_VER')) echo 'tiene permiso';
//else echo 'no tiene permiso';


$pu = new PrivilegedUser(2);
if($pu->hasPrivilege('EMP_VER')) echo 'tiene privilegio';
else echo 'no tiene privilegio';
?>
<br/>

<?php
$pa = new PrivilegedUser(2);
if($pa->hasAction('EMP_INSERT')) echo 'tiene accion';
else echo 'no tiene accion';
?>








