<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('resumen');

//titulo ----------------------------------------------------------------

$spreadsheet->getActiveSheet()->mergeCells('A1:D1'); //$spreadsheet->getActiveSheet()->mergeCells("$range1:$range2");
$spreadsheet->getActiveSheet()->mergeCells('A2:D2');
$spreadsheet->getActiveSheet()->mergeCells('A3:D3');
$spreadsheet->getActiveSheet()->mergeCells('A4:D4');
$spreadsheet->getActiveSheet()->mergeCells('A5:D5');
$spreadsheet->getActiveSheet()->mergeCells('A6:D6');
$spreadsheet->getActiveSheet()->getStyle('A1:D6')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A1:D6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

$sheet->setCellValueByColumnAndRow(1, 1, 'Cliente: '.$encabezado['cliente']);
$sheet->setCellValueByColumnAndRow(1, 2, 'Contrato: '.$encabezado['contrato']);
$sheet->setCellValueByColumnAndRow(1, 3, 'Empleado: '.$encabezado['empleado']);
$sheet->setCellValueByColumnAndRow(1, 4, 'Concepto: '.$encabezado['concepto']);
$sheet->setCellValueByColumnAndRow(1, 5, 'Período: '.$encabezado['periodo']);
$sheet->setCellValueByColumnAndRow(1, 6, 'Fecha emisión: '.$encabezado['fecha_emision']);


//encabezado ------------------------------------------------------------
$cabecera = ["Empleado",
            "Guardias",
            "Hs Extras 50",
            "Hs Extras 50 Manejo",
            "Total Hs Extras 50",
            "Hs Extras 100",
            "Hs Base",
            "Hs Viaje",
            "Total Hs viaje",
            "Viandas Extra",
            "Enfermedad",
            "Accidente"
        ];
$sheet->fromArray($cabecera, null, 'A8');
$spreadsheet->getActiveSheet()->getStyle('A8:L8')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A8:L8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//cuerpo -----------------------------------------------------------------
$fila = 9;
foreach ($view->partes as $p):

    $empleado = $p['legajo'].' '.$p['apellido'].' '.$p['legajo'];

    $sheet->setCellValueByColumnAndRow(1, $fila, $empleado);
    $sheet->setCellValueByColumnAndRow(2, $fila, $p['guardias']);
    $sheet->setCellValueByColumnAndRow(3, $fila, $p['hs_extras_50']);
    $sheet->setCellValueByColumnAndRow(4, $fila, $p['hs_extras_50_manejo']);
    $sheet->setCellValueByColumnAndRow(5, $fila, $p['total_hs_extras_50']);
    $sheet->setCellValueByColumnAndRow(6, $fila, $p['hs_extras_100']);
    $sheet->setCellValueByColumnAndRow(7, $fila, $p['hs_base']);
    $sheet->setCellValueByColumnAndRow(8, $fila, $p['hs_viaje']);
    $sheet->setCellValueByColumnAndRow(9, $fila, $p['total_hs_viaje']);
    $sheet->setCellValueByColumnAndRow(10, $fila, $p['viandas_extra']);
    $sheet->setCellValueByColumnAndRow(11, $fila, $p['enfermedad']);
    $sheet->setCellValueByColumnAndRow(12, $fila, $p['accidente']);

    $fila++;
endforeach;


//Ajustar el ancho de todas las columnas: https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
foreach ($sheet->getColumnIterator() as $column) {
    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}


//-----------------------------------------------------------------

$writer = new Xlsx($spreadsheet);
//$writer->save('C:/temp/hello world.xlsx');
$filename = 'RN3_administracion_'.$encabezado["contrato"].'.xlsx';
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean(); //https://github.com/PHPOffice/PhpSpreadsheet/issues/217
$writer->save('php://output');
exit();


?>

