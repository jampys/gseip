<style>

    td.details-control {
        cursor: pointer;
        width: 20px;
        text-align: center;
    }

    td.details-control:before { /* icono de un nodo padre cerrado */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f054";
        color: #01579B;  /* #5fba7d */
    }

    tr.shown td.details-control:before {  /* icono de un nodo padre abierto */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f078";
        color: #01579B;  /* #DD2C00 */
    }

    /*https://stackoverflow.com/questions/23431970/bootstrap-3-truncate-long-text-inside-rows-of-a-table-in-a-responsive-way
    */
    .table td.text { /* para tabla mis objetivos, columna nombre objetivo */
        max-width: 177px;
    }
    .table td.text span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        max-width: 100%;
    }

    span.resaltado{
        font-weight: bold;
    }

    table.hijo td{
        padding-right: 5px;
        /* padding-left: 5px; */
}

/*table.hijo tr td:first-child {
    min-width: 120px;
}*/

    table.hijo td img.profile-image {
        height: 70px;
        width: 70px;
        margin: 5px;
    }


</style>


<script type="text/javascript">


    $(document).ready(function(){



        $('#example, #example1').on('click', 'td.details-control', function (e) {

            var t = $(this).closest('table');
            var tr = $(this).closest('tr');

            params={};
            params.action = "index";
            params.operation = "getObjetivo";
            params.id_objetivo = $(this).closest('tr').attr('data-id');

            $.ajax({
                url:"index.php",
                type:"post",
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    //alert(Object.keys(data).length);

                    if ( tr.hasClass('shown') ) {
                        //alert('verde');
                        // This row is already open - close it
                        //tr.find('td').eq(0).html('<i class="fas fa-plus-circle fa-fw"></i>').removeClass('dp_red').addClass('dp_green');
                        tr.next('tr').hide();
                        tr.removeClass('shown');
                    }
                    else {
                        // Open this row
                        //alert('rojo');
                        //tr.find('td').eq(0).html('<i class="fas fa-minus-circle fa-fw"></i>').removeClass('dp_green').addClass('dp_red');
                        tr.after('<tr><td colspan="4">'+format(data)+'</td></tr>').show();
                        tr.addClass('shown');
                    }

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });

        } );


        /* Formato del detalle del objetivo */
        function format ( d ) {
            //https://stackoverflow.com/questions/8749236/create-table-with-jquery-append

            var subTabla ='';

            if(Object.keys(d).length > 0 ){

                var puesto = (d[0]['id_puesto'])? d[0]['puesto'] : '';
                var area = (d[0]['id_area'])? d[0]['area'] : '';
                var contrato = (d[0]['id_contrato'])? d[0]['contrato'] : '';

                var subTabla = '<table class="hijo" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; margin-left: 20px">'+
                                    '<tr><td colspan="2">'+d[0]['nombre']+'</td></tr>'+
                                    '<tr><td style="min-width: 120px"><span class="resaltado">Puesto<span></td><td>'+puesto+'</td></tr>'+
                                    '<tr><td><span class="resaltado">Área<span></td><td>'+area+'</td></tr>'+
                                    '<tr><td><span class="resaltado">Contrato<span></td><td>'+contrato+'</td></tr>'+
                                    '<tr><td><span class="resaltado">Indicador<span></td><td>'+d[0]['indicador']+'</td></tr>'+
                                    '<tr><td><span class="resaltado">Meta<span></td><td>'+d[0]['meta']+'</td></tr>'+
                                    '<tr><td><span class="resaltado">Valor<span></td><td>'+d[0]['meta_valor']+'</td></tr>'+
                                    '<tr><td><span class="resaltado">Frecuencia<span></td><td>'+d[0]['frecuencia']+'</td></tr>'+
                                    '<tr><td><span class="resaltado">Resp. ejecución<span></td><td>'+d[0]['responsable_ejecucion']+'</td></tr>'+
                                    '<tr><td><span class="resaltado">Resp. seguimiento<span></td><td>'+d[0]['responsable_seguimiento']+'</td></tr>'+
                               '</table>';

            }

            return subTabla;

        }



        //tabla de proximos cumpleaños. Abrir detalle
        $('#example2').on('click', 'td.details-control', function (e) {
            //alert('detalle cumple');

            //var t = $(this).closest('table');
            var tr = $(this).closest('tr');

            params={};
            params.action = "index";
            params.operation = "getProfile";
            params.id_empleado = $(this).closest('tr').attr('data-id');

            $.ajax({
                url:"index.php",
                type:"post",
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    var tr_abierta = $("#example2 tr[class*='shown']");
                    tr_abierta.next('tr').hide();
                    tr_abierta.removeClass('shown');

                    tr.after('<tr><td colspan="3">'+format1(data)+'</td></tr>').show();
                    tr.addClass('shown');

                    //alert(Object.keys(data).length);

                    /*if ( tr.hasClass('shown') ) {
                        //alert('verde');
                        // This row is already open - close it
                        //tr.find('td').eq(0).html('<i class="fas fa-plus-circle fa-fw"></i>').removeClass('dp_red').addClass('dp_green');
                        tr.next('tr').hide();
                        tr.removeClass('shown');
                    }
                    else {
                        // Open this row
                        //alert('rojo');
                        //tr.find('td').eq(0).html('<i class="fas fa-minus-circle fa-fw"></i>').removeClass('dp_green').addClass('dp_red');
                        tr.after('<tr><td colspan="3">'+format1(data)+'</td></tr>').show();
                        tr.addClass('shown');
                    }*/

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });

        } );




        /* Formato del detalle del cumpleaños */
        function format1 ( d ) {
            //https://stackoverflow.com/questions/8749236/create-table-with-jquery-append

            var subTabla ='';

            if(Object.keys(d).length > 0 ){

                var contrato = (d[0]['id_contrato'])? d[0]['contrato'] : '';
                var puesto = (d[0]['id_puesto'])? d[0]['puesto'] : '';
                var lugar_trabajo = (d[0]['id_localidad'])? d[0]['lugar_trabajo'] : '';

                var subTabla = '<table class="hijo" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; margin-left: 20px">'+
                    '<tr><td colspan="2"><img src="'+d[0]['profile_picture']+'" class="profile-image img-circle"></td></tr>'+
                    '<tr><td style="min-width: 20px"><span class="resaltado">Contrato<span></td><td>'+contrato+'</td></tr>'+
                    '<tr><td><span class="resaltado">Puesto<span></td><td>'+puesto+'</td></tr>'+
                    '<tr><td><span class="resaltado">Lugar trabajo<span></td><td>'+lugar_trabajo+'</td></tr>'+
                    '</table>';

            }

            return subTabla;

        }




    });

