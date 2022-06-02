<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('resumen');

//titulo ----------------------------------------------------------------

$spreadsheet->getActiveSheet()->mergeCells('A1:E1'); //$spreadsheet->getActiveSheet()->mergeCells("$range1:$range2");
$spreadsheet->getActiveSheet()->mergeCells('A2:E2');
$spreadsheet->getActiveSheet()->mergeCells('A3:E3');
$spreadsheet->getActiveSheet()->mergeCells('A4:E4');
$spreadsheet->getActiveSheet()->getStyle('A1:E4')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A1:E4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

$sheet->setCellValueByColumnAndRow(1, 1, 'RN07 Resumen de actividad de cuadrillas');
$sheet->setCellValueByColumnAndRow(1, 2, 'Contrato/s: '.$encabezado['contratos']);
$sheet->setCellValueByColumnAndRow(1, 3, 'Período: '.$encabezado['periodo']);
$sheet->setCellValueByColumnAndRow(1, 4, 'Fecha emisión: '.$encabezado['fecha_emision']);


//tab 1 encabezados columnas ------------------------------------------------------------
$cabecera = ["Contrato", "Cuadrilla", "Tipo fact.", "Días hábiles trabajados", "Días hábiles período"];
$sheet->fromArray($cabecera, null, 'A7');
$spreadsheet->getActiveSheet()->getStyle('A7:E7')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A7:E7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//tab 1 cuerpo -----------------------------------------------------------------
$fila = 8;
foreach ($view->resumen as $r):
    //$cuadrilla = $r['cuadrilla'].' ['.$r['nombre_corto_op'].']';

    $sheet->setCellValueByColumnAndRow(1, $fila, $r['contrato']);
    $sheet->setCellValueByColumnAndRow(2, $fila, $r['cuadrilla']);
    $sheet->setCellValueByColumnAndRow(3, $fila, $r['tipo']);
    $sheet->setCellValueByColumnAndRow(4, $fila, $r['dht']);
    $sheet->setCellValueByColumnAndRow(5, $fila, $r['dh']);
    $fila++;
endforeach;

//tab 1 Ajustar el ancho de todas las columnas: https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
foreach ($sheet->getColumnIterator() as $column) {
    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}


//tab 1 configuro el auto filter
$spreadsheet->getActiveSheet()->setAutoFilter('A7:E7');


//-----------------generacion de excel ------------------------------------------------
$writer = new Xlsx($spreadsheet);
//$writer->save('C:/temp/hello world.xlsx');
$filename = 'RN07_resumen_'.$id_contrato.'.xlsx';
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean(); //https://github.com/PHPOffice/PhpSpreadsheet/issues/217
$writer->save('php://output');
exit();


?>

