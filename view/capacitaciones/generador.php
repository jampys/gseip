<?php


    /*$fila['condnormal'] = ($fila['condnormal']==1)? 'checked=true':'';
    $fila['Num_precinto'] = ($fila['Num_precinto'])? $fila['Num_precinto'] : "s/d";
    $tolerancia = ($fila["presioncalibracion"]<=4.83)? "+/- 0,138 kg/cm2":"+/- 3%";
    $tolerancia_ls = ($fila["presioncalibracion"]<=4.83)? $fila["presioncalibracion"] + 0.138 : $fila["presioncalibracion"] + $fila["presioncalibracion"] * 0.03 ;
    $tolerancia_li = ($fila["presioncalibracion"]<=4.83)? $fila["presioncalibracion"] - 0.138 : $fila["presioncalibracion"] - $fila["presioncalibracion"] * 0.03 ;
    $fila1['nrocertificado'] = ($fila1['nrocertificado'])? $fila1['nrocertificado'] : "s/d";
    $fila1['mayorpresion2'] = ($fila1['mayorpresion2'])? round($fila1['mayorpresion2'], 2).' kg/cm2' : "s/d";
    $firma = ($fila['u_firma'])? base64_encode($fila['u_firma']) : "images/default.jpg";*/

//---------------------------------------------------------------------------------

$fila4['nro_capacitacion'] = $fila1['id_capacitacion'].'/'.$fila1['periodo'];
$fila4['periodo_programada'] = $fila1['periodo'].' / '.$fila1['mes_programada'];
$fila4['tema'] = $fila1['tema'];
$fila4['categoria'] = $fila1['categoria'];
$fila4['descripcion'] = $fila1['descripcion'];
$fila4['cant_participantes'] = $fila1['cant_participantes'];
$fila4['sum_hs'] = $fila1['sum_hs'];
$fila4['contratos'] = $fila2['contratos'];

/*

$fila4['No conformidad real'] = ($nc->getTipo() == 'No conformidad real')? 'checked=true':'';
$fila4['No conformidad potencial'] = ($nc->getTipo() == 'No conformidad potencial')? 'checked=true':'';
$fila4['Producto/Servicio no conforme'] = ($nc->getTipo() == 'Producto/Servicio no conforme')? 'checked=true':'';
$fila4['Oportunidad de mejora'] = ($nc->getTipo() == 'Oportunidad de mejora')? 'checked=true':'';
$fila4['Queja/Reclamo del cliente'] = ($nc->getTipo() == 'Queja/Reclamo del cliente')? 'checked=true':'';

$fila4['Si'] = ($nc->getAnalisisCausa() == 'Si')? 'checked=true':'';
$fila4['No'] = ($nc->getAnalisisCausa() == 'No')? 'checked=true':'';

$fila4['Correctiva'] = ($nc->getTipoAccion() == 'Correctiva')? 'checked=true':'';
$fila4['Preventiva'] = ($nc->getTipoAccion() == 'Preventiva')? 'checked=true':'';

$fila4['descripcion'] = nl2br($nc->getDescripcion());
$fila4['accion_inmediata'] = nl2br($nc->getAccionInmediata());
$fila4['analisis_causa_desc'] = nl2br($nc->getAnalisisCausaDesc());
*/

//----------------------------------------------------------------------------------




    require_once('vendor/autoload.php');
    $mpdf = new \Mpdf\Mpdf([
        "format" => "A4",
        "margin_top" => 14, //https://github.com/mpdf/mpdf/issues/615
        'default_font' => 'dejavusans',
        'default_font_size' => 8,
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
    $mpdf->SetTitle($fila4['title']);

    $css = file_get_contents('resources/css/dario_mpdf.css');
    $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML('





        <div style="width: 100%">



        <div style="float: left; width: 25%; height: 36px">
            <img src="resources/img/seip.png" width="126px" height="36px">
        </div>

        <div style="float: left; width: 65%; height: 36px; text-align: left">
            <span style="font-size: 15px; font-weight: bold">No conformidad/Oportunidad de mejora</span><br/>
            <span style="font-size: 13px">Calidad, Seguridad, Salud y Medio Ambiente</span>
        </div>

        <div style="float: right; width: 10%; height: 36px; font-size: 8px; text-align: right">
            RG 06-02<br/>
            22/02/2022<br/>
            rev 01
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
                    <td style="width: 25%"><span class="subtitulo">Nro. Capacitación</span></td>
                    <td style="width: 25%">'.$fila4["nro_capacitacion"].'</td>
                    <td style="width: 25%"><span class="subtitulo">Período / Programada</span></td>
                    <td style="width: 25%">'.$fila4['periodo_programada'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Tema</span></td>
                    <td>'.$fila4["tema"].'</td>
                    <td><span class="subtitulo">Categoría</span></td>
                    <td>'.$fila4['categoria'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Descripción</span></td>
                    <td  colspan="3">'.$fila4['descripcion'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Contratos</span></td>
                    <td  colspan="3">'.$fila4['contratos'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Cant. participantes</span></td>
                    <td>'.$fila4['cant_participantes'].'</td>
                    <td><span class="subtitulo">Sumatoria horas</span></td>
                    <td>'.$fila4['sum_hs'].'</td>
                </tr>
            </tbody>
        </table>
        </div>


        </div>

        <br/>



        ',
        \Mpdf\HTMLParserMode::HTML_BODY);

// Ediciones *****************************************
$html = '<div style="float: left; width: 100%">
        <span class="titulo">Ediciones</span>
        <div class="borde-circular">
            <table id="example" style="width:100%">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Capacitador</th>
                <th>Duración</th>
                <th>Modalidad</th>
            </tr>
            </thead>
            <tbody>';

foreach ($fila3 as $x) {
    $html.='<tr><td>'.$x["fecha_edicion"].'</td>><td>'.$x["nombre"].'</td><td>'.$x["capacitador"].'</td><td>'.$x["duracion"].'</td><td>'.$x["modalidad"].'</td></tr>';
}

$html.='</tbody></table></div></div>';
$mpdf->WriteHTML($html);


$mpdf->WriteHTML('<br/>');

// Participantes *****************************************
$html1 = '<div style="float: left; width: 100%">
        <span class="titulo">Participantes</span>
        <div class="borde-circular">
            <table id="example" style="width:100%">
            <thead>
            <tr>
                <th>Empleado</th>
                <th>Contrato</th>
                <th>Edición</th>
                <th>Asistió</th>
            </tr>
            </thead>
            <tbody>';

foreach ($fila5 as $x) {
    $asistio = ($x["asistio"] == 1)? 'SI' : 'NO';
    $html1.='<tr><td>'.$x["empleado"].'</td>><td>'.$x["contrato"].'</td><td>'.$x["edicion"].'</td><td>'.$asistio.'</td></tr>';
}

$html1.='</tbody></table></div></div>';
$mpdf->WriteHTML($html1);




    $namepdf = $fila['nrocertificado'].'-v'.$fila['nroserie'].'-'.date('dmY').'.pdf';
    $mpdf->Output($namepdf, 'I'); //I visualizar, D descargar



	?>

