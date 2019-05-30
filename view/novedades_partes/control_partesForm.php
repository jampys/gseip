﻿<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#txt-form').validate({
            rules: {
                id_contrato: {required: true},
                id_periodo: {required: function(item){return $('#id_periodo').attr('validar') == 1}},
                fecha_desde: {required: function(item){return $('#fecha_desde').attr('validar') == 1}},
                fecha_hasta: {required: function(item){return $('#fecha_desde').attr('validar') == 1}}
                //id_evento: {required: true}
            },
            messages:{
                id_contrato: "Seleccione un contrato",
                id_periodo: "Seleccione un período",
                fecha_desde: "Seleccione la fecha desde",
                fecha_hasta: "Seleccione la fecha hasta"
                //id_evento: "Seleccione un evento"

            }
        });


        $('.input-daterange').datepicker({ //ok
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });



        //Select dependiente: al seleccionar contrato carga periodos vigentes
        $('#myModal').on('change', '#id_contrato', function(e){
            //alert('seleccionó un contrato');
            //throw new Error();
            params={};
            params.action = "nov_periodos";
            params.operation = "getPeriodos";
            //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
            params.id_contrato = $('#id_contrato').val();
            params.activos = 1;

            $('#myModal #id_periodo').empty();


            $.ajax({
                url:"index.php",
                type:"post",
                //data:{"action": "parte-empleado-concepto", "operation": "getConceptos", "id_objetivo": <?php //print $view->objetivo->getIdObjetivo() ?>},
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    if(Object.keys(data).length > 0){

                        $.each(data, function(indice, val){
                            var label = data[indice]["nombre"]+' ('+data[indice]["fecha_desde"]+' - '+data[indice]["fecha_hasta"]+')';
                            $("#myModal #id_periodo").append('<option value="'+data[indice]["id_periodo"]+'"'
                            +' fecha_desde="'+data[indice]["fecha_desde"]+'"'
                            +' fecha_hasta="'+data[indice]["fecha_hasta"]+'"'
                            +'>'+label+'</option>');

                        });

                        //si es una edicion o view, selecciona el concepto.
                        //$("#id_concepto").val(<?php //print $view->concepto->getIdConceptoConvenioContrato(); ?>);
                        $('#myModal #id_periodo').selectpicker('refresh');

                    }

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });


        });


        

        //para exportar a pdf
        //$('.table-responsive').on("click", ".pdf", function(){
        $('#myModal').on("click", "#submit1", function(){
            //alert('Crosstab sucesos');
            $('#id_periodo').attr('validar', 0);
            $('#fecha_desde').attr('validar', 1);
            $('#fecha_hasta').attr('validar', 1);


            if ($("#txt-form").valid()){

                params={};
                //params.eventos = $("#id_evento").val();
                params.eventos = ($("#myModal #id_evento").val()!= null)? $("#myModal #id_evento").val() : '';
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


        //para exportar a txt
        //$('.table-responsive').on("click", ".txt", function(){
        $('#myModal').on("click", "#submit", function(){
            //alert('presiono en exportar txt');
            $('#id_periodo').attr('validar', 1);
            $('#fecha_desde').attr('validar', 0);
            $('#fecha_hasta').attr('validar', 0);

            if ($("#txt-form").valid()){

                params={};
                //params.id_empleado = $("#search_empleado").val();
                //params.eventos = ($("#search_evento").val()!= null)? $("#search_evento").val() : '';
                //params.eventos = ($("#myModal #id_evento").val()!= null)? $("#myModal #id_evento").val() : '';
                //params.fecha_desde = $("#myModal #fecha_desde").val();
                //params.fecha_hasta = $("#myModal #fecha_hasta").val();
                params.id_contrato = $("#myModal #id_contrato").val();
                params.id_periodo = $("#myModal #id_periodo").val();
                //location.href="index.php?action=sucesos&operation=txt";
                location.href="index.php?action=sucesos"+
                                        "&operation=txt" +
                                        //"&id_empleado="+params.id_empleado+
                                        //"&eventos="+params.eventos+
                                        //"&p_fecha_desde="+params.fecha_desde+
                                        //"&p_fecha_hasta="+params.fecha_hasta+
                                        "&id_contrato="+params.id_contrato+
                                        "&id_periodo="+params.id_periodo;

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

                    <!--<br/>-->


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


                    <div class="form-group">
                        <label for="id_periodo" class="control-label">Período de liquidación</label>
                        <select class="form-control selectpicker show-tick" id="id_periodo" name="id_periodo" title="Seleccione un periodo" data-live-search="true" data-size="5">
                            <!-- se completa dinamicamente desde javascript  -->
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="search_empleado" class="control-label">Empleado</label>
                        <select class="form-control selectpicker show-tick" id="search_empleado" name="search_empleado" data-live-search="true" data-size="5">
                            <option value="">Seleccione un empleado</option>
                            <?php foreach ($view->empleados as $em){
                                ?>
                                <option value="<?php echo $em['id_empleado']; ?>" data-icon="fas fa-user fa-sm fa-fw" >
                                    <?php echo $em['apellido']." ".$em['nombre'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="empleado">Fecha desde / hasta</label>
                        <div class="input-group input-daterange">
                            <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php //print $view->contrato->getFechaDesde() ?>" placeholder="DD/MM/AAAA" readonly>
                            <div class="input-group-addon">a</div>
                            <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php //print $view->contrato->getFechaHasta() ?>" placeholder="DD/MM/AAAA" readonly>
                        </div>
                    </div>


                </form>

                <div id="myElem" style="display:none"></div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit" title="txt">&nbsp;<i class="far fa-file-alt fa-lg"></i>&nbsp;</button>
                <button class="btn btn-primary btn-sm" id="submit1" name="submit1" type="submit" title="pdf">&nbsp;<i class="far fa-file-pdf fa-lg"></i>&nbsp;</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



