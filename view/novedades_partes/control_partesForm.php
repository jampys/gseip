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
                id_periodo: {required: true},
                id_empleado: {required: function(item){return $('#id_empleado').attr('validar') == 1}},
                id_concepto: {required: function(item){return $('#id_concepto').attr('validar') == 1}}
                //fecha_desde: {required: function(item){return $('#fecha_desde').attr('validar') == 1}},
                //fecha_hasta: {required: function(item){return $('#fecha_desde').attr('validar') == 1}}
            },
            messages:{
                id_contrato: "Seleccione un contrato",
                id_periodo: "Seleccione un período",
                id_empleado: "Seleccione un empleado",
                id_concepto: "Seleccione un concepto"
                //fecha_desde: "Seleccione la fecha desde",
                //fecha_hasta: "Seleccione la fecha hasta"

            }
        });



        //Select dependiente: al seleccionar contrato carga periodos vigentes
        $('#myModal').on('change', '#id_contrato', function(e){
            //alert('seleccionó un contrato');
            //throw new Error();
            params={};
            params.action = "partes2";
            params.operation = "getPeriodosAndEmpleados";
            //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
            params.id_contrato = $('#id_contrato').val();
            //params.activos = 1;

            $('#myModal #id_periodo').empty();
            $('#myModal #id_empleado').empty();


            $.ajax({
                url:"index.php",
                type:"post",
                //data:{"action": "parte-empleado-concepto", "operation": "getConceptos", "id_objetivo": <?php //print $view->objetivo->getIdObjetivo() ?>},
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    //completo select de periodos
                    if(Object.keys(data["periodos"]).length > 0){
                        $.each(data["periodos"], function(indice, val){
                            var label = data["periodos"][indice]["nombre"]+' ('+data["periodos"][indice]["fecha_desde"]+' - '+data["periodos"][indice]["fecha_hasta"]+')';
                            $("#myModal #id_periodo").append('<option value="'+data["periodos"][indice]["id_periodo"]+'"'
                            +' fecha_desde="'+data["periodos"][indice]["fecha_desde"]+'"'
                            +' fecha_hasta="'+data["periodos"][indice]["fecha_hasta"]+'"'
                            +'>'+label+'</option>');
                        });
                        $('#myModal #id_periodo').selectpicker('refresh');
                    }

                    //completo select de empleados
                    if(Object.keys(data["empleados"]).length > 0){
                        $.each(data["empleados"], function(indice, val){
                            var label = data["empleados"][indice]["apellido"]+' '+data["empleados"][indice]["nombre"];
                            $("#myModal #id_empleado").append('<option value="'+data["empleados"][indice]["id_empleado"]+'"'
                            +' id_convenio="'+data["empleados"][indice]["id_convenio"]+'"'
                            //+' fecha_hasta="'+data["periodos"][indice]["fecha_hasta"]+'"'
                            +'>'+label+'</option>');
                        });
                        $('#myModal #id_empleado').selectpicker('refresh');
                    }

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });


        });



        //Select dependiente: al seleccionar emppleado carga conceptos
        $('#myModal').on('change', '#id_empleado', function(e){ //ok

            params={};
            params.action = "parte-empleado-concepto";
            params.operation = "getConceptos";
            params.id_convenio = $('#myModal #id_empleado option:selected').attr('id_convenio');
            params.id_contrato = $('#myModal #id_contrato').val();

            $('#myModal #id_concepto').empty();


            $.ajax({
                url:"index.php",
                type:"post",
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    if(Object.keys(data).length > 0){
                        $.each(data, function(indice, val){
                            var label = data[indice]["concepto"]+' ('+data[indice]["codigo"]+') '+data[indice]["convenio"];
                            $("#id_concepto").append('<option value="'+data[indice]["id_concepto_convenio_contrato"]+'">'+label+'</option>');

                        });
                        $('#myModal #id_concepto').selectpicker('refresh');
                    }

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });


        });


        

        //reporte: concepto en enpleado
        $('#myModal').on("click", "#submit1", function(){
            //alert('Crosstab sucesos');
            //$('#txt-form').validate().resetForm(); //limpiar error input validate
            $('#txt-form').find('input').closest('.form-group').removeClass('has-error');
            $('#txt-form .tooltip').remove(); //limpiar error tooltip validate
            $('#id_empleado').attr('validar', 1);
            $('#id_concepto').attr('validar', 1);


            if ($("#txt-form").valid()){

                params={};
                //params.eventos = ($("#myModal #id_evento").val()!= null)? $("#myModal #id_evento").val() : '';
                //params.fecha_desde = $("#myModal #fecha_desde").val();
                //params.fecha_hasta = $("#myModal #fecha_hasta").val();
                params.id_contrato = $("#myModal #id_contrato").val();
                params.id_periodo = $("#myModal #id_periodo").val();
                params.id_empleado = $("#myModal #id_empleado").val();
                params.id_concepto_convenio_contrato = $("#myModal #id_concepto").val();
                params.id_user = "<?php echo $_SESSION['id_user']; ?>";
                var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
                var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=html&__report=gseip_nov_control_conceptos.rptdesign"+
                    //"&p_fecha_desde="+params.fecha_desde+
                    //"&p_fecha_hasta="+params.fecha_hasta+
                    "&p_id_contrato="+params.id_contrato+
                    "&p_id_periodo="+params.id_periodo+
                    "&p_id_empleado="+params.id_empleado+
                    "&p_id_concepto_convenio_contrato="+params.id_concepto_convenio_contrato+
                    "&p_id_user="+params.id_user;
                var win = window.open(URL, "_blank");

            }


            return false;
        });



        //reporte: conceptos en enpleado
        $('#myModal').on("click", "#submit5", function(){
            //alert('Crosstab sucesos');
            //$('#txt-form').validate().resetForm(); //limpiar error input validate
            $('#txt-form').find('input').closest('.form-group').removeClass('has-error');
            $('#txt-form .tooltip').remove(); //limpiar error tooltip validate
            $('#id_empleado').attr('validar', 1);
            $('#id_concepto').attr('validar', 0);


            if ($("#txt-form").valid()){

                params={};
                //params.eventos = ($("#myModal #id_evento").val()!= null)? $("#myModal #id_evento").val() : '';
                params.id_contrato = $("#myModal #id_contrato").val();
                params.id_periodo = $("#myModal #id_periodo").val();
                params.id_empleado = $("#myModal #id_empleado").val();
                params.id_concepto_convenio_contrato = $("#myModal #id_concepto").val();
                params.id_user = "<?php echo $_SESSION['id_user']; ?>";
                var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
                var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=html&__report=gseip_nov_control_conceptos2.rptdesign"+
                        //"&p_fecha_desde="+params.fecha_desde+
                        //"&p_fecha_hasta="+params.fecha_hasta+
                    "&p_id_contrato="+params.id_contrato+
                    "&p_id_periodo="+params.id_periodo+
                    "&p_id_empleado="+params.id_empleado+
                    //"&p_id_concepto_convenio_contrato="+params.id_concepto_convenio_contrato+
                    "&p_id_user="+params.id_user;
                var win = window.open(URL, "_blank");

            }


            return false;
        });


        //reporte OTs
        $('#myModal').on("click", "#submit2", function(){
            //alert('Crosstab sucesos');
            //$('#txt-form').validate().resetForm(); //limpiar error input validate
            $('#txt-form').find('input').closest('.form-group').removeClass('has-error');
            $('#txt-form .tooltip').remove(); //limpiar error tooltip validate
            $('#id_empleado').attr('validar', 0);
            $('#id_concepto').attr('validar', 0);


            if ($("#txt-form").valid()){

            params={};
            //params.eventos = ($("#myModal #id_evento").val()!= null)? $("#myModal #id_evento").val() : '';
            //params.fecha_desde = $("#myModal #fecha_desde").val();
            //params.fecha_hasta = $("#myModal #fecha_hasta").val();
            params.id_contrato = $("#myModal #id_contrato").val();
            params.id_periodo = $("#myModal #id_periodo").val();
            params.id_user = "<?php echo $_SESSION['id_user']; ?>";
            var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
            var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=html&__report=gseip_nov_control_ots.rptdesign"+
                    //"&p_fecha_desde="+params.fecha_desde+
                    //"&p_fecha_hasta="+params.fecha_hasta+
                "&p_id_contrato="+params.id_contrato+
                "&p_id_periodo="+params.id_periodo+
                //"&p_id_empleado="+params.id_empleado+
                //"&p_id_concepto_convenio_contrato="+params.id_concepto_convenio_contrato+
                "&p_id_user="+params.id_user;
            var win = window.open(URL, "_blank");

            }


            return false;
        });



        //reporte faltantes
        $('#myModal').on("click", "#submit3", function(){
            //alert('Crosstab sucesos');
            //$('#txt-form').validate().resetForm(); //limpiar error input validate
            $('#txt-form').find('input').closest('.form-group').removeClass('has-error');
            $('#txt-form .tooltip').remove(); //limpiar error tooltip validate
            $('#id_empleado').attr('validar', 0);
            $('#id_concepto').attr('validar', 0);


            if ($("#txt-form").valid()){

                params={};
                //params.eventos = ($("#myModal #id_evento").val()!= null)? $("#myModal #id_evento").val() : '';
                //params.fecha_desde = $("#myModal #fecha_desde").val();
                //params.fecha_hasta = $("#myModal #fecha_hasta").val();
                params.id_contrato = $("#myModal #id_contrato").val();
                params.id_periodo = $("#myModal #id_periodo").val();
                params.id_user = "<?php echo $_SESSION['id_user']; ?>";
                var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
                var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=html&__report=gseip_nov_control_faltantes.rptdesign"+
                        //"&p_fecha_desde="+params.fecha_desde+
                        //"&p_fecha_hasta="+params.fecha_hasta+
                    "&p_id_contrato="+params.id_contrato+
                    "&p_id_periodo="+params.id_periodo+
                        //"&p_id_empleado="+params.id_empleado+
                        //"&p_id_concepto_convenio_contrato="+params.id_concepto_convenio_contrato+
                    "&p_id_user="+params.id_user;
                var win = window.open(URL, "_blank");

            }


            return false;
        });


        //reporte de control de cuadrillas
        /*$('#myModal').on("click", "#submit4", function(){
            //alert('Crosstab sucesos');
            //$('#txt-form').validate().resetForm(); //limpiar error input validate
            $('#txt-form').find('input').closest('.form-group').removeClass('has-error');
            $('#txt-form .tooltip').remove(); //limpiar error tooltip validate
            $('#id_empleado').attr('validar', 0);
            $('#id_concepto').attr('validar', 0);


            if ($("#txt-form").valid()){

                params={};
                params.id_contrato = $("#myModal #id_contrato").val();
                params.id_periodo = $("#myModal #id_periodo").val();
                params.id_user = "<?php //echo $_SESSION['id_user']; ?>";
                var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
                var URL="<?php //echo $GLOBALS['ini']['report_url']; ?>frameset?__format=html&__report=gseip_nov_control_cuadrillas2.rptdesign"+
                        //"&p_fecha_desde="+params.fecha_desde+
                        //"&p_fecha_hasta="+params.fecha_hasta+
                    "&p_id_contrato="+params.id_contrato+
                    "&p_id_periodo="+params.id_periodo+
                        //"&p_id_empleado="+params.id_empleado+
                        //"&p_id_concepto_convenio_contrato="+params.id_concepto_convenio_contrato+
                    "&p_id_user="+params.id_user;
                var win = window.open(URL, "_blank");

            }


            return false;
        });*/






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


                    <div class="form-group required">
                        <label for="id_periodo" class="control-label">Período de liquidación</label>
                        <select class="form-control selectpicker show-tick" id="id_periodo" name="id_periodo" title="Seleccione un período" data-live-search="true" data-size="5">
                            <!-- se completa dinamicamente desde javascript  -->
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="id_empleado" class="control-label">Empleado</label>
                        <select class="form-control selectpicker show-tick" id="id_empleado" name="id_empleado" title="Seleccione un empleado" data-live-search="true" data-size="5">
                            <!-- se completa dinamicamente desde javascript  -->
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="id_concepto" class="control-label">Concepto</label>
                        <select class="form-control selectpicker show-tick" id="id_concepto" name="id_concepto" title="Seleccione un concepto" data-live-search="true" data-size="5">
                            <!-- se completa dinamicamente desde javascript  -->
                        </select>
                    </div>


                    <!--<div class="form-group">
                        <label class="control-label" for="empleado">Fecha desde / hasta</label>
                        <div class="input-group input-daterange">
                            <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php //print $view->contrato->getFechaDesde() ?>" placeholder="DD/MM/AAAA" readonly disabled>
                            <div class="input-group-addon">a</div>
                            <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php //print $view->contrato->getFechaHasta() ?>" placeholder="DD/MM/AAAA" readonly disabled>
                        </div>
                    </div>-->

                    <br/>
                    
                    <div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-sm-10">
                                <span class="glyphicon glyphicon-tags"></span>
                                &nbsp;<strong>Detalle de concepto:</strong>
                                Muestra los partes involucrados para un período, empleado y concepto indicados.
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="submit1" name="submit1" type="submit">&nbsp;<i class="far fa-file-pdf fa-lg"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>


                    <div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-sm-10">
                                <span class="glyphicon glyphicon-tags"></span>
                                &nbsp;<strong>Conceptos del período:</strong>
                                Muestra todos los conceptos involucrados para un período y empleado indicados.
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="submit5" name="submit5" type="submit">&nbsp;<i class="far fa-file-pdf fa-lg"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>


                    <div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-sm-10">
                                <span class="glyphicon glyphicon-tags"></span>
                                &nbsp;<strong>Partes del período:</strong>
                                Muestra los partes, tipos  y  números de órden para un período indicado.
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="submit2" name="submit2" type="submit">&nbsp;<i class="far fa-file-pdf fa-lg"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>


                    <div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-sm-10">
                                <span class="glyphicon glyphicon-tags"></span>
                                &nbsp;<strong>Pendientes:</strong>
                                Muestra los empleados sin parte ni suceso para un período indicado.
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="submit3" name="submit3" type="submit">&nbsp;<i class="far fa-file-pdf fa-lg"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>


                    <!--<div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-sm-10">
                                <span class="glyphicon glyphicon-tags"></span>
                                &nbsp;<strong>Cuadrillas:</strong>
                                Muestra la conformación de cuadrillas para un período indicado.
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="submit4" name="submit4" type="submit">&nbsp;<i class="far fa-file-pdf fa-lg"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>-->


                </form>

                <div id="myElem" style="display:none">
                    <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
                </div>



            </div>

            <div class="modal-footer">
                <!--<button class="btn btn-primary" id="submit" name="submit" type="submit" title="txt">&nbsp;<i class="far fa-file-alt fa-lg"></i>&nbsp;</button>-->
                <!--<button class="btn btn-primary" id="submit1" name="submit1" type="submit" title="pdf">&nbsp;<i class="far fa-file-pdf fa-lg"></i>&nbsp;</button>-->
               <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Salir</button>
           </div>

       </div>
   </div>
</div>
</fieldset>



