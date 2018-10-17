<script type="text/javascript">


    $(document).ready(function(){

        //Necesario para que funcione el plug-in bootstrap-select
        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        $('.input-group.date').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        //cancel de formulario de parte-empleado
        $('#avance-form #cancel').on('click', function(){ //ok
            //alert('cancelar form parte-empleado');
            $('#avance-form').hide();
        });



        //Guardar parte-empleado luego de ingresar nuevo o editar
        $('#avance-form').on('click', '#submit',function(){ //ok
            //alert('guardar empleado');

            if ($("#avance-form").valid()){

                var params={};
                params.action = 'obj_avances';
                params.operation = 'saveAvance';
                params.id_avance = $('#id_avance').val();
                params.id_objetivo = $('#id_objetivo').val();
                params.tarea = $('#tarea').val();
                params.indicador = $('#indicador').val();
                params.cantidad = $('#cantidad').val();
                params.comentarios = $('#comentarios').val();
                //params.conductor = $('input[name=conductor]:checked').val();
                //params.conductor = $('#conductor').prop('checked')? 1:0;
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#avance-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Avance guardado con exito').addClass('alert alert-success').show();
                        $('#left_side .grid-avances').load('index.php',{action:"obj_avances", id_objetivo: params.id_objetivo, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                //$('#myModal').modal('hide');
                                                $('#avance-form').hide();
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar el avance').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });




        $('#avance-form').validate({ //ok
            rules: {
                fecha: {required: true},
                indicador: {required: true},
                cantidad: {
                 required: true,
                 digits: true,
                 maxlength: 3
                 }

            },
            messages:{
                fecha: "Seleccione una fecha",
                indicador: "Seleccione un indicador",
                cantidad: {
                 required: "Ingrese la cantidad",
                 digits: "Ingrese solo números",
                 maxlength: "Máximo 3 dígitos"
                 }

            }

        });



    });

</script>



<form name ="avance-form" id="avance-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label ?></strong>
    </div>

        <input type="hidden" name="id_avance" id="id_avance" value="<?php print $view->avance->getIdAvance() ?>">


        <div class="form-group required">
            <label class="control-label" for="fecha_etapa">Fecha</label>
            <div class="input-group date">
                <input class="form-control" type="text" name="fecha" id="fecha" value = "<?php print $view->avance->getFecha() ?>" placeholder="DD/MM/AAAA">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>


        <div class="form-group">
            <label for="id_tarea" class="control-label">Tarea</label>
            <select class="form-control selectpicker show-tick" id="id_tarea" name="id_tarea" title="Seleccione una tarea" data-live-search="true" data-size="5">
                <!--<option value="">Seleccione una tarea</option>-->
                <?php foreach ($view->tareas as $ta){
                    ?>
                    <option value="<?php echo $ta['id_tarea']; ?>"
                        <?php echo ($ta['id_tarea'] == $view->avance->getIdTarea())? 'selected' :'' ?>
                        >
                        <?php echo $ta['nombre'] ;?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group required">
            <label for="indicador" class="control-label">Indicador</label>
            <select class="form-control selectpicker show-tick" id="indicador" name="indicador" title="Seleccione el indicador"  data-live-search="true" data-size="5">
                <?php foreach ($view->indicadores['enum'] as $mo){
                    ?>
                    <option value="<?php echo $mo; ?>"
                        <?php echo ($mo == $view->avance->getIndicador() OR ($mo == $view->indicadores['default'] AND !$view->avance->getIdAvance()) )? 'selected' :'' ?>
                        >
                        <?php echo $mo; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group required">
            <label class="control-label" for="cantidad">Cantidad</label>
            <input class="form-control" type="text" name="cantidad" id="cantidad" value = "<?php print $view->avance->getCantidad() ?>" placeholder="Cantidad">
        </div>


        <div class="form-group">
            <label class="control-label" for="comentarios">Comentarios</label>
            <textarea class="form-control" name="comentarios" id="comentarios" placeholder="Comentarios" rows="2"><?php print $view->avance->getComentarios(); ?></textarea>
        </div>


        <div id="myElem" class="msg" style="display:none"></div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
        <!--<button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













