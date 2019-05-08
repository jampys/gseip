<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({

        });


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#txt-form').validate({
            rules: {
                id_contrato: {required: true},
                fecha_desde: {required: true},
                fecha_hasta: {required: true}
            },
            messages:{
                id_contrato: "Seleccione un contrato",
                fecha_desde: "Seleccione la fecha desde",
                fecha_hasta: "Seleccione la fecha hasta"

            }
        });


        $('.input-daterange').datepicker({ //ok
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });



        /*$('#myModal').on("click", "#submit", function(){
            //alert('presiono en exportar');

            if ($("#txt-form").valid()){


                params={};
                params.action = 'partes';
                params.operation = 'checkExportTxt';
                params.fecha_desde = $("#myModal #fecha_desde").val();
                params.fecha_hasta = $("#myModal #fecha_hasta").val();
                params.id_contrato = $("#myModal #id_contrato").val();

                $.post('index.php',params,function(data, status, xhr){

                    //alert(xhr.responseText);

                    if(data[0]['flag'] >=0){

                        $("#myElem").html(data[0]['msg']).addClass('alert alert-success').show();
                        //$("#empleado-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        //$("#msg-container").html('<div id="myElem" class="msg alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><i class="fas fa-check fa-fw"></i></i>&nbsp '+data[0]['msg']+'</div>');
                        /*setTimeout(function() { $("#myElem").hide();
                         //$('#myModal').modal('hide');
                         }, 2000);

                         throw new Error();
                        //location.href="index.php?action=sucesos&operation=txt";
                        location.href="index.php?action=partes&operation=exportTxt&id_contrato="+params.id_contrato+"&fecha_desde="+params.fecha_desde+"&fecha_hasta="+params.fecha_hasta;
                        return false;

                    }else{
                        $("#myElem").html(data[0]['msg']).addClass('alert alert-danger').show();
                        //$("#msg-container").html('<div id="myElem" class="msg alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><i class="fas fa-exclamation-triangle fa-fw"></i></i>&nbsp '+data[0]['msg']+'</div>');
                    }

                }, 'json');



            }




        });*/




        /*$('#myModal').on("click", "#submit1", function(){

            if ($("#txt-form").valid()){

                //var attr = $('#search_empleado option:selected').attr('id_empleado'); // For some browsers, `attr` is undefined; for others,`attr` is false.  Check for both.
                //params.id_empleado = (typeof attr !== typeof undefined && attr !== false)? $('#search_empleado option:selected').attr('id_empleado') : '';
                //var attr = $('#search_empleado option:selected').attr('id_grupo');
                //params.id_grupo = (typeof attr !== typeof undefined && attr !== false)? $('#search_empleado option:selected').attr('id_grupo') : '';
                //params.id_vencimiento = $("#search_vencimiento").val();
                //params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                //params.id_subcontratista = $("#search_subcontratista").val();
                //params.renovado = $('#search_renovado').prop('checked')? 1 : '';

                params={};
                params.action = 'partes';
                params.operation = 'checkExportTxt';
                params.fecha_desde = $("#myModal #fecha_desde").val();
                params.fecha_hasta = $("#myModal #fecha_hasta").val();
                params.id_contrato = $("#myModal #id_contrato").val();
                params.id_user = "<?php echo $_SESSION['id_user']; ?>";

                //var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes, top=200,left=400";
                var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
                //var URL="<?php //echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=sci_plan_version.rptdesign&p_periodo="+periodo+"&p_nro_version="+nro_version+"&p_lugar_trabajo="+lugar_trabajo+"&p_usuario="+usuario+"&p_id_cia="+id_cia;
                var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__report=gseip_crossTab_novedades.rptdesign&p_id_contrato="+params.id_contrato+"&p_fecha_desde="+params.fecha_desde+"&p_fecha_hasta="+params.fecha_hasta+"&p_id_user="+params.id_user;
                //var win = window.open(URL, "_blank", strWindowFeatures);
                var win = window.open(URL, "_blank");


            }

            return false;
        }); */




        //$('.table-responsive').on("click", ".pdf", function(){
        $('#myModal').on("click", "#submit1", function(){
            alert('Crosstab sucesos');

            if ($("#txt-form").valid()){

                params={};
                params.eventos = '12,21'; //($("#search_evento").val()!= null)? $("#search_evento").val() : '';
                params.fecha_desde = $("#myModal #fecha_desde").val();
                params.fecha_hasta = $("#myModal #fecha_hasta").val();
                params.id_contrato = $("#myModal #id_contrato").val();
                params.id_user = "<?php echo $_SESSION['id_user']; ?>";
                var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
                var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=html&__report=gseip_crossTab_sucesos.rptdesign"+
                    "&p_fecha_desde="+params.fecha_desde+
                    "&p_fecha_hasta="+params.fecha_hasta+
                    "&p_id_contrato="+params.id_contrato+
                    "&p_id_evento="+params.eventos+
                    "&p_id_user="+params.id_user;
                var win = window.open(URL, "_blank");


                /*var attr = $('#search_empleado option:selected').attr('id_empleado'); // For some browsers, `attr` is undefined; for others,`attr` is false.  Check for both.
                 params.id_empleado = (typeof attr !== typeof undefined && attr !== false)? $('#search_empleado option:selected').attr('id_empleado') : '';
                 var attr = $('#search_empleado option:selected').attr('id_grupo');
                 params.id_grupo = (typeof attr !== typeof undefined && attr !== false)? $('#search_empleado option:selected').attr('id_grupo') : '';
                 //params.id_vencimiento = $("#search_vencimiento").val();
                 params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                 params.id_contrato = $("#search_contrato").val();
                 params.renovado = $('#search_renovado').prop('checked')? 1 : '';
                 params.id_user = <?php echo $_SESSION['id_user']; ?>
                 //var nro_version = Number($('#version').val());
                 //var lugar_trabajo = $('#lugar_trabajo').val();
                 //var usuario  = "<?php echo $_SESSION["USER_NOMBRE"].' '.$_SESSION["USER_APELLIDO"]; ?>";
                 //var id_cia = "<?php echo $_SESSION['ID_CIA']; ?>";
                 //var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes, top=200,left=400";

                 //var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=sci_plan_version.rptdesign&p_periodo="+periodo+"&p_nro_version="+nro_version+"&p_lugar_trabajo="+lugar_trabajo+"&p_usuario="+usuario+"&p_id_cia="+id_cia;
                 var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=gseip_vencimientos_p.rptdesign&p_id_empleado="+params.id_empleado+"&p_id_grupo="+params.id_grupo+"&p_id_vencimiento="+params.id_vencimiento+"&p_id_contrato="+params.id_contrato+"&p_renovado="+params.renovado+"&p_id_cia="+params.id_empleado+"&p_id_user="+params.id_user;
                 //var win = window.open(URL, "_blank", strWindowFeatures);
                 */

            }


            return false;
        });



        //$('.table-responsive').on("click", ".txt", function(){
        $('#myModal').on("click", "#submit", function(){
            alert('presiono en exportar txt');

            if ($("#txt-form").valid()){

                params={};
                params.id_empleado = $("#search_empleado").val();
                params.eventos = ($("#search_evento").val()!= null)? $("#search_evento").val() : '';
                params.fecha_desde = $("#myModal #fecha_desde").val();
                params.fecha_hasta = $("#myModal #fecha_hasta").val();
                params.id_contrato = $("#myModal #id_contrato").val();
                //location.href="index.php?action=sucesos&operation=txt";
                location.href="index.php?action=sucesos"+
                                        "&operation=txt" +
                                        "&id_empleado="+params.id_empleado+
                                        "&eventos="+params.eventos+
                                        "&p_fecha_desde="+params.fecha_desde+
                                        "&p_fecha_hasta="+params.fecha_hasta+
                                        "&p_id_contrato="+params.id_contrato;

            }

            return false;

        });




    });