</script>




<div class="row" style="height: 50px"> <!-- abre fila en blanco -->

</div> <!-- cierra fila en blanco -->




<div class="row"> <!-- abre fila principal -->


    <div class="col-md-6"> <!-- abre columna izquierda -->


        <div class="row">  <!-- abre fila 1 columna izquierda -->


            <div class="col-md-12">


                <div class="panel-group">

                    <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">Mis objetivos&nbsp;<?php echo date('Y'); ?>&nbsp;&nbsp;<span class="badge" style="background-color: #777"><?php echo sizeof($view->objetivos); ?></span></div>
                        <div class="panel-body">


                            <!-- Table -->
                            <?php if(isset($view->objetivos) && sizeof($view->objetivos) > 0) { ?>

                                <table id="example" class="table table-striped table-condensed table-hover" cellspacing="0" width="100%">

                                    <tbody>
                                    <?php foreach ($view->objetivos as $rp):   ?>
                                        <tr data-id="<?php echo $rp['id_objetivo']; ?>"
                                            id_objetivo="<?php echo $rp['id_objetivo'];?>"
                                            >
                                            <td class="details-control col-md-1"></td>
                                            <td class="col-md-3"><span  class="resaltado"><?php echo $rp['codigo'];?></span></td>
                                            <td class="text"><span><?php echo $rp['nombre']; ?></span></td>
                                            <td class="col-md-2">
                                                <div class="progress" style="margin-bottom: 0px">
                                                    <div class="progress-bar progress-bar-striped active <?php echo Soporte::getProgressBarColor($rp['progreso']);?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($rp['progreso'] <= 100)? $rp['progreso']:100; ?>%; min-width: 2em">
                                                        <?php echo $rp['progreso']; ?>%
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                    <?php endforeach;  ?>
                                    </tbody>
                                </table>

                            <?php }else{ ?>

                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle fa-fw"></i> No tiene objetivos fijados para el período vigente.
                                </div>

                            <?php } ?>



                        </div>


                    </div>


                </div>




            </div>


        </div> <!-- cierra fila 1 columna izquierda -->




    </div> <!-- cierra columna izquierda -->




    <div class="col-md-6"> <!-- abre columna derecha -->


        <div class="row"> <!-- abre fila 1 columna derecha -->



            <div class="col-md-12">




                <div class="panel-group">


                    <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">Responsable del seguimiento de objetivos &nbsp;<?php echo date('Y'); ?>&nbsp;&nbsp;<span class="badge" style="background-color: #777"><?php echo sizeof($view->objetivos1); ?></span></div>
                        <div class="panel-body">


                            <!-- Table -->
                            <?php if(isset($view->objetivos1) && sizeof($view->objetivos1) > 0) { ?>

                                <table id="example1" class="table table-striped table-condensed table-hover" cellspacing="0" width="100%">

                                    <tbody>
                                    <?php foreach ($view->objetivos1 as $rp):   ?>
                                        <tr data-id="<?php echo $rp['id_objetivo']; ?>"
                                            id_objetivo="<?php echo $rp['id_objetivo'];?>"
                                            >
                                            <td class="details-control col-md-1"></td>
                                            <td class="col-md-3"><span  class="resaltado"><?php echo $rp['codigo'];?></span></td>
                                            <td class="text"><span><?php echo $rp['nombre']; ?></span></td>
                                            <td class="col-md-2">
                                                <div class="progress" style="margin-bottom: 0px">
                                                    <div class="progress-bar progress-bar-striped active <?php echo Soporte::getProgressBarColor($rp['progreso']);?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($rp['progreso'] <= 100)? $rp['progreso']:100; ?>%; min-width: 2em">
                                                        <?php echo $rp['progreso']; ?>%
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                    <?php endforeach;  ?>
                                    </tbody>
                                </table>

                            <?php }else{ ?>

                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle fa-fw"></i> No tiene definidos objetivos de los que sea responsable para el período vigente.
                                </div>

                            <?php } ?>



                        </div>


                    </div>

                </div>



            </div>



        </div> <!-- cierra fila 1 columna derecha -->




        <div class="row"> <!-- abre fila 2 columna derecha -->

            <div class="col-md-5">
            </div>

            <div class="col-md-7">




                <div class="panel-group">


                    <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading"><i class="fas fa-birthday-cake fa-lg"></i>&nbsp;&nbsp;Próximos cumpleaños</div>
                        <div class="panel-body">


                            <!-- Table -->
                            <?php if(isset($view->cumpleaños) && sizeof($view->cumpleaños) > 0) { ?>

                                <table id="example2" class="table table-striped table-condensed table-hover" cellspacing="0" width="100%">

                                    <tbody>
                                    <?php foreach ($view->cumpleaños as $rp):   ?>
                                        <tr data-id="<?php echo $rp['id_empleado']; ?>"
                                            >
                                            <td class="details-control col-md-1"></td>
                                            <td class="col-md-1"><span  class="resaltado"><?php echo $rp['cumpleaños'];?></span></td>
                                            <td class="text" style="max-width: 150px"><span><?php echo $rp['apellido'].' '.$rp['nombre']; ?></span></td>
                                        </tr>
                                    <?php endforeach;  ?>
                                    </tbody>
                                </table>

                            <?php }else{ ?>

                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle fa-fw"></i> No hay cumpleaños los próximos <?php echo $view->dias; ?> dias.
                                </div>

                            <?php } ?>



                        </div>


                    </div>

                </div>



            </div>



        </div> <!-- cierra fila 2 columna derecha -->




    </div>  <!-- abre columna derecha -->




</div> <!-- cierra fila principal -->










