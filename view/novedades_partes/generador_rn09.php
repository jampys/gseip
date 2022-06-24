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
$sheet->setCellValueByColumnAndRow(1, 3, 'Empleado: '.$encabezado['empleado']);
$sheet->setCellValueByColumnAndRow(1, 4, 'Período: '.$encabezado['periodo']);
$sheet->setCellValueByColumnAndRow(1, 5, 'Fecha emisión: '.$encabezado['fecha_emision']);


//encabezado ------------------------------------------------------------
$cabecera = ["IN", "Fecha", "Cuadrilla", "Área", "Evento", "Nro. parte diario", "Tipo Órden", "Nro. Órden", "Conductor", "Acompañante", "Habilitas", "Comentarios"];
$sheet->fromArray($cabecera, null, 'A7');
$spreadsheet->getActiveSheet()->getStyle('A7:L7')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A7:L7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//cuerpo -----------------------------------------------------------------
$fila = 8;
foreach ($view->partes as $p):

    $orden_nro = ($p['cant_ots'] > 1)? $p['orden_nro'].' ('.$p['cant_ots'].')' : $p['orden_nro'];

    $sheet->setCellValueByColumnAndRow(1, $fila, $p['id_parte']);
    $sheet->setCellValueByColumnAndRow(2, $fila, $p['fecha_parte']);
    $sheet->setCellValueByColumnAndRow(3, $fila, $p['cuadrilla']);
    $sheet->setCellValueByColumnAndRow(4, $fila, $p['area']);
    $sheet->setCellValueByColumnAndRow(5, $fila, $p['evento']);
    $sheet->setCellValueByColumnAndRow(6, $fila, $p['nro_parte_diario']);
    $sheet->setCellValueByColumnAndRow(7, $fila, $p['orden_tipo']);
    $sheet->setCellValueByColumnAndRow(8, $fila, $p['orden_nro']);
    $sheet->setCellValueByColumnAndRow(9, $fila, $p['conductor']);
    $sheet->setCellValueByColumnAndRow(10, $fila, $p['acompañante']);
    $sheet->setCellValueByColumnAndRow(11, $fila, $p['habilitas']);
    $sheet->setCellValueByColumnAndRow(12, $fila, $p['comentarios']);

    if($p['orden_nro'] && !$p['habilitas']) {
        $spreadsheet->getActiveSheet()->getStyle('A' . $fila . ':L' . $fila)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCCCC');
    }

    $fila++;
endforeach;


//Ajustar el ancho de todas las columnas: https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
foreach ($sheet->getColumnIterator() as $column) {
    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}


//configuro el auto filter
$spreadsheet->getActiveSheet()->setAutoFilter('A7:L7');

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