</script>





<!-- Modal -->
<fieldset <?php //echo ( PrivilegedUser::dhasPrivilege('CON_ABM', $view->empleado->getDomain() ) )? '' : 'disabled' ?>>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="txt-form" id="txt-form" method="POST" action="index.php">
                    <input type="hidden" name="id" id="id" value="<?php //print $view->client->getId() ?>">

                    <div class="alert alert-info fade in">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <span class="glyphicon glyphicon-tags" ></span>&nbsp Esta pantalla permite exportar las sucesos a formatos .txt (para importar desde BAS) y tabla cruzada.
                    </div>

                    <br/>


                    <div class="form-group required">
                        <label class="control-label" for="empleado">Fecha desde / hasta</label>
                        <div class="input-group input-daterange">
                            <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php //print $view->contrato->getFechaDesde() ?>" placeholder="DD/MM/AAAA" readonly>
                            <div class="input-group-addon">a</div>
                            <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php //print $view->contrato->getFechaHasta() ?>" placeholder="DD/MM/AAAA" readonly>
                        </div>
                    </div>


                    <div class="form-group required">
                        <label class="control-label" for="id_empleado">Contrato</label>
                        <select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" data-live-search="true" data-size="5">
                            <option value="">Seleccione un contrato</option>
                            <?php foreach ($view->contratos as $con){
                                ?>
                                <option value="<?php echo $con['id_contrato']; ?>" >
                                    <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>







                </form>

                <div id="myElem" style="display:none"></div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit" title="txt">&nbsp;<i class="far fa-file-alt fa-fw"></i>&nbsp;</button>
                <button class="btn btn-primary btn-sm" id="submit1" name="submit1" type="submit" title="pdf">&nbsp;<i class="far fa-file-pdf fa-fw"></i>&nbsp;</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



