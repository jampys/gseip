<?php



    $pmax1 = 0; $pmax2 = 0;

    $fila['condnormal'] = ($fila['condnormal']==1)? 'checked=true':'';
    $fila['condcorrosion'] = ($fila['condcorrosion']==1)? 'checked=true':'';
    $fila['condempetrolada'] = ($fila['condempetrolada']==1)? 'checked=true':'';
    $fila['condresiduos'] = ($fila['condresiduos']==1)? 'checked=true':'';
    $fila['condprecinto'] = ($fila['condprecinto']==1)?  'checked=true':'';
    $fila['conddañoe'] = ($fila['conddañoe']==1)?  'checked=true':'';

    $fila['valvactuada'] = ($fila['valvactuada']==1)?  'checked=true':'';
    $fila['resorte'] = ($fila['resorte']==1)?  'checked=true':'';
    $fila['obturador'] = ($fila['obturador']==1)?  'checked=true':'';
    $fila['asiento'] = ($fila['asiento']==1)?  'checked=true':'';
    $fila['fuelle'] = ($fila['fuelle']==1)?  'checked=true':'';
    $fila['contratuerca'] = ($fila['contratuerca']==1)?  'checked=true':'';
    $fila['cierrepollera'] = ($fila['cierrepollera']==1)?  'checked=true':'';
    $fila['pintura'] = ($fila['pintura']==1)?  'checked=true':'';
    $fila['precintosup'] = ($fila['precintosup']==1)?  'checked=true':'';


    $fila['Num_precinto'] = ($fila['Num_precinto'])? $fila['Num_precinto'] : "s/d";
    $tolerancia = ($fila["presioncalibracion"]<=4.83)? "+/- 0,138 kg/cm2":"+/- 3%";
    $tolerancia_ls = ($fila["presioncalibracion"]<=4.83)? $fila["presioncalibracion"] + 0.138 : $fila["presioncalibracion"] + $fila["presioncalibracion"] * 0.03 ;
    $tolerancia_li = ($fila["presioncalibracion"]<=4.83)? $fila["presioncalibracion"] - 0.138 : $fila["presioncalibracion"] - $fila["presioncalibracion"] * 0.03 ;

    $fila['serie_entrada'] = ($fila['serie_entrada'])? $fila['serie_entrada'] : "s/d";
    $fila['serie_salida'] = ($fila['serie_salida'])? $fila['serie_salida'] : "s/d";

    $fila['i_nombre'] = ($fila['i_nombre'])? $fila['i_nombre'] : "s/d";
    $fila['i_marca'] = ($fila['i_marca'])? $fila['i_marca'] : "s/d";

    $fila['nroserie_fabricante'] = ($fila['nroserie_fabricante'])? $fila['nroserie_fabricante'] : "s/d";
    $fila['v_tipo_orificio'] = ($fila['v_tipo_orificio'])? $fila['v_tipo_orificio'] : "s/d";
    $fila['v_area_orificio'] = ($fila['v_area_orificio'])? $fila['v_area_orificio'] : "s/d";
    $fila['sap'] = ($fila['sap'])? $fila['sap'] : "s/d";
    $fila['v_resorte'] = ($fila['v_resorte'])? $fila['v_resorte'] : "s/d";
    $fila['v_fuelle'] = ($fila['v_fuelle'])? $fila['v_fuelle'] : "s/d";

    $fila["fluidotobera"] = ($fila['fluidotobera'])? $fila['fluidotobera'] : "s/d";

    $fila1['nrocertificado'] = ($fila1['nrocertificado'])? $fila1['nrocertificado'] : "s/d";
    $fila1['mayorpresion'] = ($fila1['mayorpresion'])? round($fila1['mayorpresion'], 2).' kg/cm2' : "s/d";
    $fila1['mayorpresion2'] = ($fila1['mayorpresion2'])? round($fila1['mayorpresion2'], 2).' kg/cm2' : "s/d";


    $fila2['nro_ot'] = ($fila2['nro_ot'])? $fila2['nro_ot'] : "s/d";
    $fila2['instalacion'] = ($fila2['instalacion'])? $fila2['instalacion'] : "s/d";
    $fila2['yacimiento'] = ($fila2['yacimiento'])? $fila2['yacimiento'] : "s/d";
    $fila2['equipo'] = ($fila2['equipo'])? $fila2['equipo'] : "s/d";

    $firma = ($fila['u_firma'])? base64_encode($fila['u_firma']) : "images/default.jpg";
//---------------------------------------------------------------------------------

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


