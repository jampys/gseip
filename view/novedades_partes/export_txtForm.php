<script type="text/javascript">


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
                periodo: {required: true}
            },
            messages:{
                id_contrato: "Seleccione un contrato",
                periodo: "Seleccione un período de liquidación"

            }
        });


        //Select dependiente: al seleccionar contrato carga periodos vigentes
        /*$('#myModal').on('change', '#id_contrato', function(e){
            //alert('seleccionó un contrato');
            //throw new Error();
            params={};
            params.action = "nov_periodos";
            params.operation = "getPeriodos";
            //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
            params.id_contrato = $('#id_contrato').val();
            //params.activos = 1;

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


        });*/


        //al presionar boton de exportar en txt
        $('#myModal').on("click", "#submit", function(){ //ok
            //alert('presiono en exportar');

            if ($("#txt-form").valid()){


                params={};
                params.action = 'partes';
                params.operation = 'checkExportTxt';
                params.id_contrato = $("#myModal #id_contrato").val();
                params.periodo = $("#myModal #periodo").val();
                //alert(params.id_contrato);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(xhr.responseText);

                    if(data[0]['flag'] >=0){

                        $("#myElem").html(data[0]['msg']).addClass('alert alert-success').show();
                        //$("#empleado-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        //$("#msg-container").html('<div id="myElem" class="msg alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><i class="fas fa-check fa-fw"></i></i>&nbsp '+data[0]['msg']+'</div>');
                        /*setTimeout(function() { $("#myElem").hide();
                         //$('#myModal').modal('hide');
                         }, 2000);

                         throw new Error();*/
                        //location.href="index.php?action=partes&operation=exportTxt&id_contrato="+params.id_contrato+"&fecha_desde="+params.fecha_desde+"&fecha_hasta="+params.fecha_hasta;
                        location.href="index.php?action=partes&operation=exportTxt&id_contrato="+params.id_contrato+
                                                                                "&periodo="+params.periodo;
                                                                                //"&fecha_desde="+params.fecha_desde+
                                                                                //"&fecha_hasta="+params.fecha_hasta
                        return false;

                    }else{
                        $("#myElem").html(data[0]['msg']).addClass('alert alert-danger').show();
                        //$("#msg-container").html('<div id="myElem" class="msg alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><i class="fas fa-exclamation-triangle fa-fw"></i></i>&nbsp '+data[0]['msg']+'</div>');
                    }

                }, 'json');



            }



            return false;
        });


        //al presionar boton de exportar en pdf
        //Desactivado temporalmente 31/01/2020
        /*$('#myModal').on("click", "#submit1", function(){

            if ($("#txt-form").valid()){

                //var attr = $('#search_empleado option:selected').attr('id_empleado'); // For some browsers, `attr` is undefined; for others,`attr` is false.  Check for both.
                //params.id_empleado = (typeof attr !== typeof undefined && attr !== false)? $('#search_empleado option:selected').attr('id_empleado') : '';
                //var attr = $('#search_empleado option:selected').attr('id_grupo');
                //params.id_grupo = (typeof attr !== typeof undefined && attr !== false)? $('#search_empleado option:selected').attr('id_grupo') : '';
                //params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                //params.renovado = $('#search_renovado').prop('checked')? 1 : '';

                params={};
                //params.action = 'partes';
                //params.operation = 'checkExportTxt';
                params.id_contrato = $("#myModal #id_contrato").val();
                params.periodo = $("#myModal #periodo").val();
                params.id_user = "<?php echo $_SESSION['id_user']; ?>";

                //var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes, top=200,left=400";
                var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
                //var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__report=gseip_crossTab_novedades.rptdesign&p_id_contrato="+params.id_contrato+"&p_fecha_desde="+params.fecha_desde+"&p_fecha_hasta="+params.fecha_hasta+"&p_id_user="+params.id_user;
                var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__report=gseip_crossTab_novedades.rptdesign&p_id_contrato="+params.id_contrato+
                                                                    //"&p_fecha_desde="+params.fecha_desde+
                                                                    //"&p_fecha_hasta="+params.fecha_hasta+
                                                                    "&p_id_periodo="+params.id_periodo+
                                                                    "&p_id_user="+params.id_user;

                //var win = window.open(URL, "_blank", strWindowFeatures);
                var win = window.open(URL, "_blank");


            }

            return false;
        });*/



        //Exportar novedades para control administracion
        $('#myModal').on("click", "#submit2", function(){

            if ($("#txt-form").valid()){

                params={};
                params.id_contrato = $("#myModal #id_contrato").val();
                params.first_contrato = params.id_contrato[0];
                params.periodo = $("#myModal #periodo").val();
                params.id_user = "<?php echo $_SESSION['id_user']; ?>";
                //alert(params.first_contrato);

                //var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes, top=200,left=400";
                var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
                //var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__report=gseip_crossTab_novedades.rptdesign&p_id_contrato="+params.id_contrato+"&p_fecha_desde="+params.fecha_desde+"&p_fecha_hasta="+params.fecha_hasta+"&p_id_user="+params.id_user;
                var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__report=gseip_nov_control_administracion_"+params.first_contrato+".rptdesign&p_id_contrato="+params.id_contrato+
                    "&p_periodo="+params.periodo+
                    "&p_id_user="+params.id_user;

                //var win = window.open(URL, "_blank", strWindowFeatures);
                var win = window.open(URL, "_blank");


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

                    <div class="form-group required">
                        <label class="control-label" for="id_empleado">Contrato</label>
                        <!--<select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" data-live-search="true" data-size="5">-->
                        <select multiple class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="5" title="Seleccione un contrato">
                            <!--<option value="">Seleccione un contrato</option>-->
                            <?php foreach ($view->contratos as $con){
                                ?>
                                <option value="<?php echo $con['id_contrato']; ?>" >
                                    <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <!--<div class="form-group required">
                        <label for="id_periodo" class="control-label">Período de liquidación</label>
                        <select class="form-control selectpicker show-tick" id="id_periodo" name="id_periodo" title="Seleccione un periodo" data-live-search="true" data-size="5">
                            <!-- se completa dinamicamente desde javascript
                        </select>
                    </div>-->

                    <div class="form-group required">
                        <label class="control-label" for="periodo">Período de liquidación</label>
                        <select class="form-control selectpicker show-tick" id="periodo" name="periodo" data-live-search="true" data-size="5">
                            <option value="">Seleccione un período</option>
                            <?php foreach ($view->periodos_sup as $ps){
                                ?>
                                <option value="<?php echo $ps['periodo']; ?>" >
                                    <?php echo $ps['nombre'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-sm-10">
                                <span class="glyphicon glyphicon-tags"></span>
                                &nbsp;<strong>Control de Novedades Administración:</strong>
                                Exporta novedades en formato de tabla cruzada (empleados/conceptos).
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="submit2" name="submit2" type="submit">&nbsp;<i class="far fa-file-alt fa-lg"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>


                    <div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-sm-10">
                                <span class="glyphicon glyphicon-tags" ></span>
                                &nbsp;<strong>Archivo de texto</strong>
                                <strong class="dp_orange">(RRHH)</strong>
                                <strong>:</strong>
                                Exporta novedades en formato .txt (admisible para BAS).
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="submit" name="submit" type="submit">&nbsp;<i class="far fa-file-alt fa-lg"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>


                </form>

                <div id="myElem" style="display:none">
                    <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
                </div>



            </div>

            <div class="modal-footer">
                <!--<button class="btn btn-primary" id="submit" name="submit" type="submit" title="txt">&nbsp;<i class="far fa-file-alt fa-lg"></i>&nbsp;</button>
                <button class="btn btn-primary" id="submit1" name="submit1" type="submit" title="pdf">&nbsp;<i class="far fa-file-pdf fa-lg"></i>&nbsp;</button>-->
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



