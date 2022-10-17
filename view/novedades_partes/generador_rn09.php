<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('partes');

//titulo ----------------------------------------------------------------

$spreadsheet->getActiveSheet()->mergeCells('A1:E1'); //$spreadsheet->getActiveSheet()->mergeCells("$range1:$range2");
$spreadsheet->getActiveSheet()->mergeCells('A2:E2');
$spreadsheet->getActiveSheet()->mergeCells('A3:E3');
$spreadsheet->getActiveSheet()->mergeCells('A4:E4');
$spreadsheet->getActiveSheet()->mergeCells('A5:E5');
$spreadsheet->getActiveSheet()->mergeCells('A6:E6');
$spreadsheet->getActiveSheet()->getStyle('A1:E6')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A1:E6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

$sheet->setCellValueByColumnAndRow(1, 1, 'RN09 Control de partes y habilitas');
$sheet->setCellValueByColumnAndRow(1, 2, 'Cliente: '.$encabezado['cliente']);
$sheet->setCellValueByColumnAndRow(1, 3, 'Contrato: '.$encabezado['contrato']);
$sheet->setCellValueByColumnAndRow(1, 4, 'Empleado: '.$encabezado['empleado']);
$sheet->setCellValueByColumnAndRow(1, 5, 'Período: '.$encabezado['periodo']);
$sheet->setCellValueByColumnAndRow(1, 6, 'Fecha emisión: '.$encabezado['fecha_emision']);


//encabezado ------------------------------------------------------------
$cabecera = ["IN", "Fecha", "Cuadrilla", "Área", "Evento", "Nro. parte diario", "Tipo Órden", "Nro. Órden", "Cant. rep.", "Conductor", "Acompañante", "Habilitas", "Comentarios"];
$sheet->fromArray($cabecera, null, 'A8');
$spreadsheet->getActiveSheet()->getStyle('A8:M8')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A8:M8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//cuerpo -----------------------------------------------------------------
$fila = 9;
foreach ($view->partes as $p):

    $sheet->setCellValueByColumnAndRow(1, $fila, $p['id_parte']);
    $sheet->setCellValueByColumnAndRow(2, $fila, $p['fecha_parte']);
    $sheet->setCellValueByColumnAndRow(3, $fila, $p['cuadrilla']);
    $sheet->setCellValueByColumnAndRow(4, $fila, $p['area']);
    $sheet->setCellValueByColumnAndRow(5, $fila, $p['evento']);
    $sheet->setCellValueByColumnAndRow(6, $fila, $p['nro_parte_diario']);
    $sheet->setCellValueByColumnAndRow(7, $fila, $p['orden_tipo']);
    $sheet->setCellValueByColumnAndRow(8, $fila, $p['orden_nro']);
    $sheet->setCellValueByColumnAndRow(9, $fila, $p['cant_ots']);
    $sheet->setCellValueByColumnAndRow(10, $fila, $p['conductor']);
    $sheet->setCellValueByColumnAndRow(11, $fila, $p['acompañante']);
    $sheet->setCellValueByColumnAndRow(12, $fila, $p['habilitas']);
    $sheet->setCellValueByColumnAndRow(13, $fila, $p['comentarios']);

    if($p['orden_nro'] && !$p['habilitas']) {
        $spreadsheet->getActiveSheet()->getStyle('A' . $fila . ':M' . $fila)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCCCC');
    }

    if($p['cant_ots'] > 1) {
        $spreadsheet->getActiveSheet()->getStyle('H' . $fila . ':I' . $fila)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('C8BDE8');
    }

    $fila++;
endforeach;


//Ajustar el ancho de todas las columnas: https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
foreach ($sheet->getColumnIterator() as $column) {
    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}

//configuro el auto filter
$spreadsheet->getActiveSheet()->setAutoFilter('A8:M8');

/* comentarios en las celdas necesarias */
$spreadsheet->getActiveSheet()->getComment('I8')->getText()->createTextRun('Cantidad de repeticiones que hay de la OT en partes. Debería ser siempre 1.');

//genero repore
$writer = new Xlsx($spreadsheet);
//$writer->save('C:/temp/hello world.xlsx');
$filename = 'RN09_partes_'.date("d-m-Y").'.xlsx';
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean(); //https://github.com/PHPOffice/PhpSpreadsheet/issues/217
$writer->save('php://output');
exit();


?>

