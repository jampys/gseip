<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('resumen');

//titulo ----------------------------------------------------------------

$spreadsheet->getActiveSheet()->mergeCells('A1:F1'); //$spreadsheet->getActiveSheet()->mergeCells("$range1:$range2");
$spreadsheet->getActiveSheet()->mergeCells('A2:F2');
$spreadsheet->getActiveSheet()->mergeCells('A3:F3');
$spreadsheet->getActiveSheet()->mergeCells('A4:F4');
$spreadsheet->getActiveSheet()->getStyle('A1:F4')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A1:F4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

$sheet->setCellValueByColumnAndRow(1, 1, 'RN07 Resumen de actividad de cuadrillas');
$sheet->setCellValueByColumnAndRow(1, 2, 'Contrato/s: '.$encabezado['contratos']);
$sheet->setCellValueByColumnAndRow(1, 3, 'Período: '.$encabezado['periodo']);
$sheet->setCellValueByColumnAndRow(1, 4, 'Fecha emisión: '.$encabezado['fecha_emision']);


//tab 1 encabezados columnas ------------------------------------------------------------
$cabecera = ["Contrato", "Código cuadrilla", "Tipo cuadrilla", "Tipo fact.", "Días hábiles trabajados", "Días hábiles período"];
$sheet->fromArray($cabecera, null, 'A6');
$spreadsheet->getActiveSheet()->getStyle('A6:F6')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A6:F6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//tab 1 cuerpo -----------------------------------------------------------------
$fila = 7;
foreach ($view->resumen as $r):
    //$cuadrilla = $r['cuadrilla'].' ['.$r['nombre_corto_op'].']';

    $sheet->setCellValueByColumnAndRow(1, $fila, $r['contrato']);
    $sheet->setCellValueByColumnAndRow(2, $fila, $r['nombre_corto_op']);
    $sheet->setCellValueByColumnAndRow(3, $fila, $r['cuadrilla']);
    $sheet->setCellValueByColumnAndRow(4, $fila, $r['tipo']);
    $sheet->setCellValueByColumnAndRow(5, $fila, $r['dht']);
    $sheet->setCellValueByColumnAndRow(6, $fila, $r['dh']);
    $fila++;
endforeach;

//tab 1 Ajustar el ancho de todas las columnas: https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
/*foreach ($sheet->getColumnIterator() as $column) {
    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}*/

$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);

$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12); //codigo cuadrilla
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(12); //dht
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(12); //dh

$spreadsheet->getActiveSheet()->getStyle('B6')->getAlignment()->setWrapText(true); //codigo cuadrilla
$spreadsheet->getActiveSheet()->getStyle('E6')->getAlignment()->setWrapText(true); //dht
$spreadsheet->getActiveSheet()->getStyle('F6')->getAlignment()->setWrapText(true); //dh

/* Alinear a la izquierda columna B */
$spreadsheet->getActiveSheet()->getStyle('B7:B300')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

/* ajuste fila de contratos del encabezado*/
$spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
$spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);


//tab 1 configuro el auto filter
$spreadsheet->getActiveSheet()->setAutoFilter('A6:E6');


//-----------------generacion de excel ------------------------------------------------
$writer = new Xlsx($spreadsheet);
//$writer->save('C:/temp/hello world.xlsx');
$filename = 'RN07_resumen_'.date("d-m-Y").'.xlsx';
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean(); //https://github.com/PHPOffice/PhpSpreadsheet/issues/217
$writer->save('php://output');
exit();


?>

