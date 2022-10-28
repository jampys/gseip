<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('pendientes');

//titulo ----------------------------------------------------------------

$spreadsheet->getActiveSheet()->mergeCells('A1:D1'); //$spreadsheet->getActiveSheet()->mergeCells("$range1:$range2");
$spreadsheet->getActiveSheet()->mergeCells('A2:D2');
$spreadsheet->getActiveSheet()->mergeCells('A3:D3');
$spreadsheet->getActiveSheet()->getStyle('A1:B3')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A1:B3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

$sheet->setCellValueByColumnAndRow(1, 1, 'RC01 Control de personal sin capacitación');
$sheet->setCellValueByColumnAndRow(1, 2, 'Período: '.$encabezado['periodo']);
$sheet->setCellValueByColumnAndRow(1, 3, 'Fecha emisión: '.$encabezado['fecha_emision']);


//encabezado ------------------------------------------------------------
$cabecera = ["Empleado", "Contratos"];
$sheet->fromArray($cabecera, null, 'A5');
$spreadsheet->getActiveSheet()->getStyle('A5:B5')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A5:B5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//cuerpo -----------------------------------------------------------------
$fila = 6;
foreach ($view->empleados as $p):
    $sheet->setCellValueByColumnAndRow(1, $fila, $p['empleado']);
    $sheet->setCellValueByColumnAndRow(2, $fila, $p['contratos']);

    $fila++;
endforeach;


//Ajustar el ancho de todas las columnas: https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
foreach ($sheet->getColumnIterator() as $column) {
    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}


//configuro el auto filter
$spreadsheet->getActiveSheet()->setAutoFilter('A5:B5');

//genero repore
$writer = new Xlsx($spreadsheet);
//$writer->save('C:/temp/hello world.xlsx');
$filename = 'RC01_Personal_sin_capacitacion_'.date("d-m-Y").'.xlsx';
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean(); //https://github.com/PHPOffice/PhpSpreadsheet/issues/217
$writer->save('php://output');
exit();


?>

