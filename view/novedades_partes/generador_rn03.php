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
$spreadsheet->getActiveSheet()->mergeCells('A5:R5');
$spreadsheet->getActiveSheet()->getStyle('A1:R5')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A1:R5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

$sheet->setCellValueByColumnAndRow(1, 1, 'RN03 Control de Novedades Administración');
$sheet->setCellValueByColumnAndRow(1, 2, 'Cliente: '.$encabezado['cliente']);
$sheet->setCellValueByColumnAndRow(1, 3, 'Contrato/s: '.$encabezado['contratos']);
$sheet->setCellValueByColumnAndRow(1, 4, 'Período: '.$encabezado['periodo']);
$sheet->setCellValueByColumnAndRow(1, 5, 'Fecha emisión: '.$encabezado['fecha_emision']);


//encabezado ------------------------------------------------------------
$cabecera = ["Empleado",
            "Guardias",
            "Hs Extras 50",
            "Hs Extras 50 Manejo",
            "Hs Extras 50 Truncado",
            "Hs Extras 50 Traslado",
            "Total Hs Extras 50",
            "Hs Extras 100",
            "Hs Base",
            "Hs Viaje Truncado",
            "Hs Viaje",
            "Total Hs viaje",
            "Desarraigo",
            "Zona Diferencial -MF",
            "Viandas Des. Des. y Mer.",
            "Viandas día trabajado",
            "Viandas Des. Alm. y Cena",
            "Viandas Extra",
            "Viandas Extra Manejo",
            "Viandas Extra Comedor",
            "Total Viandas Extra",
            "Enfermedad",
            "Accidente",
            "Injustificadas",
            "Permiso días trámite",
            "Permiso gremial",
            "Resto Permisos",
            "Vacaciones",
            "Suspención",
            "Mayor Función",
            "Monto cañista",
            "Adicional Horas Nocturnas",
            "DD Háb. período",
            "DRT período",
            "DHT período",
            "DHNT período",
            "DCNT período",
            "DH calendario",
            "Observaciones"
        ];
