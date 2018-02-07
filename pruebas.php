<?php

include_once("config/config.php");
include_once("config/soporte.php");
include_once("model/empleadosModel.php");
include_once("model/localidadesModel.php");

include_once("model/securityModel.php");



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
<?php

print_r(Role::getRolePerms(1));
?>








