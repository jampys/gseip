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

$fila4['descripcion'] = $nc->getDescripcion();


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



        <div style="float: left; width: 100%" >
        <!--&nbsp;<img src="valve.png" alt="Smiley face" height="15" width="15">-->
            <span class="titulo">Patrón de Calibración</span>

        <div class="borde-circular" >
        <table style="width:100%">
            <tbody>
                <tr>
                    <td style="width: 30%"><span class="subtitulo">Marca</span></td>
                    <td style="width: 20%">'.$fila['i_marca'].'</td>
                    <td style="width: 30%"><span class="subtitulo">Rango de Medición</span></td>
                    <td style="width: 20%">'.$fila['i_rango'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">N° de Serie</span></td>
                    <td>'.$fila['i_nombre'].'</td>
                    <td><span class="subtitulo">Fecha de Calibración</span></td>
                    <td>'.$fila['i_fechacalib'].'</td>
                </tr>
            </tbody>
        </table>
        </div>

        </div>


        <br/>
        <div style="float: left; width: 100%">
            <span class="titulo">Datos de la válvula</span>

            <div class="borde-circular">
            <table style="width:100%">
            <tbody>
                <tr>
                    <td style="width: 30%"><span class="subtitulo">Marca</span></td>
                    <td style="width: 20%">'.$fila['marca'].'</td>
                    <td style="width: 30%"><span class="subtitulo">Modelo</span></td>
                    <td style="width: 20%">'.$fila['modelo'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">N° de Serie SEIP</span></td>
                    <td>'.$fila['nroserievalv'].'</td>
                    <td><span class="subtitulo">N° de Serie fabricante</span></td>
                    <td>'.$fila['nroserie_fabricante'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Tipo Conexión E/S</span></td>
                    <td>'.$fila['v_tipo_conexion'].'</td>
                    <td><span class="subtitulo">Orificio Tipo <b>/</b> Área</span></td>
                    <td>'.$fila['v_tipo_orificio']." <b>/</b> ".$fila['v_area_orificio'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Tamaño <b>/</b> Serie Entrada</span></td>
                    <td>'.$fila['diametro_entrada']." <b>/</b> ".$fila['serie_entrada'].'</td>
                    <td><span class="subtitulo">Tamaño <b>/</b> Serie Salida</span></td>
                    <td>'.$fila['diametro_salida']." <b>/</b> ".$fila['serie_salida'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">N° de Equipo SAP</span></td>
                    <td>'.$fila['sap'].'</td>
                    <td><span class="subtitulo">Cuerpo <b>/</b> Bonete</span></td>
                    <td>'.$fila['cuerpo'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Platillos <b>/</b> Paquetes <b>/</b> Arreglo</span></td>
                    <td>'.$fila['v_ppa'].'</td>
                    <td><span class="subtitulo">Resorte</span></td>
                    <td>'.$fila['v_resorte'].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Fuelle</span></td>
                    <td>'.$fila['v_fuelle'].'</td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        </div>


        <br/>

        <div style="float: left; width: 100%">
            <span class="titulo">Condiciones de ingreso de la válvula</span>

            <div class="borde-circular">
            <table style="width:100%">
            <tbody>
                <tr>
                    <td style="width: 50%"><input type="checkbox" id="" value="" '.$fila["condnormal"].'>&nbsp;<span class="subtitulo">Normal</span></td>
                    <td style="width: 50%"><input type="checkbox" id="" value="" '.$fila['condresiduos'].' >&nbsp;<span class="subtitulo">Residuos</span></td>
                </tr>
                <tr>
                    <td><input type="checkbox" id="" value="" '.$fila['condcorrosion'].'>&nbsp;<span class="subtitulo">Corrosión</span></td>
                    <td><input type="checkbox" id="" value="" '.$fila['condprecinto'].' >&nbsp;<span class="subtitulo">Precinto Cortado</span></td>
                </tr>
                <tr>
                    <td><input type="checkbox" id="" value="" '.$fila['condempetrolada'].' >&nbsp;<span class="subtitulo">Empetrolada</span></td>
                    <td><input type="checkbox" id="" value="" '.$fila['conddañoe'].' >&nbsp;<span class="subtitulo">Daño Estructural</span></td>
                </tr>
            </tbody>
        </table>
        </div>

        </div>

        <br/>
        <div style="float: left; width: 100%">
            <span class="titulo">Pre Pop Test</span>

            <div class="borde-circular">
            <table style="width:100%">
            <tbody>
                <tr>
                    <td style="width: 30%"><span class="subtitulo">N° de Certificado</span></td>
                    <td style="width: 20%">'.$fila1['nrocertificado'].'</td>
                    <td style="width: 30%"><span class="subtitulo">Presión de Apertura Ensayo 1</span></td>
                    <td style="width: 20%">'.$fila1["mayorpresion"].'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Fecha</span></td>
                    <td>'.$fila1['fechacalib'].'</td>
                    <td><span class="subtitulo">Presión de Apertura Ensayo 2</span></td>
                    <td>'.$fila1["mayorpresion2"].'</td>
                </tr>
            </tbody>
            </table>
            </div>
        </div>




        <br/>
        <div style="float: left; width: 100%">
            <span class="titulo">Condiciones de Calibración</span>

            <div class="borde-circular">
            <table style="width:100%">
            <tbody>
                <tr>
                    <td style="width: 30%"><span class="subtitulo">Tolerancia</span></td>
                    <td style="width: 20%">'.$tolerancia.'</td>
                    <td style="width: 30%"><span class="subtitulo">Fluido</span></td>
                    <td style="width: 20%">'.$fila["fluidotobera"].'</td>
                </tr>
            </tbody>
        </table>
        </div>

        </div>






        <br/>
        <div style="float: left; width: 100%">
            <span class="titulo">Calibración</span>

            <div class="borde-circular">
            <table style="width:100%">
            <tbody>
                <tr>
                    <td style="width: 30%"><span class="subtitulo">Presión de Apertura Ensayo 1</span></td>
                    <td style="width: 20%">'.$pmax1.'</td>
                    <td style="width: 30%"><span class="subtitulo">Presión Promedio de Apertura</span></td>
                    <td style="width: 20%">'.$pprom.'</td>
                </tr>
                <tr>
                    <td><span class="subtitulo">Presión de Apertura Ensayo 2</span></td>
                    <td>'.$pmax2.'</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        </div>

        </div>


        <br/>
        <div class="borde-circular" style="float: left; width: 100%">
            <img src="pdf/Imagen/'.$_SESSION['ini']['unidad_negocio']['un'].'/imagen_'.$fila['id_calibracion'].'.png">
        </div>



        <br/>
        <br/>
        <br/>
        <br/>

        <div style="float: left; width: 100%">
            <span class="titulo">Descripción de las tareas realizadas</span>

            <div class="borde-circular">
            <table style="width:100%">
            <tbody>
                <tr>
                    <td style="width: 50%"><input type="checkbox" id="" value="" '.$fila["valvactuada"].'>&nbsp;<span class="subtitulo">Válvula actuada</span></td>
                    <td style="width: 50%"><input type="checkbox" id="" value="" '.$fila['contratuerca'].' >&nbsp;<span class="subtitulo">Contratuerca</span></td>
                </tr>
                <tr>
                    <td><input type="checkbox" id="" value="" '.$fila['resorte'].'>&nbsp;<span class="subtitulo">Resorte Principal</span></td>
                    <td><input type="checkbox" id="" value="" '.$fila['cierrepollera'].' >&nbsp;<span class="subtitulo">Cierre de Pollera</span></td>
                </tr>
                <tr>
                    <td><input type="checkbox" id="" value="" '.$fila['obturador'].' >&nbsp;<span class="subtitulo">Obturador</span></td>
                    <td><input type="checkbox" id="" value="">&nbsp;<span class="subtitulo">Correc. Temp. de trabajo</span></td>
                </tr>
                <tr>
                    <td><input type="checkbox" id="" value="" '.$fila['asiento'].' >&nbsp;<span class="subtitulo">Asiento</span></td>
                    <td><input type="checkbox" id="" value="" '.$fila['pintura'].'>&nbsp;<span class="subtitulo">Pintura</span></td>
                </tr>
                <tr>
                    <td><input type="checkbox" id="" value="" '.$fila['fuelle'].' >&nbsp;<span class="subtitulo">Fuelle</span></td>
                    <td><input type="checkbox" id="" value="" '.$fila['precintos'].' >&nbsp;<span class="subtitulo">Precinto Superior</span></td>
                </tr>
            </tbody>
        </table>
        </div>

        </div>


        <br/>
        <div style="float: left; width: 100%">
            <span class="titulo">Comentarios</span>

            <div class="borde-circular">
            <table style="width:100%; margin-top:10px">
            <tbody>
                <tr>
                    <td>'.$fila['comentarios'].'</td>
                </tr>
            </tbody>
        </table>
        </div>

        </div>



        <br/>
        <div class="borde-circular" style="width: 100%; background-color: #ffffff">

        <table style="width:100%; margin-top:10px">
            <tbody>
                <tr>
                    <td style="width: 50%; text-align: center">
                        <!--<img src="'.$firma.'" height="90px">-->
                        <img src="data:image/jpeg;base64,'.$firma.'" height="90px">
                        <br/>
                        <span>'.$fila['u_apellido'].' '.$fila['u_nombre'].'</span>
                    </td>
                    <td style="width: 50%; text-align: center">
                </tr>
                <tr>
                    <td style="text-align: center"><span class="subtitulo">Responsable de Calibración</span></td>
                    <td style="text-align: center"><span class="subtitulo">Inspección Cliente</span></td>
                </tr>
            </tbody>
        </table>


        </div>




        ',
        \Mpdf\HTMLParserMode::HTML_BODY);
    //$namepdf="Valv-".$fila['nroserie']."_IdCalib-".$fila['id_calibracion']."_Fecha-".date('d-m-Y_H-i-s').".pdf";
    $namepdf = $fila['nrocertificado'].'-v'.$fila['nroserie'].'-'.date('dmY').'.pdf';
    $mpdf->Output($namepdf, 'I'); //I visualizar, D descargar



	?>