//----------------------------------------------------------------------------------

    include('pdf/graficomasicos.php');

    //calculo de la presion promedio. Ojo las variables se calculas en graficomasicos.php
    $pmax1 = round($pmax1, 2).' kg/cm2';
    $pmax2 = round($pmax2, 2).' kg/cm2';
    $pprom = ($pmax2 > 0)? ($pmax1 + $pmax2)/2 : $pmax1;
    $pprom = round($pprom, 2).' kg/cm2';


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
    $mpdf->SetTitle($fila['nrocertificado']);

    $css = file_get_contents('resources/css/dario_mpdf.css');
    $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML('





        <div style="width: 100%">



        <div style="float: left; width: 25%; height: 36px">
            <img src="resources/img/seip.png" width="126px" height="36px">
        </div>

        <div style="float: left; width: 65%; height: 36px; text-align: left">
            <span style="font-size: 15px; font-weight: bold">Informe de No conformidad/Oportunidad de mejora</span><br/>
            <span style="font-size: 13px">Calidad, Seguridad, Salud y Medio Ambiente</span>
        </div>

        <div style="float: right; width: 10%; height: 36px; font-size: 8px; text-align: right">
            RG 01-01<br/>
            23/05/2021<br/>
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
                    <td style="width: 30%"><span class="subtitulo">Cliente</span></td>
                    <td style="width: 20%">'.$fila["c_nombre"].'</td>
                    <td style="width: 30%"><span class="subtitulo">N° de Certificado</span></td>
                    <td style="width: 20%">'.$fila['nrocertificado'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Unidad de Negocio</span></td>
                    <td>'.$GLOBALS['ini']["unidad_negocio"]["un_nombre"].'</td>
                    <td><span class="subtitulo">Orden de Trabajo</span></td>
                    <td>'.$fila2["nro_ot"].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Yacimiento</span></td>
                    <td>'.$fila2["yacimiento"].'</td>
                    <td><span class="subtitulo">Fecha de Calibración</span></td>
                    <td>'.$fila["fechacalib"].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Instalación</span></td>
                    <td>'.$fila2["instalacion"].'</td>
                    <td><span class="subtitulo">N° de Precinto</span></td>
                    <td>'.$fila["Num_precinto"].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Equipo <b>/</b> Línea de Proceso</span></td>
                    <td>'.$fila2["equipo"].' '.$fila2['numequipo'].'</td>
                    <td><span class="subtitulo">Seteo de calibración</span></td>
                    <td>'.$fila['presioncalibracion'].' kg/cm2</td>
                </tr>
            </tbody>
        </table>
        </div>


        </div>


        <br/>



         <div style="float: left; width: 100%">
            <span class="titulo">Tipo de No conformidad</span>

            <div class="borde-circular">
            <table style="width:100%">
            <tbody>
                <tr>
                    <td style="width: 50%"><input type="checkbox" id="" value="" '.$fila4['No conformidad real'].'>&nbsp;<span class="subtitulo">No conformidad real</span></td>
                    <td style="width: 50%"><input type="checkbox" id="" value="" '.$fila4['Oportunidad de mejora'].' >&nbsp;<span class="subtitulo">Oportunidad de mejora</span></td>
                </tr>
                <tr>
                    <td><input type="checkbox" id="" value="" '.$fila4['No conformidad potencial'].'>&nbsp;<span class="subtitulo">No conformidad potencial</span></td>
                    <td><input type="checkbox" id="" value="" '.$fila4['Queja/Reclamo del cliente'].' >&nbsp;<span class="subtitulo">Queja / reclamo del cliente</span></td>
                </tr>
                <tr>
                    <td><input type="checkbox" id="" value="" '.$fila4['Producto/Servicio no conforme'].' >&nbsp;<span class="subtitulo">Producto / Servicio no conforme</span></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        </div>

        </div>


        <br/>


        <div style="width:49%; float:left">
        <span class="titulo">Análisis de causa raiz</span>
        <div class="borde-circular" >
        <table style="width:100%">
            <tbody>
                <tr>
                    <td style="width: 50%"><input type="checkbox" id="" value="" '.$fila4['Si'].'>&nbsp;<span class="subtitulo">Si</span></td>
                </tr>
                <tr>
                    <td style="width: 50%"><input type="checkbox" id="" value="" '.$fila4['No'].' >&nbsp;<span class="subtitulo">No</span></td>
                </tr>
            </tbody>
        </table>
        </div>

        </div>

        <div style="width:1%; float:left">&nbsp;</div>

        <div style="width:50%; float:left">
        <span class="titulo">Tipo acción</span>
        <div class="borde-circular" >
        <table style="width:100%">
            <tbody>
                <tr>
                    <td style="width: 50%"><input type="checkbox" id="" value="" '.$fila4['Correctiva'].'>&nbsp;<span class="subtitulo">Correctiva</span></td>
                </tr>
                <tr>
                    <td style="width: 50%"><input type="checkbox" id="" value="" '.$fila4['Preventiva'].' >&nbsp;<span class="subtitulo">Preventiva</span></td>
                </tr>
            </tbody>
        </table>
        </div>

        </div>



        <br/>
        <div style="float: left; width: 100%">
            <span class="titulo">Descripción del hallazgo</span>

            <div class="borde-circular">
            <table style="width:100%">
            <tbody>
                <tr>
                    <td>'.$fila4['descripcion'].'</td>
                </tr>
            </tbody>
        </table>
        </div>

        </div>



        <br/>
        <div style="float: left; width: 100%">
            <span class="titulo">Acción inmediata</span>

            <div class="borde-circular">
            <table style="width:100%">
            <tbody>
                <tr>
                    <td>'.$fila4['accion_inmediata'].'</td>
                </tr>
            </tbody>
        </table>
        </div>

        </div>



        <br/>
        <div style="float: left; width: 100%">
            <span class="titulo">Análisis de causa raiz</span>

            <div class="borde-circular">
            <table style="width:100%">
            <tbody>
                <tr>
                    <td>'.$fila4['analisis_causa_desc'].'</td>
                </tr>
            </tbody>
        </table>
        </div>

        </div>


        <br/>
        <table id="example" style="width:100%">
            <thead>
            <tr>
                <th>Acción</th>
                <th>Responsable</th>
                <th>F. impl.</th>
            </tr>
            </thead>
            <tbody>



        ',
        \Mpdf\HTMLParserMode::HTML_BODY);


foreach ($fila5 as $x) {
    $mpdf->WriteHTML('<tr><td>'.$x["accion"].'</td>><td>'.$x["user"].'</td><td>'.$x["fecha_implementacion"].'</td></tr>');
}

$mpdf->WriteHTML('</tbody></table>');




    $namepdf = $fila['nrocertificado'].'-v'.$fila['nroserie'].'-'.date('dmY').'.pdf';
    $mpdf->Output($namepdf, 'I'); //I visualizar, D descargar



	?>

