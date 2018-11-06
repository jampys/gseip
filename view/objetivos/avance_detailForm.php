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
                <input class="form-control" type="text" name="fecha" id="fecha" value = "<?php print $view->avance->getFecha() ?>" placeholder="DD/MM/AAAA" readonly>
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>


        <div class="form-group">
            <label for="id_tarea" class="control-label">Actividad</label>
            <select class="form-control selectpicker show-tick" id="id_tarea" name="id_tarea" data-live-search="true" data-size="5">
                <option value="">Seleccione una actividad</option>
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
        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="button">Guardar</button>
        <!--<button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>












