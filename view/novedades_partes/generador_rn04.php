<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('partes');

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
$sheet->setCellValueByColumnAndRow(1, 3, 'Período: '.$encabezado['periodo']);
$sheet->setCellValueByColumnAndRow(1, 4, 'Días hábiles del período: '.$encabezado['dh']);
$sheet->setCellValueByColumnAndRow(1, 5, 'Fecha emisión: '.$encabezado['fecha_emision']);


//encabezado ------------------------------------------------------------
$cabecera = ["Día semana", "Fecha", "Cuadrilla", "IN", "Área", "Evento", "Empleados", "Trabajado hábil", "Trabajado no hábil", "Hs Normal", "Hs 50%", "Hs 100%", "Detalle de la novedad"];
$sheet->fromArray($cabecera, null, 'A7');
$spreadsheet->getActiveSheet()->getStyle('A7:M7')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A7:M7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//cuerpo -----------------------------------------------------------------
$fila = 8;
foreach ($view->partes as $p):

    $trabajado_habil = ($p['trabajado'] == 1 && in_array($p['dia_numero'], array(2, 3, 4, 5, 6)))? 1 : "";
    $trabajado_no_habil = ($p['trabajado'] == 1 && in_array($p['dia_numero'], array(1, 7)))? 1 : "";

    $sheet->setCellValueByColumnAndRow(1, $fila, $p['dia']);
    $sheet->setCellValueByColumnAndRow(2, $fila, $p['fecha']);
    $sheet->setCellValueByColumnAndRow(3, $fila, $p['cuadrilla']);
    $sheet->setCellValueByColumnAndRow(4, $fila, $p['id_parte']);
    $sheet->setCellValueByColumnAndRow(5, $fila, $p['area']);
    $sheet->setCellValueByColumnAndRow(6, $fila, $p['evento']);
    $sheet->setCellValueByColumnAndRow(7, $fila, $p['empleados']);
    $sheet->setCellValueByColumnAndRow(8, $fila, $trabajado_habil);
    $sheet->setCellValueByColumnAndRow(9, $fila, $trabajado_no_habil);
    $sheet->setCellValueByColumnAndRow(10, $fila, $p['hs_normal']);
    $sheet->setCellValueByColumnAndRow(11, $fila, $p['hs_50']);
    $sheet->setCellValueByColumnAndRow(12, $fila, $p['hs_100']);
    $sheet->setCellValueByColumnAndRow(13, $fila, $p['detalle']);


    $fila++;
endforeach;


//Ajustar el ancho de todas las columnas: https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
foreach ($sheet->getColumnIterator() as $column) {
    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}


//-----------------------------------------------------------------

$writer = new Xlsx($spreadsheet);
//$writer->save('C:/temp/hello world.xlsx');
$filename = 'RN4_conceptos_'.$encabezado["contrato"].'.xlsx';
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean(); //https://github.com/PHPOffice/PhpSpreadsheet/issues/217
$writer->save('php://output');
exit();


?>

