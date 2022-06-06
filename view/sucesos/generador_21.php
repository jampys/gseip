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

/*$fila4['nro_no_conformidad'] = $nc->getNroNoConformidad();
$fila4['nombre'] = $nc->getNombre();
$fila4['sector'] = $nc->getSector();
$fila4['created_date'] = $nc->getCreatedDate();
$fila4['estado'] = $nc->getEstado();
$fila4['responsable_seguimiento'] = $rs->getApellido().' '.$rs->getNombre();


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
$fila4['analisis_causa_desc'] = nl2br($nc->getAnalisisCausaDesc());*/




$fila1['id_suceso'] = $su->getIdSuceso();
$fila1['periodo'] = $su->getPeriodo();
$fila1['legajo'] = substr($em->getLegajo(), 2, 4);
$fila1['cuil'] = $em->getCuil();
$fila1['cuil'] = substr($fila1['cuil'], 0, 2).'-'.substr($fila1['cuil'], 2, -1).'-'.substr($fila1['cuil'], -1, 1);
$fila1['apellido'] = $em->getApellido();
$fila1['nombre'] = $em->getNombre();
$fila1['title'] = $fila1['id_suceso']." ".$fila1['apellido']." ".$fila1['nombre'];

$fila1['fecha_desde'] = $su->getFechaDesde();
$fila1['fecha_hasta'] = $su->getFechaHasta();

$fd = DateTime::createFromFormat('d/m/Y', $su->getFechaDesde());
$fh = DateTime::createFromFormat('d/m/Y', $su->getFechaHasta());
$fh->modify('+1 day'); //increment en 1 la fecha_hasta
$interval = $fd->diff($fh);
$fila1['dias'] = $interval->format('%a');


//setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
$fila1['sysdate'] = strftime("%d de %B del %Y"); //https://stackoverflow.com/questions/22635303/get-day-from-string-in-spanish-php


//----------------------------------------------------------------------------------




    require_once('vendor/autoload.php');
    $mpdf = new \Mpdf\Mpdf([
        "format" => "A4",
        "margin_top" => 14, //https://github.com/mpdf/mpdf/issues/615
        'default_font' => 'dejavusans',
        'default_font_size' => 9,
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
    $mpdf->SetTitle($fila1['title'] );

    $css = file_get_contents('resources/css/dario_mpdf.css');
    $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML('





        <div style="width: 100%">



        <div style="float: left; width: 25%; height: 36px">
            <img src="resources/img/seip.png" width="126px" height="36px">
        </div>

        <div style="float: left; width: 65%; height: 36px; text-align: left">
            <span style="font-size: 15px; font-weight: bold">Período de descanso anual</span><br/>
            <span style="font-size: 13px">RRHH y Administración</span>
        </div>

        <div style="float: right; width: 10%; height: 36px; font-size: 8px; text-align: right">
            RG 01-01<br/>
            06/06/2022<br/>
            rev 02
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
                    <td style="width: 20%"><span class="subtitulo">Nro. suceso</span></td>
                    <td style="width: 30%">'.$fila1['id_suceso'].'</td>
                    <td style="width: 20%"><span class="subtitulo">Cant. días</span></td>
                    <td style="width: 30%">'.$fila1['dias'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Empleado</span></td>
                    <td>'.$fila1['legajo'].' '.$fila1['apellido'].' '. $fila1['nombre'].'</td>
                    <td><span class="subtitulo">F. desde - F. hasta</span></td>
                    <td>'.$fila1['fecha_desde'].' - '.$fila1['fecha_hasta'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">CUIL</span></td>
                    <td>'.$fila1['cuil'].'</td>
                    <td><span class="subtitulo">Período</span></td>
                    <td>'.$fila1['periodo'].'</td>
                </tr>
            </tbody>
        </table>
        </div>


        </div>




        <br/>
        <div style="float: left; width: 100%">

            <div class="borde-circular">
                <table style="width:100%;">
                    <tbody>
                        <tr>
                            <td><span class="subtitulo">Empleado/a: '.$fila1['legajo'].' '.$fila1['apellido'].' '.$fila1['nombre'].'</span></td>
                        </tr>
                        <tr>
                            <td>En cumplimiento de la legislación, se le notifica que el Período de Descanso Anual correspondiente al
                            año '.$fila1['periodo'].', es de '.$fila1['dias'].' días. Dichas vacaciones comenzarán a regir desde el dia '.$fila1['fecha_desde'].' hasta
                            el '.$fila1['fecha_hasta'].' inclusive. Debiéndose reintegrar a sus tareas el siguiente día hábil.</td>
                        </tr>
                        <tr>
                            <td>Atentamente,</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>Comodoro Rivadavia, '.$fila1['sysdate'].'</td>
                        </tr>
                    </tbody>
                </table>

                <table style="width:100%; margin-top:80px;">
                    <tbody>
                        <tr>
                            <td style="width: 50%; text-align: center">................................................</td>
                            <td style="width: 50%"></td>
                        </tr>
                        <tr>
                            <td style="text-align: center"><span class="subtitulo">Firma superior</span></td>
                            <td style="width: 50%"></td>
                        </tr>
                    </tbody>
                </table>


            </div>

        </div>



        <br/>
        <div style="float: left; width: 100%">

            <div class="borde-circular">
                <table style="width:100%;">
                    <tbody>
                        <tr>
                            <td><span class="subtitulo">Señores: SEIP S.R.L.</span></td>
                        </tr>
                        <tr>
                            <td>Quedo debidamente notificado de la comunicación precedente.</td>
                        </tr>
                    </tbody>
                </table>

                <table style="width:100%; margin-top:80px;">
                    <tbody>
                        <tr>
                            <td style="width: 50%; text-align: center">................................................</td>
                            <td style="width: 50%"></td>
                        </tr>
                        <tr>
                            <td style="text-align: center"><span class="subtitulo">Firma empleado/a</span></td>
                            <td style="width: 50%"></td>
                        </tr>
                    </tbody>
                </table>


            </div>

        </div>





        ',
        \Mpdf\HTMLParserMode::HTML_BODY);






    $namepdf = $fila1['id_suceso'].'-LVA-'.$fila1['apellido'].'-'.$fila1['nombre'].'-'.date('dmY').'.pdf';
    $mpdf->Output($namepdf, 'I'); //I visualizar, D descargar



	?>

