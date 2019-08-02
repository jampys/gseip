<?php

if (
    !isset($_FILES['fileToUpload']['error']) ||
    is_array($_FILES['fileToUpload']['error'])
) {echo "error";}
else{

    //$output_dir = 'uploads/habilitas'; //$GLOBALS['ini']['upload_dir']."horarios/";
    //$temp = explode(".", $_FILES["fileToUpload"]["name"]);
    //$newfilename = $id . '_' . $temp[0] . '_' .round(microtime(true)) . '.' . end($temp);
    //move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $output_dir . $newfilename);

}

//https://stackoverflow.com/questions/13246597/how-to-read-a-large-file-line-by-line
$a = array();
$counter = 0;
//$file = fopen("uploads/files/SEIP 1016 Julio.txt", "r") or exit("Unable to open file!");
//$file = $_FILES['fileToUpload']['tmp_name'];
$file = "";
if (is_uploaded_file($_FILES['fileToUpload']['tmp_name']))
    $file = file_get_contents($_FILES['fileToUpload']['tmp_name']);
//Output a line of the file until the end is reached
while(!feof($file))
{
    //echo fgets($file). "<br />";
    $a[] = fgets($file);
    //echo $line."<br />";
    //if ($line[1] == "Contrato") echo $line[0]."<br />";


}
fclose($file);


foreach ($a as $k => $v) {
    // $array[3] se actualizará con cada valor de $array...
    $line_1 = explode(" ", $a[$k]);
    //$line_2 = explode(" ", $a[$k+1]);
    //echo is_numeric($line_1[0]). "<br />";
    //print_r($array);
    if ($line_1[1] == "Contrato") {
        $counter +=1;
        echo $line_1[0];
        $b = array();
        $b = array_slice($a, $k+1 ,20);

        foreach ($b as $k1 => $v1) {
            $line_2 = explode(" ", $b[$k1]);
            // $array[3] se actualizará con cada valor de $array...
            //$line_1 = explode(" ", $a[$k]);
            //$line_2 = explode(" ", $a[$k+1]);
            //echo is_numeric($line_1[0]). "<br />";
            //print_r($array);
            //if ($line_1[1] == "Contrato") echo $line_1[0];
            if (is_numeric($line_2[0]) && is_numeric($line_2[1]) ) {
                echo " ".$line_2[1].
                     " ".$line_2[4].
                     " ".$line_2[6].
                     " ".$line_2[7].
                     "<br />";
                break;
            }else{
                continue;
            }




        }



    }


}

echo "Registros procesados: ".$counter;

//cantidad un. pr unitario importe

?>









