<?php

    require_once('vendor/autoload.php');
    $mpdf = new \Mpdf\Mpdf([
        "format" => "A4",
        "margin_top" => 14, //https://github.com/mpdf/mpdf/issues/615
        'default_font' => 'dejavusans',
        'default_font_size' => 8,
        //'debug' => true, //debug errores
        'allow_output_buffering' => true //capturar excepciones
    ]);

    $mpdf->SetHTMLFooter('
                <table style="width:100%">
                    <tbody>
                        <tr>
                            <td><span style="font-size: 10px">'.$GLOBALS['ini']["unidad_negocio"]["un_direccion"].'  Tel. '.$GLOBALS['ini']["unidad_negocio"]["un_telefono"].'</span></td>
                            <td>{PAGENO}/{nb}</td>
                        </tr>
                    </tbody>
                </table>
            ');
    //$mpdf->SetFooter('{PAGENO}');
    $mpdf->SetTitle('RN01 Reporte de Actividad de cuadrillas');

    $css = file_get_contents('resources/css/dario_mpdf.css');
    $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML('





        <div style="width: 100%">



        <div style="float: left; width: 25%; height: 36px">
            <img src="resources/img/seip.png" width="126px" height="36px">
        </div>

        <div style="float: left; width: 65%; height: 36px; text-align: left">
            <span style="font-size: 16px; font-weight: bold">RN01 Reporte de actividad de cuadrillas</span><br/>
            <span style="font-size: 13px">Gerencia operativa y de servicios</span>
        </div>

        <div style="float: right; width: 10%; height: 36px; font-size: 8px; text-align: right">
            RO-VSE-0104<br/>
            19/11/2021<br/>
            rev 00
        </div>


        </div>
        <hr/>



        <div style="float: left; width: 100%">


            <div class="borde-circular" style="background-color: #f2f2f2">
            <table style="width:100%">
            <!--<thead>
                <tr>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Age</th>
                </tr>
            </thead>-->
            <tbody>
                <tr>
                    <td style="width: 20%"><span class="subtitulo">Cliente</span></td>
                    <td style="width: 30%">'.$encabezado['cliente'].'</td>
                    <td style="width: 20%"><span class="subtitulo">Contrato</span></td>
                    <td style="width: 30%">'.$encabezado['contrato'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Cuadrilla</span></td>
                    <td>'.$encabezado['cuadrilla'].'</td>
                    <td><span class="subtitulo">Fecha emisión</span></td>
                    <td>'.$encabezado['fecha_emision'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Fecha desde - hasta</span></td>
                    <td>'.$encabezado['fecha_desde']." - ".$encabezado['fecha_hasta'].'</td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        </div>


        </div>

        <br/>





        <table id="example" style="width:100%">
            <thead>
            <tr>
                <th>Fecha parte</th>
                <th>Cuadrilla</th>
                <th>Área</th>
                <th>Evento</th>
                <th>Nro. parte</th>
                <th>Nro. OT</th>
            </tr>
            </thead>
            <tbody>


        ',
        \Mpdf\HTMLParserMode::HTML_BODY);


        foreach ($rta as $x) {
            $mpdf->WriteHTML('<tr><td>'.$x["fecha_parte"].'</td><td>'.$x["cuadrilla"].'</td><td>'.$x["area"].'</td><td>'.$x["evento"].'</td><td>'.$x["nro_parte_diario"].'</td><td>'.$x["orden_nro"].'</td></tr>');
        }

        $mpdf->WriteHTML('</tbody></table>');


        $namepdf = 'RN01-'.$encabezado['cliente'].'-'.date('dmYHi').'.pdf';
        $mpdf->Output($namepdf, 'I'); //I visualizar, D descargar





	?>

