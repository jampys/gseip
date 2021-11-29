<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('partes');

//cuerpo -----------------------------------------------------------------
$fila = 2;
foreach ($view->partes as $p):
    $sheet->setCellValueByColumnAndRow(1, $fila, $p['fecha_parte']);
    $sheet->setCellValueByColumnAndRow(2, $fila, $p['cuadrilla']);
    $sheet->setCellValueByColumnAndRow(3, $fila, $p['area']);
    $sheet->setCellValueByColumnAndRow(4, $fila, $p['evento']);
    $sheet->setCellValueByColumnAndRow(5, $fila, $p['nro_parte_diario']);
    $sheet->setCellValueByColumnAndRow(6, $fila, $p['orden_nro']);

    $fila++;
endforeach;


//Ajustar el ancho de todas las columnas: https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
foreach ($sheet->getColumnIterator() as $column) {
    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}


//-----------------------------------------------------------------

$writer = new Xlsx($spreadsheet);
//$writer->save('C:/temp/hello world.xlsx');
$filename = 'partes.xlsx';
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean(); //https://github.com/PHPOffice/PhpSpreadsheet/issues/217
$writer->save('php://output');
exit();


?>

