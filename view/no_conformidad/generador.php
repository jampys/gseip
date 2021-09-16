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

$fila4['nro_no_conformidad'] = $nc->getNroNoConformidad();
$fila4['nombre'] = $nc->getNombre();
$fila4['sector'] = $nc->getSector();
$fila4['created_date'] = $nc->getCreatedDate();
$fila4['estado'] = $nc->getEstado();
$fila4['responsable_seguimiento'] = $rs->getApellido().' '.$rs->getNombre();

$fila4['title'] = $fila4['nro_no_conformidad'].' '.$fila4['nombre'];

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
                    <td style="width: 25%"><span class="subtitulo">Nro. No conformidad</span></td>
                    <td style="width: 25%">'.$fila4["nro_no_conformidad"].'</td>
                    <td style="width: 25%"><span class="subtitulo">Fecha</span></td>
                    <td style="width: 25%">'.$fila4['created_date'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Nombre</span></td>
                    <td>'.$fila4["nombre"].'</td>
                    <td><span class="subtitulo">Resp. seguimiento</span></td>
                    <td>'.$fila4['responsable_seguimiento'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Sector/Proceso</span></td>
                    <td>'.$fila4['sector'].'</td>
                    <td><span class="subtitulo">Estado</span></td>
                    <td>'.$fila4['estado'].'</td>
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




        ',
        \Mpdf\HTMLParserMode::HTML_BODY);


$html = '<div style="float: left; width: 100%">
        <span class="titulo">Acciones</span>
        <div class="borde-circular">
            <table id="example" style="width:100%">
            <thead>
            <tr>
                <th>Acción</th>
                <th>Resp. ejecución</th>
                <th>F. impl.</th>
            </tr>
            </thead>
            <tbody>';

foreach ($fila5 as $x) {
    $html.='<tr><td>'.$x["accion"].'</td>><td>'.$x["responsable_ejecucion"].'</td><td>'.$x["fecha_implementacion"].'</td></tr>';
}

$html.='</tbody></table></div></div>';
$mpdf->WriteHTML($html);


$mpdf->WriteHTML('
        <br/>
        <div style="float: left; width: 100%">
            <!--<span class="titulo">Datos de la válvula</span>-->

            <div class="borde-circular">
            <table style="width:100%">
            <tbody>
                <tr>
                    <td style="width: 25%"><span class="subtitulo">Verificó</span></td>
                    <td style="width: 25%">'.$fila6['verifico'].'</td>
                    <td style="width: 25%"><span class="subtitulo">Fecha</span></td>
                    <td style="width: 25%">'.$fila6['fecha_verificacion'].'</td>
                </tr>
            </tbody>
        </table>

        </div>
');


    $namepdf = $fila['nrocertificado'].'-v'.$fila['nroserie'].'-'.date('dmY').'.pdf';
    $mpdf->Output($namepdf, 'I'); //I visualizar, D descargar



	?>

