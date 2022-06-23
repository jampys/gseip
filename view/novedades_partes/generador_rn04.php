<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


//tab 1 declaracion
$spreadsheet = new Spreadsheet();
$sheet1 = $spreadsheet->getActiveSheet();
$sheet1->setTitle('Resumen');


//tab 1 encabezado  ----------------------------------------------------------------
$spreadsheet->getActiveSheet()->mergeCells('A1:D1'); //$spreadsheet->getActiveSheet()->mergeCells("$range1:$range2");
$spreadsheet->getActiveSheet()->mergeCells('A2:D2');
$spreadsheet->getActiveSheet()->mergeCells('A3:D3');
$spreadsheet->getActiveSheet()->mergeCells('A4:D4');
$spreadsheet->getActiveSheet()->mergeCells('A5:D5');
$spreadsheet->getActiveSheet()->getStyle('A1:D5')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A1:D5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

$sheet1->setCellValueByColumnAndRow(1, 1, 'Cliente: '.$encabezado['cliente']);
$sheet1->setCellValueByColumnAndRow(1, 2, 'Contrato: '.$encabezado['contrato']);
$sheet1->setCellValueByColumnAndRow(1, 3, 'Período: '.$encabezado['periodo']);
$sheet1->setCellValueByColumnAndRow(1, 4, 'Días hábiles del período (desde el inicio a hoy): '.$encabezado['dh1']);
$sheet1->setCellValueByColumnAndRow(1, 5, 'Fecha emisión: '.$encabezado['fecha_emision']);

//tab 1 encabezados columnas ------------------------------------------------------------
$cabecera = ["Cuadrilla", "Tipo fact.", "Días hábiles trabajados", "Días hábiles esperados"];
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



// tab 2 declaracion -------------------------------------------------------------
$spreadsheet->createSheet();
$sheet = $spreadsheet->setActiveSheetIndex(1);
$sheet->setTitle('Detalle diario');

//tab 2 encabezado  ----------------------------------------------------------------

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


//tab 2 encabezados columnas ------------------------------------------------------------
$cabecera = ["Día semana", "Fecha", "Cuadrilla", "IN", "Área", "Evento", "Conductor", "Acompañante", "Trabajado hábil", "Trabajado no hábil", "Hs Normal", "Hs 50%", "Hs 100%", "Detalle de la novedad"];
$sheet->fromArray($cabecera, null, 'A7');
$spreadsheet->getActiveSheet()->getStyle('A7:N7')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A7:N7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//tab 2 cuerpo -----------------------------------------------------------------
$fila = 8;
foreach ($view->partes as $p):

    $trabajado_habil = ($p['trabajado'] == 1 && in_array($p['dia_numero'], array(2, 3, 4, 5, 6)) && $p['feriado'] == '')? 1 : "";
    $trabajado_no_habil = ($p['trabajado'] == 1 && (in_array($p['dia_numero'], array(1, 7)) || $p['feriado'] != ''))? 1 : "";
    $cuadrilla = $p['cuadrilla'].' ['.$p['nombre_corto_op'].']';

    $sheet->setCellValueByColumnAndRow(1, $fila, $p['dia']);
    $sheet->setCellValueByColumnAndRow(2, $fila, $p['fecha']);
    $sheet->setCellValueByColumnAndRow(3, $fila, $cuadrilla);
    $sheet->setCellValueByColumnAndRow(4, $fila, $p['id_parte']);
    $sheet->setCellValueByColumnAndRow(5, $fila, $p['area']);
    $sheet->setCellValueByColumnAndRow(6, $fila, $p['evento']);
    $sheet->setCellValueByColumnAndRow(7, $fila, $p['conductor']);
    $sheet->setCellValueByColumnAndRow(8, $fila, $p['acompañante']);
    $sheet->setCellValueByColumnAndRow(9, $fila, $trabajado_habil);
    $sheet->setCellValueByColumnAndRow(10, $fila, $trabajado_no_habil);
    $sheet->setCellValueByColumnAndRow(11, $fila, $p['hs_normal']);
    $sheet->setCellValueByColumnAndRow(12, $fila, $p['hs_50']);
    $sheet->setCellValueByColumnAndRow(13, $fila, $p['hs_100']);
    $sheet->setCellValueByColumnAndRow(14, $fila, $p['detalle']);


    $fila++;
endforeach;


//tab 2 Ajustar el ancho de todas las columnas: https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
foreach ($sheet->getColumnIterator() as $column) {
    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}

//tab 2 configuro el auto filter
$spreadsheet->getActiveSheet()->setAutoFilter('A7:N7');


//tab 1: pone como activa la tab 1
$spreadsheet->setActiveSheetIndex(0); //

//-----------------generacion de excel ------------------------------------------------
$writer = new Xlsx($spreadsheet);
//$writer->save('C:/temp/hello world.xlsx');
$filename = 'RN04_control_insp_'.$encabezado["contrato"].'.xlsx';
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean(); //https://github.com/PHPOffice/PhpSpreadsheet/issues/217
$writer->save('php://output');
exit();


?>

