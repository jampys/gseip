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
        $('#fecha_verificacion').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            parentEl: '#myModal',
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
                verificacion_eficacia: {
                    required: true,
                    maxlength: 400
                },
                fecha_verificacion: {
                    required: true,
                    validDate: true
                },
                id_responsable_ejecucion: {required: true}
            },
            messages:{
                /*codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                }, */
                verificacion_eficacia: {
                    required: "Ingrese la verificación de la eficacia",
                    maxlength: "Máximo 400 caracteres"
                },
                etapa: "Seleccione una etapa",
                fecha_verificacion: {
                    required: "Seleccione la fecha de implementación",
                    validDate: "Ingrese un formato de fecha válido DD/MM/AAAA"
                },
                id_responsable_ejecucion: "Seleccione el responsable de ejecución"
            }

        });



    });

</script>



<form name ="etapa-form" id="etapa-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label ?></strong>
    </div>

    <input type="hidden" name="id_verificacion" id="id_verificacion" value="<?php print $view->verificacion->getIdVerificacion() ?>">
    <input type="hidden" name="id_no_conformidad" id="id_no_conformidad" value="<?php print $view->verificacion->getIdNoConformidad() ?>">

        <div class="form-group required">
            <label class="control-label" for="verificacion_eficacia">Verificación eficacia</label>
            <textarea class="form-control" name="verificacion_eficacia" id="verificacion_eficacia" placeholder="Verificación de eficacia" rows="4"><?php print $view->verificacion->getVerificacionEficacia(); ?></textarea>
        </div>

    <div class="form-group required">
        <label class="control-label" for="fecha_verificacion">Fecha verificación</label>
        <div class="inner-addon right-addon">
            <input class="form-control" type="text" name="fecha_verificacion" id="fecha_verificacion" value = "<?php print $view->verificacion->getFechaVerificacion() ?>" placeholder="DD/MM/AAAA">
            <i class="fad fa-calendar-alt"></i>
        </div>
    </div>


    <div id="myElem" class="msg" style="display:none">
        <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
    </div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary" id="submit" name="submit" type="submit"  <?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) && $view->target!='view')? '' : 'disabled' ?>  >Guardar</button>
        <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