$sheet->fromArray($cabecera, null, 'A7');
$spreadsheet->getActiveSheet()->getStyle('A7:AM7')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A7:AM7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//cuerpo -----------------------------------------------------------------
$fila = 8;
foreach ($view->partes as $p):

    $empleado = $p['legajo'].' '.$p['apellido'].' '.$p['nombre'];

    $sheet->setCellValueByColumnAndRow(1, $fila, $empleado); //A
    $sheet->setCellValueByColumnAndRow(2, $fila, $p['guardias']); //B
    $sheet->setCellValueByColumnAndRow(3, $fila, $p['hs_extras_50']); //C
    $sheet->setCellValueByColumnAndRow(4, $fila, $p['hs_extras_50_manejo']); //D
    $sheet->setCellValueByColumnAndRow(5, $fila, $p['hs_extras_50_truncado']); //E
    $sheet->setCellValueByColumnAndRow(6, $fila, $p['hs_extras_50_traslado']); //F
    $sheet->setCellValueByColumnAndRow(7, $fila, $p['total_hs_extras_50']); //G
    $sheet->setCellValueByColumnAndRow(8, $fila, $p['hs_extras_100']); //H
    $sheet->setCellValueByColumnAndRow(9, $fila, $p['hs_base']); //I
    $sheet->setCellValueByColumnAndRow(10, $fila, $p['hs_viaje_truncado']); //J
    $sheet->setCellValueByColumnAndRow(11, $fila, $p['hs_viaje']); //K
    $sheet->setCellValueByColumnAndRow(12, $fila, $p['total_hs_viaje']); //L
    $sheet->setCellValueByColumnAndRow(13, $fila, $p['desarraigo']); //M
    $sheet->setCellValueByColumnAndRow(14, $fila, $p['zona_dif']); //N
    $sheet->setCellValueByColumnAndRow(15, $fila, $p['viandas_des_des_y_mer']); //O
    $sheet->setCellValueByColumnAndRow(16, $fila, $p['viandas_dia_trabajado']); //P
    $sheet->setCellValueByColumnAndRow(17, $fila, $p['viandas_des_alm_y_cena']); //Q
    $sheet->setCellValueByColumnAndRow(18, $fila, $p['viandas_extra']); //R
    $sheet->setCellValueByColumnAndRow(19, $fila, $p['viandas_extra_manejo']); //S
    $sheet->setCellValueByColumnAndRow(20, $fila, $p['viandas_extra_comedor']); //T
    $sheet->setCellValueByColumnAndRow(21, $fila, $p['total_viandas_extra']); //U
    $sheet->setCellValueByColumnAndRow(22, $fila, $p['enfermedad']); //V
    $sheet->setCellValueByColumnAndRow(23, $fila, $p['accidente']); //W
    $sheet->setCellValueByColumnAndRow(24, $fila, $p['injustificadas']); //X;
    $sheet->setCellValueByColumnAndRow(25, $fila, $p['permiso_dias_tramite']); //Y
    $sheet->setCellValueByColumnAndRow(26, $fila, $p['permiso_gremial']); //Z
    $sheet->setCellValueByColumnAndRow(27, $fila, $p['resto_permisos']); //AA
    $sheet->setCellValueByColumnAndRow(28, $fila, $p['vacaciones']); //AB
    $sheet->setCellValueByColumnAndRow(29, $fila, $p['suspenciones']); //AC
    $sheet->setCellValueByColumnAndRow(30, $fila, $p['mayor_funcion']); //AD
    $sheet->setCellValueByColumnAndRow(31, $fila, $p['monto_cañista']); //AE
    $sheet->setCellValueByColumnAndRow(32, $fila, $p['adicional_horas_nocturnas']); //AF
    $sheet->setCellValueByColumnAndRow(33, $fila, $p['DHDD']); //AG
    $sheet->setCellValueByColumnAndRow(34, $fila, $p['DRT']); //AH
    $sheet->setCellValueByColumnAndRow(35, $fila, $p['DHT']); //AI
    $sheet->setCellValueByColumnAndRow(36, $fila, $p['DHNT']); //AJ
    $sheet->setCellValueByColumnAndRow(37, $fila, $p['DCNT']); //AK
    $sheet->setCellValueByColumnAndRow(38, $fila, $p['DH']); //AL
    $sheet->setCellValueByColumnAndRow(39, $fila, $p['mayor_funcion_m']); //AM

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
$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('AA')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('AB')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('AC')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('AD')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('AE')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('AF')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('AG')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('AH')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('AI')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('AJ')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('AK')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('AL')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('AM')->setWidth(30);


