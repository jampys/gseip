<?php

/*require_once("config/config.php");
include_once("model/habilidadesModel.php");

$habilidad = new Habilidad($_POST['id_habilidad']);
$habilidad->setCodigo($_POST['codigo']);
$habilidad->setNombre($_POST['nombre']);

$rta = $habilidad->save();
//print_r(json_encode($rta));
//$ah = new sQuery();
//echo "conexion comun: ".print_r(squery::dpLastInsertId() )."///";

*/







$id = $_POST['id'];
/* con este id hago el insert de las fotos en la BD */



$output_dir = "uploads/";
if(isset($_FILES["myfile"]))
{
	$ret = array();
	
//	This is for custom errors;	
/*	$custom_error= array();
	$custom_error['jquery-upload-file-error']="File already exists";
	echo json_encode($custom_error);
	die();
*/
	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
 	 	$fileName = $_FILES["myfile"]["name"];
 		move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
    	$ret[]= $fileName;
	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["myfile"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$fileName = $_FILES["myfile"]["name"][$i];
		move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
	  	$ret[]= $fileName;
	  }
	
	}
    echo json_encode($ret);
    //echo $id;
    //$stmt=new sQuery();
    //echo "conexion upload ".print_r(squery::dpGetConnectionId() );
    //echo "last id: ".print_r(squery::dpLastInsertId() )."///";


 }
 ?>