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
$spreadsheet->getActiveSheet()->mergeCells('A4:D4');
$spreadsheet->getActiveSheet()->mergeCells('A5:D5');
$spreadsheet->getActiveSheet()->getStyle('A1:D5')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A1:D5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

$sheet->setCellValueByColumnAndRow(1, 1, 'Cliente: '.$encabezado['cliente']);
$sheet->setCellValueByColumnAndRow(1, 2, 'Contrato: '.$encabezado['contrato']);
$sheet->setCellValueByColumnAndRow(1, 3, 'Empleado: '.$encabezado['empleado']);
$sheet->setCellValueByColumnAndRow(1, 4, 'Período: '.$encabezado['periodo']);
$sheet->setCellValueByColumnAndRow(1, 5, 'Fecha emisión: '.$encabezado['fecha_emision']);


//encabezado ------------------------------------------------------------
$cabecera = ["Fecha", "Día semana", "Empleado", "Observaciones"];
$sheet->fromArray($cabecera, null, 'A7');
$spreadsheet->getActiveSheet()->getStyle('A7:D7')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A7:D7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//cuerpo -----------------------------------------------------------------
$fila = 8;
foreach ($view->partes as $p):
    $sheet->setCellValueByColumnAndRow(1, $fila, $p['fecha']);
    $sheet->setCellValueByColumnAndRow(2, $fila, $p['dia_semana']);
    $sheet->setCellValueByColumnAndRow(3, $fila, $p['empleado']);
    $sheet->setCellValueByColumnAndRow(4, $fila, $p['descripcion']);

    $fila++;
endforeach;


//Ajustar el ancho de todas las columnas: https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
foreach ($sheet->getColumnIterator() as $column) {
    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}


//configuro el auto filter
$spreadsheet->getActiveSheet()->setAutoFilter('A7:D7');

//genero repore
$writer = new Xlsx($spreadsheet);
//$writer->save('C:/temp/hello world.xlsx');
$filename = 'RN08_pendientes_'.date("d-m-Y").'.xlsx';
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean(); //https://github.com/PHPOffice/PhpSpreadsheet/issues/217
$writer->save('php://output');
exit();


?>

