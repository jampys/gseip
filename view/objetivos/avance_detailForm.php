<script type="text/javascript">


    $(document).ready(function(){

        //Necesario para que funcione el plug-in bootstrap-select
        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        moment.locale('es');
        $('#fecha').daterangepicker({
            singleDatePicker: true,
            //showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            "locale": {
                "format": "DD/MM/YYYY"
            }
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format));
            picker.element.valid();
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
                 },
                cantidad_plan: {
                    required: true,
                    digits: true,
                    maxlength: 3
                },
                periodo: {required: true}

            },
            messages:{
                fecha: "Seleccione una fecha",
                indicador: "Seleccione un indicador",
                cantidad: {
                    required: "Ingrese la cantidad real",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 3 dígitos"
                 },
                cantidad_plan: {
                    required: "Ingrese la cantidad planificada",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 3 dígitos"
                },
                periodo: "Seleccione un período"

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
            <div class="inner-addon right-addon">
                <input class="form-control" type="text" name="fecha" id="fecha" value = "<?php print $view->avance->getFecha() ?>" placeholder="DD/MM/AAAA" readonly>
                <i class="glyphicon glyphicon-calendar"></i>
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
            <label for="periodo" class="control-label">Período</label>
            <select class="form-control selectpicker show-tick" id="periodo" name="periodo" title="Seleccione el período"  data-live-search="true" data-size="5">
                <?php foreach ($view->periodos['enum'] as $pe){
                    ?>
                    <option value="<?php echo $pe; ?>"
                        <?php echo ($pe == $view->avance->getPeriodo() OR ($pe == $view->periodos['default'] AND !$view->avance->getIdAvance()) )? 'selected' :'' ?>
                        >
                        <?php echo $pe; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <!--<div class="form-group required">
            <label class="control-label" for="cantidad">Cantidad</label>
            <input class="form-control" type="text" name="cantidad" id="cantidad" value = "<?php print $view->avance->getCantidad() ?>" placeholder="Cantidad">
        </div>-->


        <div class="row">
            <div class="form-group col-md-6 required">
                <label for="cantidad_plan" class="control-label">Cant. planificada</label>
                <input type="text" class="form-control" name="cantidad_plan" id="cantidad_plan" value = "<?php echo ($view->avance->getIdAvance())? $view->avance->getCantidadPlan() : $view->objetivo->getMetaValor(); ?>" placeholder="Cantidad real">
            </div>
            <div class="form-group col-md-6 required">
                <label for="cantidad" class="control-label">Cant. real</label>
                <input type="text" class="form-control" name="cantidad" id="cantidad" value = "<?php print $view->avance->getCantidad() ?>" placeholder="Cantidad real">
            </div>
        </div>


        <div class="form-group">
            <label class="control-label" for="comentarios">Comentarios</label>
            <textarea class="form-control" name="comentarios" id="comentarios" placeholder="Comentarios" rows="2"><?php print $view->avance->getComentarios(); ?></textarea>
        </div>


        <div id="myElem" class="msg" style="display:none">
            <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
        </div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary" id="submit" name="submit" type="button" <?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) && $view->target!='view')? '' : 'disabled' ?> >Guardar</button>
        <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













