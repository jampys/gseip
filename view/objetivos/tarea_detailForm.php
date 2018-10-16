<script type="text/javascript">


    $(document).ready(function(){

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


        $('.input-daterange').datepicker({ //ok
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });


        //cancel de formulario de parte-orden
        $('#orden-form #cancel').on('click', function(){
            //alert('cancelar form parte-orden');
            $('#orden-form').hide();
        });



        //Guardar parte-orden luego de ingresar nuevo o editar
        $('#right_side').on('click', '#submit',function(){ //ok
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
                params.duracion = $('#duracion').val();
                params.servicio = $('#servicio').val();
                //params.conductor = $('input[name=conductor]:checked').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#orden-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Orden guardada con exito').addClass('alert alert-success').show();
                        $('#left_side .grid-ordenes').load('index.php',{action:"parte-orden", id_parte: params.id_parte, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                            //$('#myModal').modal('hide');
                            $('#orden-form').hide();
                        }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar la órden').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });




        $('#orden-form').validate({
            rules: {
                /*codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },*/
                id_empleado: {required: true}
            },
            messages:{
                /*codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                }, */
                id_empleado: "Seleccione un empleado"
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
        <input type="hidden" name="id_tarea" id="id_tarea" value="<?php print $view->tarea->getIdTarea() ?>">

        <div class="form-group required">
            <label class="control-label" for="nombre">Nombre</label>
            <input class="form-control" type="text" name="nombre" id="nombre" value = "<?php print $view->tarea->getNombre() ?>" placeholder="Nombre">
        </div>


        <div class="form-group required">
            <label class="control-label" for="empleado">Fecha inicio / fin</label>
            <div class="input-group input-daterange">
                <input class="form-control" type="text" name="fecha_inicio" id="fecha_fin" value = "<?php print $view->tarea->getFechaInicio() ?>" placeholder="DD/MM/AAAA">
                <div class="input-group-addon">a</div>
                <input class="form-control" type="text" name="fecha_fin" id="fecha_fin" value = "<?php print $view->tarea->getFechaFin() ?>" placeholder="DD/MM/AAAA">
            </div>
        </div>


    <div id="myElem" class="msg" style="display:none"></div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
        <!--<button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













