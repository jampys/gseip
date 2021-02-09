<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({ //ok
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });



        moment.locale('es');
        $('#fecha, #f1, #f2').daterangepicker({
            parentEl: '#myModal',
            //showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            linkedCalendars: false,
            "locale": {
                "format": "DD/MM/YYYY"
            }
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format) + ' - ' + picker.endDate.format(picker.locale.format));
            picker.element.valid();
            $(this).closest('.row').find('.cdias').val(picker.endDate.diff(picker.startDate, 'days')+1);
        });

        var drp = $('#fecha').data('daterangepicker');
        //var drp1 = $('#f1').data('daterangepicker');
        //var drp2 = $('#f2').data('daterangepicker');

        //solo ocurre al cambiar el valor de fecha. Restringe el rango de fechas de f1 y f2
        /*$('#fecha').on("apply.daterangepicker", function (e, picker) {
            drp1.minDate = picker.startDate;
            drp1.maxDate = picker.endDate;
            drp2.minDate = picker.startDate;
            drp2.maxDate = picker.endDate;
        });*/

        //Sirve para restringir f1 y f2 al rango de fechas de fecha.
        //drp1.minDate = drp.startDate;
        //drp1.maxDate = drp.endDate;
        //drp2.minDate = drp.startDate;
        //drp2.maxDate = drp.endDate;

        //Al hacer check o uncheck en checkbox
        /*$("#chk_imputar").change(function() {
            var ischecked= $(this).is(':checked');
            if(ischecked) {
                drp1.setStartDate(drp.startDate);
                drp1.setEndDate(drp.endDate);
                drp1.element.val(drp1.startDate.format(drp1.locale.format) + ' - ' + drp1.endDate.format(drp1.locale.format));
                $('#cantidad1').val($('#dias').val());
                $('#id_periodo2').val("").selectpicker('refresh');
                drp2.setStartDate(new Date); //limpia starDate
                drp2.setEndDate(new Date); //limpia endDate
                $('#f2').val(""); //limpia el input
                $('#cantidad2').val(0);
            }else{
                drp1.setStartDate(new Date);
                drp1.setEndDate(new Date);
                $('#f1').val("");
                $('#cantidad1').val(0);
            }

        });*/



        //Select dependiente: al seleccionar contrato carga periodos vigentes
        // solo se usa cuando es un insert
        $('#suceso-form').on('change', '#id_empleado', function(e){
            //alert('seleccionó un contrato');
            //throw new Error();
            params={};
            params.action = "sucesosP";
            params.operation = "getContratos";
            //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
            params.id_empleado = $('#id_empleado').val();
            params.activos = 1;

            $('#id_contrato').empty();


            $.ajax({
                url:"index.php",
                type:"post",
                //data:{"action": "parte-empleado-concepto", "operation": "getConceptos", "id_objetivo": <?php //print $view->objetivo->getIdObjetivo() ?>},
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    $("#id_contrato").html('<option value="">Seleccione un contrato</option>');

                    if(Object.keys(data).length > 0){

                        $.each(data, function(indice, val){
                            var label = data[indice]["nro_contrato"]+' '+data[indice]["contrato"];
                            $("#id_contrato").append('<option value="'+data[indice]["id_contrato"]+'"'
                            +'>'+label+'</option>');
                        });
                    }
                    $('#id_contrato').selectpicker('refresh');

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });


        });




        $('#myModal').on('click', '#submit',function(){ //ok


            if ($("#suceso-form").valid()){

                var params={};
                params.action = 'sucesosP';
                params.operation = 'saveSuceso';
                params.id_suceso = $('#myModal #id_suceso').val();
                params.id_empleado = $('#myModal #id_empleado').val();
                params.id_evento = $('#myModal #id_evento').val();
                params.fecha_desde = drp.startDate.format('DD/MM/YYYY');
                params.fecha_hasta = drp.endDate.format('DD/MM/YYYY');
                params.dias = $('#myModal #dias').val();
                params.observaciones = $('#myModal #observaciones').val();
                params.id_contrato = $('#myModal #id_contrato').val();
                params.programado = $('#myModal #programado').val();
                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);
                    if(data >=0){
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myModal #myElem").html('Suceso guardado con exito').addClass('alert alert-success').show();
                        setTimeout(function() { $("#myElem").hide();
                                                $("#suceso-form #cancel").trigger("click"); //para la modal (nov2)
                                                $('.grid-sucesos').load('index.php',{action:"novedades2", operation: "sucesosRefreshGrid", id_empleado: params.id_empleado, id_contrato: $('#id_contrato').val(), id_periodo: $('#id_periodo').val()}); //para la modal (nov2)
                                                $('#myModal').modal('hide');
                                                $("#search").trigger("click");
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar el suceso').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });



        $('#myModal #cancel').on('click', function(){
           //alert('cancelar');
            //uploadObj.stopUpload();
        });


        $('#myModal').modal({ //ok
            backdrop: 'static',
            keyboard: false
        });


        $('#suceso-form').validate({ //ok
            errorContainer: '#myModal #myElem',
            ignore: "", //para dias1 hidden
            rules: {
                id_empleado: {required: true},
                id_evento: {required: true},
                fecha: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        //async: false,
                        data: {
                            action: "sucesos",
                            operation: "checkRango",
                            fecha_desde: function(){ return drp.startDate.format('DD/MM/YYYY');},
                            fecha_hasta: function(){ return drp.endDate.format('DD/MM/YYYY');},
                            id_empleado: function(){ return $('#id_empleado').val();},
                            id_evento: function(){ return $('#id_evento').val();},
                            id_suceso: function(){ return $('#id_suceso').val();}
                        }
                    }
                },
                id_periodo1: {required: true},
                id_periodo2: { required: false,
                               notEqual: ["#id_periodo1", "Seleccione un período de liquidación diferente al primero"]
                },
                f1: {required: true}

            },
            messages:{
                id_empleado: "Seleccione un empleado",
                id_evento: "Seleccione un suceso",
                fecha: {
                    required: "Seleccione la fecha de fin",
                    remote: "Ya existe un suceso para el empleado y evento en la fecha seleccionada"
                },
                id_periodo1: "Seleccione un período para el evento",
                f1: "Seleccione un rango de fechas para el primer período"
            }

        });





        $("#myModal #id_empleado").on('changed.bs.select', function (e) {
            //Al seleccionar un grupo, completa automaticamente el campo vencimiento y lo deshabilita.
            if ($('#id_empleado option:selected').attr('id_grupo') !='') {
                $('#id_vencimiento').selectpicker('val', $('#id_empleado option:selected').attr('id_vencimiento')).prop('disabled', true).selectpicker('refresh');
            }
            else{
                $('#id_vencimiento').selectpicker('val', '').prop('disabled', false).selectpicker('refresh');
            }

        });




    });

</script>



<!-- Modal -->
<fieldset  <?php //echo ($view->renovacion->getIdRnvRenovacion() || !PrivilegedUser::dhasAction('RPE_UPDATE', array(1))   )? 'disabled' : '';  ?>  >
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="suceso-form" id="suceso-form" method="POST" action="index.php">
                    <input type="hidden" name="id_suceso" id="id_suceso" value="<?php print $view->suceso->getIdSuceso() ?>">

                    <div class="form-group required">
                        <label for="id_empleado" class="control-label">Empleado</label>
                        <select class="form-control selectpicker show-tick" id="id_empleado" name="id_empleado" title="Seleccione un empleado" data-live-search="true" data-size="5">
                            <?php foreach ($view->empleados as $em){
                                ?>
                                <option value="<?php echo $em['id_empleado']; ?>"
                                    <?php echo ($view->suceso->getIdEmpleado() == $em['id_empleado'])? 'selected' : ''; ?>
                                    data-icon="fas fa-user fa-sm fa-fw"
                                    >
                                    <?php echo $em['apellido'].' '.$em['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group required">
                        <label for="id_evento" class="control-label">Suceso</label>
                            <select class="form-control selectpicker show-tick" id="id_evento" name="id_evento" title="Seleccione un suceso" data-live-search="true" data-size="5" data-show-subtext="true">
                                <?php foreach ($view->eventos as $ev){ ?>
                                    <option value="<?php echo $ev['id_evento']; ?>" data-subtext="<?php echo $ev['tipo_liquidacion'] ;?>"
                                        <?php echo ($ev['id_evento'] == $view->suceso->getIdEvento())? 'selected' :'' ?>
                                        >
                                        <?php echo $ev['nombre'];?>
                                    </option>
                                <?php  } ?>
                            </select>
                    </div>


                    <div class="form-group required">
                        <label for="id_contrato" class="control-label">Contrato</label>
                        <select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" data-live-search="true" data-size="5">
                            <!-- se completa dinamicamente desde javascript cuando es un insert  -->
                            <!--<option value="">Seleccione un contrato</option>-->
                            <?php foreach ($view->contratos as $pe){
                                ?>
                                <option value="<?php echo $pe['id_contrato']; ?>"
                                    <?php echo ($view->suceso->getIdContrato() == $pe['id_contrato'])? 'selected' : ''; ?>
                                    >
                                    <?php echo $pe['nro_contrato'].' '.$pe['contrato']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="row">
                        <div class="form-group col-md-9 required">
                            <label class="control-label" for="">Fechas desde / hasta</label>
                            <div class="inner-addon right-addon">
                                <input class="form-control" type="text" name="fecha" id="fecha" value = "<?php echo ($view->suceso->getFechaDesde() && $view->suceso->getFechaHasta())? $view->suceso->getFechaDesde()." - ".$view->suceso->getFechaHasta() : "";  ?>" placeholder="DD/MM/AAAA - DD/MM/AAAA" readonly>
                                <i class="glyphicon glyphicon-calendar"></i>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="dias" class="control-label">Total días</label>
                            <input type="text" class="form-control cdias" name="dias" id="dias" value = "<?php print $view->suceso->getCantidad1(); ?>" placeholder="" disabled >
                            <input type="hidden" name="dias1" id="dias1">
                        </div>
                    </div>


                    <div class="form-group required">
                        <label for="programado" class="control-label">Período programado</label>
                        <select class="form-control selectpicker show-tick" id="programado" name="programado" title="Seleccione un período" data-live-search="true" data-size="5" data-show-subtext="true">
                            <?php foreach ($view->periodos as $ev){ ?>
                                <option value="<?php echo $ev['per']; ?>"
                                    <?php echo ($ev['per'] == $view->suceso->getProgramado())? 'selected' :'' ?>
                                    >
                                    <?php echo $ev['periodo'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="observaciones">Observaciones</label>
                        <textarea class="form-control" name="observaciones" id="observaciones" placeholder="Observaciones" rows="2"><?php print $view->suceso->getObservaciones(); ?></textarea>
                    </div>

                </form>



                <div id="myElem" class="msg" style="display:none">
                    <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
                </div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



