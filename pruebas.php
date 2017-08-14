<?php

include_once("config/config.php.old");
include_once("config/soporte.php");
include_once("model/empleadosModel.php");
include_once("model/localidadesModel.php");



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



