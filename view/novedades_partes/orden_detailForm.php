<script type="text/javascript">


    $(document).ready(function(){

        $('#hora_inicio, #hora_fin').timepicker({
            showMeridian: false,
            //defaultTime: false
            defaultTime: '00:00 AM'
        });

        //Necesario para que funcione el plug-in bootstrap-select
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


        //cancel de formulario de parte-orden. Se movió a partesFormUpdate.php
        /*$('#orden-form #cancel').on('click', function(){
            $('#orden-form').hide();
        });*/



        //Guardar parte-orden luego de ingresar nuevo o editar
        //$('#right_side').on('click', '#submit',function(){ //ok
        $('#orden-form').on('click', '#submit',function(){ //ok
            //alert('guardar orden');

            if ($("#orden-form").valid()){

                var params={};
                params.action = 'parte-orden';
                params.operation = 'saveOrden';
                params.id_parte = $('#id_parte').val();
                params.id_parte_orden = $('#id_parte_orden').val();
                params.nro_parte_diario = $('#nro_parte_diario').val();
                params.orden_tipo = $('#orden_tipo').val();
                params.orden_nro = $('#orden_nro').val();
                params.hora_inicio = $('#hora_inicio').val();
                params.hora_fin = $('#hora_fin').val();
                params.id_area = $('#orden-form #id_area').val();
                params.servicio = $('#servicio').val();
                //params.conductor = $('input[name=conductor]:checked').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.id_area);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#orden-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#orden-form #myElem").html('Orden guardada con exito').addClass('alert alert-success').show();
                        $('#left_side .grid-ordenes').load('index.php',{action:"parte-orden", id_parte: params.id_parte, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#orden-form #myElem").hide();
                                                //$('#myModal').modal('hide');
                                                //$('#orden-form').hide();
                                                $("#orden-form #cancel").trigger("click"); //para la modal (nov2)
                                                $('.grid-ordenes').load('index.php',{action:"parte-orden", operation: "refreshGrid", id_parte: params.id_parte}); //para la modal (nov2)
                                                $('#table_empleados').load('index.php',{action:"novedades2", operation:"tableEmpleados", fecha: $('#add_fecha').val(), id_contrato: $('#id_contrato').val()}); //para la modal (nov2)
                                                $('#orden-form').hide(); //para la comun (nov)
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar la órden').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });




        $('#orden-form').validate({
            errorContainer: '#myModal #myElem',
            rules: {
                nro_parte_diario: {
                        required: true,
                        //digits: true,
                        maxlength: 15 //8
                },
                orden_tipo: {required: true},
                orden_nro: {
                    required: true,
                    digits: true,
                    maxlength: 15
                },
                servicio:{
                    maxlength: 250
                }
            },
            messages:{
                nro_parte_diario: {
                    required: "Ingrese el Nro. parte diario",
                    //digits: "Ingrese solo números",
                    maxlength: "Máximo 15 dígitos"
                },
                orden_tipo: "Seleccione el tipo de orden",
                orden_nro: {
                    required: "Ingrese el Nro. orden",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 15 dígitos"
                },
                servicio:{
                    maxlength: "Máximo 250 caracteres"
                }
            }

        });



    });

</script>



<form name ="orden-form" id="orden-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label ?></strong>
    </div>

        <!--<input type="hidden" name="id_parte" id="id_parte" value="<?php //print $view->orden->getIdParte() ?>">-->
        <input type="hidden" name="id_parte_orden" id="id_parte_orden" value="<?php print $view->orden->getIdParteOrden() ?>">

        <div class="form-group required">
            <label class="control-label" for="codigo">Nro. parte diario</label>
            <input class="form-control" type="text" name="nro_parte_diario" id="nro_parte_diario" value = "<?php print $view->orden->getNroParteDiario() ?>" placeholder="Nro. parte diario">
        </div>

        <div class="form-group required">
            <label for="orden_tipo" class="control-label">Tipo orden</label>
            <select class="form-control selectpicker show-tick" id="orden_tipo" name="orden_tipo">
                <?php foreach ($view->orden_tipos['enum'] as $nac){
                    ?>
                    <option value="<?php echo $nac; ?>"
                        <?php echo ($nac == $view->orden->getOrdenTipo() OR ($nac == $view->orden_tipos['default'] AND !$view->orden->getIdParteOrden()) )? 'selected' :'' ?>
                        >
                        <?php echo $nac; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>

        <div class="form-group required">
            <label class="control-label" for="orden_nro">Nro. orden</label>
            <input class="form-control" type="text" name="orden_nro" id="orden_nro" value = "<?php print $view->orden->getOrdenNro() ?>" placeholder="Nro. orden">
        </div>


        <div class="row">

            <div class="col-md-6">
                <!--<div class="form-group">
                    <label class="control-label" for="hs_normal">Hora inicio</label>
                    <input class="form-control hs-group" type="time" name="hora_inicio" id="hora_inicio" value = "<?php //print $view->orden->getHoraInicio() ?>" placeholder="hh:mm">
                </div>-->
                <div class="form-group">
                    <label class="control-label" for="hs_normal">Hora inicio</label>
                    <div class="input-group bootstrap-timepicker timepicker">
                        <input type="text" class="form-control input-small" name="hora_inicio" id="hora_inicio" value = "<?php print $view->orden->getHoraInicio() ?>" >
                        <span class="input-group-addon"><i class="fad fa-clock"></i></span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--<div class="form-group">
                    <label class="control-label" for="hs_50">Hora fin</label>
                    <input class="form-control hs-group" type="time" name="hora_fin" id="hora_fin" value = "<?php //print $view->orden->getHoraFin() ?>" placeholder="hh:mm">
                </div>-->
                <div class="form-group">
                    <label class="control-label" for="hs_50">Hora fin</label>
                    <div class="input-group bootstrap-timepicker timepicker">
                        <input type="text" class="form-control input-small" name="hora_fin" id="hora_fin" value = "<?php print $view->orden->getHoraFin() ?>" >
                        <span class="input-group-addon"><i class="fad fa-clock"></i></span>
                    </div>
                </div>
            </div>


        </div>


        <div class="form-group">
            <label for="id_area" class="control-label" title="Seleccionar sólo si difiere del área principal">Área secundaria</label>
            <select class="form-control selectpicker show-tick" id="id_area" name="id_area" data-live-search="true" data-size="5">
                <option value="">Seleccione un área secundaria</option>
                <?php foreach ($view->areas as $ar){
                    ?>
                    <option value="<?php echo $ar['id_area']; ?>"
                        <?php echo ($ar['id_area'] == $view->orden->getIdArea())? 'selected' :'' ?>
                        >
                        <?php echo $ar['codigo']." ".$ar['nombre'];?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group">
            <label class="control-label" for="servicio">Servicio</label>
            <textarea class="form-control" name="servicio" id="servicio" placeholder="Descripción del servicio" rows="4"><?php print $view->orden->getServicio(); ?></textarea>
        </div>





    <div id="myElem" class="msg" style="display:none">
        <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
    </div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary" id="submit" name="submit" type="submit" <?php echo ($view->target!='view' )? '' : 'disabled' ?> >Guardar</button>
        <!--<button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













