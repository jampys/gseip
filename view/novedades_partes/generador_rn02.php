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
$spreadsheet->getActiveSheet()->mergeCells('A6:F6');
$spreadsheet->getActiveSheet()->getStyle('A1:F6')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A1:F6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

$sheet->setCellValueByColumnAndRow(1, 1, 'RN02 Reporte de actividad de cuadrillas');
$sheet->setCellValueByColumnAndRow(1, 2, 'Cliente: '.$encabezado['cliente']);
$sheet->setCellValueByColumnAndRow(1, 3, 'Contrato: '.$encabezado['contrato']);
$sheet->setCellValueByColumnAndRow(1, 4, 'Cuadrilla: '.$encabezado['cuadrilla']);
$sheet->setCellValueByColumnAndRow(1, 5, 'Fecha desde - hasta: '.$encabezado['fecha_desde'].' - '.$encabezado['fecha_hasta']);
$sheet->setCellValueByColumnAndRow(1, 6, 'Fecha emisión: '.$encabezado['fecha_emision']);


//encabezado ------------------------------------------------------------
$cabecera = ["Fecha parte", "Cuadrilla", "Área", "Evento", "Nro. parte", "Nro. OT", "Nro. contrato", "Mes", "Semana", "Día semana", "Nro. recurso", "Denominación recurso", "Personal", "Interno", "Hrs", "Item", "Horario inicio", "Horario fin"];
$sheet->fromArray($cabecera, null, 'A8');
$spreadsheet->getActiveSheet()->getStyle('A8:R8')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A8:R8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//cuerpo -----------------------------------------------------------------
$fila = 9;
foreach ($view->partes as $p):

    $sMonth = DateTime::createFromFormat('d/m/Y', $p['fecha_parte'])->format('m');
    $sFirstDayOfMonth = DateTime::createFromFormat('d/m/Y', $p['fecha_parte'])->format('01/m/Y');
    $sWeekOfMonth = DateTime::createFromFormat('d/m/Y', $p['fecha_parte'])->format('W') - DateTime::createFromFormat('d/m/Y', $sFirstDayOfMonth)->format('W') + 1;
    $sDayOfWeek = DateTime::createFromFormat('d/m/Y', $p['fecha_parte'])->format('N');


    $sheet->setCellValueByColumnAndRow(1, $fila, $p['fecha_parte']);
    $sheet->setCellValueByColumnAndRow(2, $fila, $p['nombre_corto_op']);
    $sheet->setCellValueByColumnAndRow(3, $fila, $p['area']);
    $sheet->setCellValueByColumnAndRow(4, $fila, $p['evento']);
    $sheet->setCellValueByColumnAndRow(5, $fila, $p['nro_parte_diario']);
    $sheet->setCellValueByColumnAndRow(6, $fila, $p['orden_nro']);
    $sheet->setCellValueByColumnAndRow(7, $fila, $p['nro_contrato']);
    $sheet->setCellValueByColumnAndRow(8, $fila, $sMonth);
    $sheet->setCellValueByColumnAndRow(9, $fila, $sWeekOfMonth);
    $sheet->setCellValueByColumnAndRow(10, $fila, $sDayOfWeek);
    $sheet->setCellValueByColumnAndRow(11, $fila, $p['nombre_corto_op']);
    $sheet->setCellValueByColumnAndRow(12, $fila, $p['denominacion_recurso']);
    $sheet->setCellValueByColumnAndRow(13, $fila, $p['personal']);
    $sheet->setCellValueByColumnAndRow(14, $fila, $p['nombre_corto']);
    $sheet->setCellValueByColumnAndRow(15, $fila, $p['hrs']);
    $sheet->setCellValueByColumnAndRow(16, $fila, $p['item']);
    $sheet->setCellValueByColumnAndRow(17, $fila, $p['hora_inicio']);
    $sheet->setCellValueByColumnAndRow(18, $fila, $p['hora_fin']);

    $fila++;
endforeach;


//Ajustar el ancho de todas las columnas: https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
foreach ($sheet->getColumnIterator() as $column) {
    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}


//-----------------------------------------------------------------

$writer = new Xlsx($spreadsheet);
//$writer->save('C:/temp/hello world.xlsx');
$filename = 'RN02_'.$encabezado["contrato"].'_'.$_GET['fecha_desde'].'_'.$_GET['fecha_hasta'].'.xlsx';
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean(); //https://github.com/PHPOffice/PhpSpreadsheet/issues/217
$writer->save('php://output');
exit();


?>

