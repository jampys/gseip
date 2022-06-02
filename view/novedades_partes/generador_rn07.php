<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('resumen');

//titulo ----------------------------------------------------------------

$spreadsheet->getActiveSheet()->mergeCells('A1:R1'); //$spreadsheet->getActiveSheet()->mergeCells("$range1:$range2");
$spreadsheet->getActiveSheet()->mergeCells('A2:R2');
$spreadsheet->getActiveSheet()->mergeCells('A3:R3');
$spreadsheet->getActiveSheet()->mergeCells('A4:R4');
$spreadsheet->getActiveSheet()->getStyle('A1:R4')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A1:R4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

$sheet->setCellValueByColumnAndRow(1, 1, 'Cliente: '.$encabezado['cliente']);
$sheet->setCellValueByColumnAndRow(1, 2, 'Contrato/s: '.$encabezado['contratos']);
$sheet->setCellValueByColumnAndRow(1, 3, 'Período: '.$encabezado['periodo']);
$sheet->setCellValueByColumnAndRow(1, 4, 'Fecha emisión: '.$encabezado['fecha_emision']);


//tab 1 encabezados columnas ------------------------------------------------------------
$cabecera = ["Cuadrilla", "Tipo fact.", "Días hábiles trabajados", "Días habiles esperados"];
$sheet1->fromArray($cabecera, null, 'A7');
$spreadsheet->getActiveSheet()->getStyle('A7:D7')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A7:D7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//tab 1 cuerpo -----------------------------------------------------------------
$fila = 8;
foreach ($view->resumen as $r):
    //$cuadrilla = $r['cuadrilla'].' ['.$r['nombre_corto_op'].']';

    $sheet1->setCellValueByColumnAndRow(1, $fila, $r['cuadrilla']);
    $sheet1->setCellValueByColumnAndRow(2, $fila, $r['tipo']);
    $sheet1->setCellValueByColumnAndRow(3, $fila, $r['dht']);
    $sheet1->setCellValueByColumnAndRow(4, $fila, $encabezado['dh1']);
    $fila++;
endforeach;

//tab 1 Ajustar el ancho de todas las columnas: https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
foreach ($sheet1->getColumnIterator() as $column) {
    $sheet1->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}


//tab 1 configuro el auto filter
$spreadsheet->getActiveSheet()->setAutoFilter('A7:N7');


//-----------------generacion de excel ------------------------------------------------
$writer = new Xlsx($spreadsheet);
//$writer->save('C:/temp/hello world.xlsx');
$filename = 'RN07_resumen_'.$encabezado["template"].'_'.$id_contrato.'.xlsx';
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean(); //https://github.com/PHPOffice/PhpSpreadsheet/issues/217
$writer->save('php://output');
exit();


?>

