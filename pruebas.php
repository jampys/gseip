<?php

include_once("config/config.php");
include_once("config/soporte.php");
include_once("model/empleadosModel.php");
include_once("model/localidadesModel.php");

print_r(Empleado::getSexos());


?>


<select>
    <?

    $enumList = explode(",", str_replace("'", "", substr(Empleado::getSexos()['COLUMN_TYPE'], 5, (strlen(Empleado::getSexos()['COLUMN_TYPE'])-6))));

    foreach($enumList as $value){
        echo "<option value=\"$value\">$value</option>";
    }
    ?>
</select>




<hr/>

<?php

$vals = Soporte::get_enum_values('empleados', 'sexo');

foreach($vals as $val){
    echo $val;
}

?>

<hr/>






