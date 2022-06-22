<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('vencimientos');

//titulo ----------------------------------------------------------------

$spreadsheet->getActiveSheet()->mergeCells('A1:F1'); //$spreadsheet->getActiveSheet()->mergeCells("$range1:$range2");
$spreadsheet->getActiveSheet()->mergeCells('A2:F2');
$spreadsheet->getActiveSheet()->mergeCells('A3:F3');
$spreadsheet->getActiveSheet()->mergeCells('A4:F4');
$spreadsheet->getActiveSheet()->mergeCells('A5:F5');
$spreadsheet->getActiveSheet()->mergeCells('A6:F6');
$spreadsheet->getActiveSheet()->mergeCells('A7:F7');
$spreadsheet->getActiveSheet()->getStyle('A1:F7')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A1:F7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

$sheet->setCellValueByColumnAndRow(1, 1, 'RV01 Reporte de vencimientos de personal');
$sheet->setCellValueByColumnAndRow(1, 2, 'Contrato: '.$encabezado['contrato']);
$sheet->setCellValueByColumnAndRow(1, 3, 'Vencimiento/s: '.$encabezado['vencimientos']);
$sheet->setCellValueByColumnAndRow(1, 4, 'Empleado: '.$encabezado['empleado']);
$sheet->setCellValueByColumnAndRow(1, 5, 'Grupo: '.$encabezado['grupo']);
$sheet->setCellValueByColumnAndRow(1, 6, 'Subcontratista: '.$encabezado['subcontratista']);
$sheet->setCellValueByColumnAndRow(1, 7, 'Fecha emisión: '.$encabezado['fecha_emision']);


//encabezado ------------------------------------------------------------
$cabecera = [
    "Nro. vto",
    "Vencimiento",
    "Empleado / grupo",
    "F. emisión",
    "F. vto.",
    "Nro. renovación"
];
$sheet->fromArray($cabecera, null, 'A9');
$spreadsheet->getActiveSheet()->getStyle('A9:F9')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A9:F9')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//cuerpo -----------------------------------------------------------------
$fila = 10;
foreach ($view->vencimientos as $p):

    //$sMonth = DateTime::createFromFormat('d/m/Y', $p['fecha_parte'])->format('m');
    //$sMonth = substr($p['periodo'], -2);
    //$sFirstDayOfMonth = DateTime::createFromFormat('d/m/Y', $p['fecha_parte'])->format('01/m/Y');
    //$horas_extra = ($p['id_evento'] == 1 or $p['id_evento'] == 15)? 'SI' : 'NO'; //horas extra: si tiene GU Guardia activada o JE Jornada extendida
    //$area = ($p['area1'])? $p['area1'] : $p['area'] ;
    //$orden_nro = ($p['orden_nro']==0 || $p['orden_nro']==1 || $p['orden_nro']=='')? 'Falta OT' : $p['orden_nro'];

    $emp_gru = ($p['empleado'])? $p['empleado'] : $p['grupo'];

    $sheet->setCellValueByColumnAndRow(1, $fila, $p['id_renovacion']);
    $sheet->setCellValueByColumnAndRow(2, $fila, $p['vencimiento']);
    $sheet->setCellValueByColumnAndRow(3, $fila, $emp_gru);
    $sheet->setCellValueByColumnAndRow(4, $fila, $p['fecha_emision']);
    $sheet->setCellValueByColumnAndRow(5, $fila, $p['fecha_vencimiento']);
    $sheet->setCellValueByColumnAndRow(6, $fila, $p['id_rnv_renovacion']);

    $spreadsheet->getActiveSheet()->getStyle('A'.$fila.':F'.$fila)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //->getStartColor()->setARGB('b3b300');
        ->getStartColor()->setARGB(substr($p['color'], 1, 6));

    $fila++;
endforeach;


//Ajustar el ancho de todas las columnas: https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
foreach ($sheet->getColumnIterator() as $column) {
    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}



//configuro el auto filter
$spreadsheet->getActiveSheet()->setAutoFilter('A9:F9');


//genero el reporte
$writer = new Xlsx($spreadsheet);
//$writer->save('C:/temp/hello world.xlsx');
$filename = 'RV01_vencimientos_personal'.date("d-m-Y").'.xlsx';
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean(); //https://github.com/PHPOffice/PhpSpreadsheet/issues/217
$writer->save('php://output');
exit();


?>