$spreadsheet->getActiveSheet()->getStyle('B7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('C7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('D7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('E7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('F7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('G7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('H7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('I7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('J7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('K7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('L7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('M7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('N7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('O7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('P7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('Q7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('R7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('S7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('T7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('U7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('V7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('W7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('X7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('Y7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('Z7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AA7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AB7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AC7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AD7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AE7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AF7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AG7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AH7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AI7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AJ7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AK7')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AL7')->getAlignment()->setTextRotation(90);

/* comentarios en las celdas necesarias */
$spreadsheet->getActiveSheet()->getComment('AG7')->getText()->createTextRun('Dias Hábiles Disponible en Domicilio del período.');
$spreadsheet->getActiveSheet()->getComment('AH7')->getText()->createTextRun('Dias Reales Trabajados del período.');
$spreadsheet->getActiveSheet()->getComment('AI7')->getText()->createTextRun('Dias Hábiles Trabajados del período.');
$spreadsheet->getActiveSheet()->getComment('AJ7')->getText()->createTextRun('Dias Hábiles No Trabajados del período.');
$spreadsheet->getActiveSheet()->getComment('AK7')->getText()->createTextRun('Dias Corridos No Trabajados del período.');
$spreadsheet->getActiveSheet()->getComment('AL7')->getText()->createTextRun('Dias Hábiles del mes calendario.');


/* ocultar columnas de acuerdo al template del contrato */

//guardias B
if ($encabezado['template'] == 'POZOS' ||
    $encabezado['template'] == 'PAE'
) $spreadsheet->getActiveSheet()->getColumnDimension('B')->setVisible(false);

//hs_extras_50 C
if ($encabezado['template'] == 'PAE'
) $spreadsheet->getActiveSheet()->getColumnDimension('C')->setVisible(false);

//hs_extras_50_manejo D
if ($encabezado['template'] == 'PAE'
) $spreadsheet->getActiveSheet()->getColumnDimension('D')->setVisible(false);

//hs_extras_50_truncado E
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'POZOS' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH'
) $spreadsheet->getActiveSheet()->getColumnDimension('E')->setVisible(false);

//hs_extras_50_traslado F
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'POZOS' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH'
) $spreadsheet->getActiveSheet()->getColumnDimension('F')->setVisible(false);

//hs_base I
if ($encabezado['template'] == 'POZOS' ||
    $encabezado['template'] == 'YPF_SC'
) $spreadsheet->getActiveSheet()->getColumnDimension('I')->setVisible(false);

//hs_viaje_truncado J
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'POZOS' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH'
) $spreadsheet->getActiveSheet()->getColumnDimension('J')->setVisible(false);

//hs_viaje K
if ($encabezado['template'] == 'POZOS'
) $spreadsheet->getActiveSheet()->getColumnDimension('K')->setVisible(false);


//desarraigo M
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH' ||
    $encabezado['template'] == 'YPF_SC'
) $spreadsheet->getActiveSheet()->getColumnDimension('M')->setVisible(false);

//zona_dif N
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH' ||
    $encabezado['template'] == 'YPF_SC'
) $spreadsheet->getActiveSheet()->getColumnDimension('N')->setVisible(false);

//viandas_des_des_y_mer O
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH' ||
    $encabezado['template'] == 'YPF_SC'
) $spreadsheet->getActiveSheet()->getColumnDimension('O')->setVisible(false);

//viandas_dia_trabajado P
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH' ||
    $encabezado['template'] == 'YPF_SC'
) $spreadsheet->getActiveSheet()->getColumnDimension('P')->setVisible(false);

//viandas_des_alm_y_cena Q
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH' ||
    $encabezado['template'] == 'YPF_SC'
) $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setVisible(false);

//viandas_extra_manejo S
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'POZOS' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH'
) $spreadsheet->getActiveSheet()->getColumnDimension('S')->setVisible(false);

//viandas_extra_comedor T
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'POZOS' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH'
) $spreadsheet->getActiveSheet()->getColumnDimension('T')->setVisible(false);


//total_viandas_extra U
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH'
) $spreadsheet->getActiveSheet()->getColumnDimension('U')->setVisible(false);

//monto_cañista AE
if ($encabezado['template'] == 'POZOS' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH' ||
    $encabezado['template'] == 'YPF_SC'
) $spreadsheet->getActiveSheet()->getColumnDimension('AE')->setVisible(false);


//adicional_horas_nocturnas AF
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'POZOS' ||
    $encabezado['template'] == 'YPF_CH' ||
    $encabezado['template'] == 'YPF_SC'
) $spreadsheet->getActiveSheet()->getColumnDimension('AF')->setVisible(false);


//-----------------------------------------------------------------

$writer = new Xlsx($spreadsheet);
//$writer->save('C:/temp/hello world.xlsx');
$filename = 'RN03_administracion_'.$encabezado["template"].'_'.date("d-m-Y").'.xlsx';
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean(); //https://github.com/PHPOffice/PhpSpreadsheet/issues/217
$writer->save('php://output');
exit();


?>

