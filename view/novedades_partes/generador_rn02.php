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
$cabecera = [
    "Nro. contrato",
    "Mes",
    "Semana",
    "Parte No.",
    "Fecha inicio",
    "Fecha fin",
    "Día semana",
    "Solicitante",
    "No. recurso",
    "Denominación recurso",
    "Personal",
    "Interno",
    "Zona",
    "Lugar",
    "Descripción",
    "Avance %",
    "OT",
    "Cod",
    "Hrs",
    "Cantidad (coeficiente)",
    "Item",
    "Descripción item",
    "VR unitario",
    "VR total",
    "Hs extras",
    "Observaciones",
    "Tiempo de viaje",
    "Tiempo neto reparación",
    "Tiempos varios",
    "Tiempos restantes",
    "Horario inicio",
    "Horario fin",
    "Parte objeto",
    "Síntoma",
    "Causa",
    "Tiempo de parada",
    "Observación de parada",
    "Punto de medida",
    "Observaciones generales"

];
$sheet->fromArray($cabecera, null, 'A8');
$spreadsheet->getActiveSheet()->getStyle('A8:AM8')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A8:AM8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//cuerpo -----------------------------------------------------------------
$fila = 9;
foreach ($view->partes as $p):

    //$sMonth = DateTime::createFromFormat('d/m/Y', $p['fecha_parte'])->format('m');
    $sMonth = substr($p['periodo'], -2);
    //$sFirstDayOfMonth = DateTime::createFromFormat('d/m/Y', $p['fecha_parte'])->format('01/m/Y');
    $sFirstDayOfMonth = DateTime::createFromFormat('d/m/Y', $p['fecha_desde'])->format('d/m/Y');
    $sWeekOfMonth = DateTime::createFromFormat('d/m/Y', $p['fecha_parte'])->format('W') - DateTime::createFromFormat('d/m/Y', $sFirstDayOfMonth)->format('W') + 1;
    $sDayOfWeek = DateTime::createFromFormat('d/m/Y', $p['fecha_parte'])->format('N');
    $horas_extra = ($p['id_evento'] == 1 or $p['id_evento'] == 15)? 'SI' : 'NO'; //horas extra: si tiene GU Guardia activada o JE Jornada extendida
    $area = ($p['area1'])? $p['area1'] : $p['area'] ;
    $orden_nro = ($p['orden_nro']==0 || $p['orden_nro']==1 || $p['orden_nro']=='')? 'Falta OT' : $p['orden_nro'];

    $sheet->setCellValueByColumnAndRow(1, $fila, $p['nro_contrato']);
    $sheet->setCellValueByColumnAndRow(2, $fila, $sMonth);
    $sheet->setCellValueByColumnAndRow(3, $fila, $sWeekOfMonth);
    $sheet->setCellValueByColumnAndRow(4, $fila, $p['nro_parte_diario']);
    $sheet->setCellValueByColumnAndRow(5, $fila, $p['fecha_parte']);
    $sheet->setCellValueByColumnAndRow(6, $fila, ''); //fecha fin
    $sheet->setCellValueByColumnAndRow(7, $fila, $sDayOfWeek);
    $sheet->setCellValueByColumnAndRow(8, $fila, ''); //solicitante
    $sheet->setCellValueByColumnAndRow(9, $fila, $p['nombre_corto_op']);
    $sheet->setCellValueByColumnAndRow(10, $fila, $p['denominacion_recurso']);
    $sheet->setCellValueByColumnAndRow(11, $fila, $p['personal']);
    $sheet->setCellValueByColumnAndRow(12, $fila, $p['nombre_corto']);
    $sheet->setCellValueByColumnAndRow(13, $fila, $area);
    $sheet->setCellValueByColumnAndRow(14, $fila, ''); //lugar
    $sheet->setCellValueByColumnAndRow(15, $fila, ''); //descripcion
    $sheet->setCellValueByColumnAndRow(16, $fila, ''); //avance
    $sheet->setCellValueByColumnAndRow(17, $fila, $orden_nro);
    $sheet->setCellValueByColumnAndRow(18, $fila, ''); //Cod
    $sheet->setCellValueByColumnAndRow(19, $fila, $p['hrs']);
    $sheet->setCellValueByColumnAndRow(20, $fila, ''); //cantidad(coeficiente)
    $sheet->setCellValueByColumnAndRow(21, $fila, $p['item']);
    $sheet->setCellValueByColumnAndRow(22, $fila, $p['denominacion_recurso']); //descripcion item
    $sheet->setCellValueByColumnAndRow(23, $fila, ''); //VR unitario
    $sheet->setCellValueByColumnAndRow(24, $fila, ''); //VR total
    $sheet->setCellValueByColumnAndRow(25, $fila, $horas_extra); //Horas extras
    $sheet->setCellValueByColumnAndRow(26, $fila, $p['evento']); //observaciones
    $sheet->setCellValueByColumnAndRow(27, $fila, ''); //tiempo de viaje
    $sheet->setCellValueByColumnAndRow(28, $fila, ''); //tiempo neto reparacion
    $sheet->setCellValueByColumnAndRow(29, $fila, ''); //tiempos varios
    $sheet->setCellValueByColumnAndRow(30, $fila, ''); //tiempos restantes
    $sheet->setCellValueByColumnAndRow(31, $fila, $p['hora_inicio']);
    $sheet->setCellValueByColumnAndRow(32, $fila, $p['hora_fin']);
    $sheet->setCellValueByColumnAndRow(33, $fila, ''); //parte objeto
    $sheet->setCellValueByColumnAndRow(34, $fila, ''); //sintoma
    $sheet->setCellValueByColumnAndRow(35, $fila, ''); //causa
    $sheet->setCellValueByColumnAndRow(36, $fila, ''); //tiempo de parada
    $sheet->setCellValueByColumnAndRow(37, $fila, ''); //observacion de parada
    $sheet->setCellValueByColumnAndRow(38, $fila, ''); //punto de medida
    $sheet->setCellValueByColumnAndRow(39, $fila, ''); //observaciones generales

    $fila++;
endforeach;


//Ajustar el ancho de todas las columnas: https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
foreach ($sheet->getColumnIterator() as $column) {
    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}



//configuro el auto filter
$spreadsheet->getActiveSheet()->setAutoFilter('A8:AM8');


//genero el reporte
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

