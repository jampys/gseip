<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('partes');

//titulo ----------------------------------------------------------------

$spreadsheet->getActiveSheet()->mergeCells('A1:F1'); //$spreadsheet->getActiveSheet()->mergeCells("$range1:$range2");
$spreadsheet->getActiveSheet()->mergeCells('A2:F2');
$spreadsheet->getActiveSheet()->mergeCells('A3:F3');
$spreadsheet->getActiveSheet()->mergeCells('A4:F4');
$spreadsheet->getActiveSheet()->mergeCells('A5:F5');
$spreadsheet->getActiveSheet()->getStyle('A1:F5')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A1:F5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e6e6e6');

$sheet->setCellValueByColumnAndRow(1, 1, 'Cliente: '.$encabezado['cliente']);
$sheet->setCellValueByColumnAndRow(1, 2, 'Contrato: '.$encabezado['contrato']);
$sheet->setCellValueByColumnAndRow(1, 3, 'Cuadrilla: '.$encabezado['cuadrilla']);
$sheet->setCellValueByColumnAndRow(1, 4, 'Fecha desde - hasta: '.$encabezado['fecha_desde'].' - '.$encabezado['fecha_hasta']);
$sheet->setCellValueByColumnAndRow(1, 5, 'Fecha emisión: '.$encabezado['fecha_emision']);


//encabezado ------------------------------------------------------------
$encabezado = ["Fecha parte", "Cuadrilla", "Área", "Evento", "Nro. parte", "Nro. OT"];
$sheet->fromArray($encabezado, null, 'A7');
$spreadsheet->getActiveSheet()->getStyle('A7:F7')->getFont()->setBold(true);

//cuerpo -----------------------------------------------------------------
$fila = 8;
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

