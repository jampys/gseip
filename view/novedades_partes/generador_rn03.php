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
            "Hs Viaje",
            "Hs Viaje Truncado",
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
$sheet->fromArray($cabecera, null, 'A6');
$spreadsheet->getActiveSheet()->getStyle('A6:AM6')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A6:AM6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6E6');

//cuerpo -----------------------------------------------------------------
$fila = 7;
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
    $sheet->setCellValueByColumnAndRow(10, $fila, $p['hs_viaje']); //J
    $sheet->setCellValueByColumnAndRow(11, $fila, $p['hs_viaje_truncado']); //K
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


$spreadsheet->getActiveSheet()->getStyle('B6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('C6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('D6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('E6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('F6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('G6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('H6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('I6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('J6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('K6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('L6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('M6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('N6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('O6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('P6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('Q6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('R6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('S6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('T6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('U6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('V6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('W6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('X6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('Y6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('Z6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AA6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AB6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AC6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AD6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AE6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AF6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AG6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AH6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AI6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AJ6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AK6')->getAlignment()->setTextRotation(90);
$spreadsheet->getActiveSheet()->getStyle('AL6')->getAlignment()->setTextRotation(90);


/* ocultar columnas de acuerdo al template del contrato */

//guardias
if ($encabezado['template'] == 'POZOS' ||
    $encabezado['template'] == 'PAE'
) $spreadsheet->getActiveSheet()->getColumnDimension('B')->setVisible(false);

//hs_extras_50
if ($encabezado['template'] == 'PAE'
) $spreadsheet->getActiveSheet()->getColumnDimension('C')->setVisible(false);

//hs_extras_50_manejo
if ($encabezado['template'] == 'PAE'
) $spreadsheet->getActiveSheet()->getColumnDimension('D')->setVisible(false);

//hs_extras_50_truncado
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'POZOS' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH'
) $spreadsheet->getActiveSheet()->getColumnDimension('E')->setVisible(false);

//hs_extras_50_traslado
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'POZOS' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH'
) $spreadsheet->getActiveSheet()->getColumnDimension('F')->setVisible(false);

//hs_base
if ($encabezado['template'] == 'POZOS' ||
    $encabezado['template'] == 'YPF_SC'
) $spreadsheet->getActiveSheet()->getColumnDimension('I')->setVisible(false);

//hs_viaje_truncado
if ($encabezado['template'] == 'PAE' ||
    $encabezado['template'] == 'POZOS' ||
    $encabezado['template'] == 'STAFF' ||
    $encabezado['template'] == 'YPF_CH'
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
    $encabezado['template'] == 'POZOS' ||
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
$filename = 'RN3_administracion_'.$encabezado["template"].'_'.$id_contrato.'.xlsx';
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

ob_end_clean(); //https://github.com/PHPOffice/PhpSpreadsheet/issues/217
$writer->save('php://output');
exit();


?>

