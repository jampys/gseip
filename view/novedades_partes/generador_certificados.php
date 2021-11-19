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
    $mpdf->SetTitle('Reporte PPT y calibraciones');

    $css = file_get_contents('pdf/dario.css');
    $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML('





        <div style="width: 100%">



        <div style="float: left; width: 25%; height: 36px">
            <img src="resources/img/seip.png" width="126px" height="36px">
        </div>

        <div style="float: left; width: 65%; height: 36px; text-align: left">
            <span style="font-size: 16px; font-weight: bold">Reporte PPT y calibraciones</span><br/>
            <span style="font-size: 13px">Válvulas de Seguridad y Alivio</span>
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
                    <td style="width: 25%"><span class="subtitulo">Cliente</span></td>
                    <td style="width: 25%">'.$encabezado['cliente'].'</td>
                    <td style="width: 25%"><span class="subtitulo">Contrato</span></td>
                    <td style="width: 25%">'.$encabezado['contrato'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Unidad de Negocio</span></td>
                    <td>'.$GLOBALS['ini']["unidad_negocio"]["un_nombre"].'</td>
                    <td><span class="subtitulo">Nro. válvula</span></td>
                    <td>'.$valvula.'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Yacimiento</span></td>
                    <td>'.$yacimiento.'</td>
                    <td><span class="subtitulo">Fecha desde - hasta</span></td>
                    <td>'.$startDate." - ".$endDate.'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Instalación</span></td>
                    <td>'.$instalacion.'</td>
                    <td><span class="subtitulo">Cant. calibraciones</span></td>
                    <td>'.$count_cert.'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Tipo certificado</span></td>
                    <td>'.$certificado.'</td>
                    <td><span class="subtitulo">Cant. PPT</span></td>
                    <td>'.$count_ppt.'</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><span class="subtitulo">Fecha emisión</span></td>
                    <td>'.date('d/m/Y H:i').'</td>
                </tr>
            </tbody>
        </table>
        </div>


        </div>

        <br/>





        <table id="example" style="width:100%">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Certificado</th>
                <th>Nro. válvula</th>
                <th>Tipo válvula</th>
                <th>Marca</th>
                <th>Modelo</th>
            </tr>
            </thead>
            <tbody>


        ',
        \Mpdf\HTMLParserMode::HTML_BODY);


        foreach ($rta as $x) {
            $mpdf->WriteHTML('<tr><td>'.$x["fecha_calibracion"].'</td><td>'.$x["nro_certificado"].'</td><td>'.$x["nro_serie"].'</td><td>'.$x["tipo_valvula"].'</td><td>'.$x["marca"].'</td><td>'.$x["modelo"].'</td></tr>');
        }

        $mpdf->WriteHTML('</tbody></table>');


        $namepdf = 'Reporte_PSV_calibradas-'.$cliente.'-'.date('dmYHi').'.pdf';
        $mpdf->Output($namepdf, 'I'); //I visualizar, D descargar





	?>

