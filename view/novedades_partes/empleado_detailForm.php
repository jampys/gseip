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


        //cancel de formulario de etapa
        $('#etapa-form #cancel').on('click', function(){
            $('#etapa-form').hide();
        });


        $('#etapa-form').validate({
            rules: {
                /*codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },*/
                fecha_etapa: {required: true},
                etapa: {required: true},
                aplica: {required: true},
                motivo: {required: true},
                modo_contacto: {required: true}
            },
            messages:{
                /*codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                }, */
                fecha_etapa: "Seleccione una fecha para la etapa",
                etapa: "Seleccione una etapa",
                aplica: "Seleccione una opción",
                motivo: "Seleccione el motivo",
                modo_contacto: "Seleccione el modo de contacto"
            }

        });



    });

</script>



<form name ="etapa-form" id="etapa-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label ?></strong>
    </div>

    <input type="hidden" name="id_parte" id="id_parte" value="<?php print $view->empleado->getIdParte() ?>">

        <!--<input type="hidden" name="id_postulacion" id="id_postulacion" value="<?php //print $view->etapa->getIdPostulacion() ?>">-->


        <div class="form-group required">
            <label for="id_empleado" class="control-label">Empleado</label>
            <select class="form-control selectpicker show-tick" id="id_empleado" name="id_empleado" data-live-search="true" data-size="5">
                <option value="">Seleccione un empleado</option>
                <?php foreach ($view->empleados as $em){
                    ?>
                    <option value="<?php echo $em['id_empleado']; ?>"
                        <?php echo ($em['id_empleado'] == $view->empleado->getIdEmpleado())? 'selected' :'' ?>
                        >
                        <?php echo $em['apellido'].' '.$em['nombre'];?>
                    </option>
                <?php  } ?>
            </select>
        </div>



    <div class="form-group required">
        <label for="etapa" class="control-label">Etapa</label>
        <select class="form-control selectpicker show-tick" id="etapa" name="etapa" title="Seleccione la etapa"  data-live-search="true" data-size="5">
            <?php foreach ($view->etapas['enum'] as $et){
                ?>
                <option value="<?php echo $et; ?>"
                    <?php echo ($et == $view->etapa->getEtapa() OR ($et == $view->etapas['default'] AND !$view->etapa->getIdEtapa()) )? 'selected' :'' ?>
                    >
                    <?php echo $et; ?>
                </option>
            <?php  } ?>
        </select>
    </div>


    <div class="form-group required">
        <label for="aplica" class="control-label">Aplica</label>

        <div class="input-group">

            <?php foreach($view->aplica_opts['enum'] as $val){ ?>
                <label class="radio-inline">
                    <input type="radio" name="aplica" value="<?php echo $val ?>"
                        <?php echo ($val == $view->etapa->getAplica() OR ($val == $view->aplica_opts['default'] AND !$view->etapa->getIdEtapa()))? 'checked' :'' ?>
                        ><?php echo ($val==1)? 'Si':'No' ?>
                </label>
            <?php } ?>

        </div>
    </div>



    <div class="form-group required">
        <label for="motivo" class="control-label">Motivo</label>
        <select class="form-control selectpicker show-tick" id="motivo" name="motivo" title="Seleccione el motivo"  data-live-search="true" data-size="5">
            <?php foreach ($view->motivos['enum'] as $mo){
                ?>
                <option value="<?php echo $mo; ?>"
                    <?php echo ($mo == $view->etapa->getMotivo() OR ($mo == $view->motivos['default'] AND !$view->etapa->getIdEtapa()) )? 'selected' :'' ?>
                    >
                    <?php echo $mo; ?>
                </option>
            <?php  } ?>
        </select>
    </div>

    <div class="form-group required">
        <label for="modo_contacto" class="control-label">Modo contacto</label>
        <select class="form-control selectpicker show-tick" id="modo_contacto" name="modo_contacto" title="Seleccione el modo de contacto"  data-live-search="true" data-size="5">
            <?php foreach ($view->modos_contacto['enum'] as $mc){
                ?>
                <option value="<?php echo $mc; ?>"
                    <?php echo ($mc == $view->etapa->getModoContacto() OR ($mc == $view->modos_contacto['default'] AND !$view->etapa->getIdEtapa()) )? 'selected' :'' ?>
                    >
                    <?php echo $mc; ?>
                </option>
            <?php  } ?>
        </select>
    </div>



    <div id="myElem" class="msg" style="display:none"></div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
        <!--<button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













