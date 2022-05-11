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
            "Accidente",
            "Injustificadas",
            "Permiso días trámite",
            "Permiso gremial",
            "Resto Permisos",
            "Vacaciones",
            "Suspención",
            "Mayor Función",
            "DD Háb. período",
            "DRT período",
            "DHT período",
            "DHNT período",
            "DCNT período",
            "DH calendario",
            "Observaciones"
        ];
$sheet->fromArray($cabecera, null, 'A8');
$spreadsheet->getActiveSheet()->getStyle('A8:Z8')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A8:Z8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//cuerpo -----------------------------------------------------------------
$fila = 9;
foreach ($view->partes as $p):

    $empleado = $p['legajo'].' '.$p['apellido'].' '.$p['nombre'];

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
    $sheet->setCellValueByColumnAndRow(13, $fila, $p['injustificadas']);
    $sheet->setCellValueByColumnAndRow(14, $fila, $p['permiso_dias_tramite']);
    $sheet->setCellValueByColumnAndRow(15, $fila, $p['permiso_gremial']);
    $sheet->setCellValueByColumnAndRow(16, $fila, $p['resto_permisos']);
    $sheet->setCellValueByColumnAndRow(17, $fila, $p['vacaciones']);
    $sheet->setCellValueByColumnAndRow(18, $fila, $p['suspenciones']);
    $sheet->setCellValueByColumnAndRow(19, $fila, $p['mayor_funcion']);
    $sheet->setCellValueByColumnAndRow(20, $fila, $p['DHDD']);
    $sheet->setCellValueByColumnAndRow(21, $fila, $p['DRT']);
    $sheet->setCellValueByColumnAndRow(22, $fila, $p['DHT']);
    $sheet->setCellValueByColumnAndRow(23, $fila, $p['DHNT']);
    $sheet->setCellValueByColumnAndRow(24, $fila, $p['DCNT']);
    $sheet->setCellValueByColumnAndRow(25, $fila, $p['DH']);
    $sheet->setCellValueByColumnAndRow(26, $fila, $p['mayor_funcion_m']);

    $fila++;
endforeach;


//Ajustar el ancho de todas las columnas:
/*foreach ($sheet->getColumnIterator() as $column) {
    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}*/

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('X')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('Y')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setWidth(30);


$spreadsheet->getActiveSheet()->getStyle('B8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('C8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('D8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('E8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('F8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('G8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('H8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('I8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('J8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('K8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('L8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('M8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('N8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('O8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('P8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('Q8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('R8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('S8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('T8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('U8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('V8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('W8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('X8')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('Y8')->getAlignment()->setTextRotation(90);


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

