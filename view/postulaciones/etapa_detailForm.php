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


        $('#puesto').validate({ //ok
            rules: {
                codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },
                nombre: {required: true},
                id_area: {required: true},
                id_nivel_competencia: {required: true}
            },
            messages:{
                codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                },
                nombre: "Ingrese el nombre",
                id_area: "Seleccione un área",
                id_nivel_competencia: "Seleccione un nivel de competencia"
            }

        });



    });

</script>



<form name ="etapa-form" id="etapa-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label ?></strong>
    </div>

    <input type="hidden" name="id_etapa" id="id_etapa" value="<?php print $view->etapa->getIdEtapa() ?>">
    <input type="hidden" name="id_postulacion" id="id_postulacion" value="<?php print $view->etapa->getIdPostulacion() ?>">

    <div class="form-group required">
        <label class="control-label" for="fecha_etapa">Fecha etapa</label>
        <div class="input-group date">
            <input class="form-control" type="text" name="fecha_etapa" id="fecha_etapa" value = "<?php print $view->etapa->getFechaEtapa() ?>" placeholder="DD/MM/AAAA">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
            </div>
        </div>
    </div>

    <div class="form-group required">
        <label for="etapa" class="control-label">Etapa</label>
        <select class="form-control selectpicker show-tick" id="etapa" name="etapa" title="Seleccione la etapa">
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
        <select class="form-control selectpicker show-tick" id="motivo" name="motivo" title="Seleccione el motivo">
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
        <select class="form-control selectpicker show-tick" id="modo_contacto" name="modo_contacto" title="Seleccione el modo de contacto">
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

    <div class="form-group">
        <label class="control-label" for="comentarios">Comentarios</label>
        <textarea class="form-control" name="comentarios" id="comentarios" placeholder="Comentarios" rows="2"><?php print $view->etapa->getComentarios(); ?></textarea>
    </div>


    <div id="myElem" class="msg" style="display:none"></div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
        <!--<button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













