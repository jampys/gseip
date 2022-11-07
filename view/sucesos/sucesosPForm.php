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



        function getData(url, params){
            var jqxhr = $.ajax({
                url:"index.php",
                type:"post",
                data: params,
                dataType:"json"//xml,html,script,json
            });
            return jqxhr ;
        }


        //Select dependiente: al seleccionar contrato carga periodos vigentes
        // solo se usa cuando es un insert
        $('#suceso-form').on('change', '#id_empleado', function(e){
            //alert('seleccionó un contrato');
            //throw new Error();
            params={};
            params.action = "sucesosP";
            params.operation = "getContratos";
            //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
            params.id_suceso = $('#myModal #id_suceso').val();
            params.id_empleado = $('#myModal #id_empleado').val();
            params.activos = 1;

            getData('index.php', params)
                .then(function(data){ //completo select de contratos

                    $('#myModal #id_contrato').empty();
                    $("#myModal #id_contrato").html('<option value="">Seleccione un contrato</option>');

                    if(Object.keys(data).length > 0){

                        $.each(data, function(indice, val){
                            var label = data[indice]["nro_contrato"]+' '+data[indice]["contrato"];
                            $("#myModal #id_contrato").append('<option value="'+data[indice]["id_contrato"]+'"'
                            +'>'+label+'</option>');
                        });

                        $('#myModal #id_contrato').selectpicker('refresh');
                    }

                    params.action = "sucesos";
                    params.operation = "getPeriodosVacaciones";
                    return getData('index.php', params);


                }).then(function(data){ //completo select de periodos de vacaciones


                    $('#periodo').empty();
                    if(Object.keys(data).length > 0){
                        $.each(data, function(indice, val){
                            var label = data[indice]["periodo"];
                            let subtext = data[indice]["cantidad"]+' días. Disp. '+data[indice]["pendientes"]+' días';
                            let disabled = (indice > 0)? 'disabled' : '';
                            $("#periodo").append('<option value="'+data[indice]["periodo"]+'" dias_disp="'+data[indice]["pendientes"]+'" data-subtext="'+subtext+'" '+disabled+'>'+label+'</option>');

                        });
                        $('#periodo').selectpicker('refresh');
                    }

                }).catch(function(data, textStatus, errorThrown){

                    alert(data.responseText);
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
                params.periodo = $('#myModal #periodo').val();
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
                                                $('#example').DataTable().ajax.reload(); //$("#search").trigger("click");
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
                id_contrato: {required: true},
                programado: {required: true},
                fecha: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
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
                periodo: {
                    required: {
                        depends: function(element) {
                            return $("#id_evento").val() == 21;
                        }
                    }
                }

            },
            messages:{
                id_empleado: "Seleccione un empleado",
                id_evento: "Seleccione un suceso",
                id_contrato: "Seleccione un contrato",
                programado: "Seleccione un período programado",
                fecha: {
                    required: "Seleccione la fecha de fin",
                    remote: "Ya existe un suceso para el empleado y evento en la fecha seleccionada"
                },
                periodo: {
                    required: "Seleccione el año para las vacaciones"
                }
            }

        });



        jQuery.validator.addMethod(
            "max",
            function (value, element, params) {
                let dias_sel = $('#myModal #dias').val();
                let dias_disp = params;
                dias_sel = dias_sel || 0; //si el campo es NaN (not a number) lo convierte en 0.
                dias_disp = dias_disp || 0; //si el campo es NaN (not a number) lo convierte en 0.


                if ($('#myModal #id_evento').val() != 21) return true; //si no es un evento de vacaciones
                else if ($('#myModal #id_suceso').val()) return true; //si es una edicion
                else if(dias_disp >= dias_sel ) return true;
                else return false;
            },
            jQuery.validator.format("El período de vacaciones seleccionado no tiene suficientes días.")
        );

        $("#dias1").rules('add', {max: function(){ return parseInt($('#myModal #periodo option:selected').attr('dias_disp'));} });





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

                    <div class="alert alert-info fade in">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <i class="fas fa-tags"></i>&nbsp;  Permite crear sucesos programados a futuro (dentro de los próximos 12 meses).
                        La imputación se producirá automaticamente al momento de generarse el período de liquidación.
                    </div>

                    <input type="hidden" name="id_suceso" id="id_suceso" value="<?php print $view->suceso->getIdSuceso() ?>">

                    <div class="row">
                        <div class="form-group col-md-9 required">
                            <label for="id_empleado" class="control-label">Empleado</label>
                            <select class="form-control selectpicker show-tick" id="id_empleado" name="id_empleado" title="Seleccione un empleado" data-live-search="true" data-size="5">
                                <?php foreach ($view->empleados as $em){
                                    ?>
                                    <option value="<?php echo $em['id_empleado']; ?>"
                                        <?php echo ($view->suceso->getIdEmpleado() == $em['id_empleado'])? 'selected' : ''; ?>
                                        >
                                        <?php echo $em['legajo'].' '.$em['apellido'].' '.$em['nombre']; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-md-9 required">
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

                        <div class="form-group col-md-3">
                            <label class="control-label" for="periodo" title="Requerido solo para Licencia por vacaciones">Período <i class="fa fa-info-circle dp_light_gray"></i></label>
                            <select class="form-control selectpicker show-tick" id="periodo" name="periodo" data-live-search="true" data-size="5" title="Período">
                                <!-- se completa dinamicamente desde javascript cuando es un insert  -->
                                <?php foreach ($view->años as $per){
                                    ?>
                                    <option value="<?php echo $per; ?>"
                                        <?php echo ($per == $view->suceso->getPeriodo())? 'selected' :'' ?>
                                        >
                                        <?php echo $per; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-md-9 required">
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
                    </div>


                    <div class="row">
                        <div class="form-group col-md-9 required">
                            <label class="control-label" for="">Fechas desde / hasta</label>
                            <div class="inner-addon right-addon">
                                <input class="form-control" type="text" name="fecha" id="fecha" value = "<?php echo ($view->suceso->getFechaDesde() && $view->suceso->getFechaHasta())? $view->suceso->getFechaDesde()." - ".$view->suceso->getFechaHasta() : "";  ?>" placeholder="DD/MM/AAAA - DD/MM/AAAA" readonly>
                                <i class="fad fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="dias" class="control-label">Total días</label>
                            <input type="text" class="form-control cdias" name="dias" id="dias" value = "<?php print $view->suceso->getCantidad1(); ?>" placeholder="" disabled >
                            <input type="hidden" name="dias1" id="dias1">
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-md-9 required">
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
                    </div>


                    <div class="row">
                        <div class="form-group col-md-9">
                            <label class="control-label" for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" placeholder="Observaciones" rows="4"><?php print $view->suceso->getObservaciones(); ?></textarea>
                        </div>
                    </div>

                </form>



                <div id="myElem" class="msg" style="display:none">
                    <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
                </div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="submit" name="submit" type="submit" <?php echo ( PrivilegedUser::dhasPrivilege('SUC_ABM', array(1)) && $view->target!='view')? '' : 'disabled' ?> >Guardar</button>
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



