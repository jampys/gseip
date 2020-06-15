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
        $('#fecha_desde, #fecha_hasta').daterangepicker({
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


        //cancel de formulario de etapa
        $('#role-form #cancel').on('click', function(){
            $('#role-form').hide();
        });


        $('#role-form').validate({
            rules: {
                id_role: {required: true}
            },
            messages:{
                id_role: "Seleccione un rol"
            }

        });



    });

</script>



<form name ="role-form" id="role-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label ?></strong>
    </div>

    <input type="hidden" name="id_user_role" id="id_user_role" value="<?php print $view->role->getIdUserRole() ?>">
    <input type="hidden" name="id_user" id="id_user" value="<?php print $view->role->getIdUser() ?>">


        <div class="form-group required">
            <label for="id_role" class="control-label">Rol</label>
            <select class="selectpicker form-control show-tick" id="id_role" name="id_role" data-live-search="true" data-size="5">
                <option value="">Seleccione un Rol</option>
                <?php foreach ($view->roles as $rol){ ?>
                    <option value="<?php echo $rol['id_role']; ?>"
                        <?php echo ($rol['id_role'] == $view->role->getIdRole())? 'selected' :'' ?>
                        >
                        <?php echo $rol['nombre']; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group">
            <label class="control-label" for="fecha_desde">Fecha desde</label>
            <div class="inner-addon right-addon">
                <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php print $view->role->getFechaDesde() ?>" placeholder="DD/MM/AAAA" disabled>
                <i class="glyphicon glyphicon-calendar"></i>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label" for="fecha_hasta">Fecha hasta</label>
            <div class="inner-addon right-addon">
                <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php print $view->role->getFechaHasta() ?>" placeholder="DD/MM/AAAA" readonly>
                <i class="glyphicon glyphicon-calendar"></i>
            </div>
        </div>


    <div id="myElem" class="msg" style="display:none">
        <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
    </div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary" id="submit" name="submit" type="submit" <?php echo ( PrivilegedUser::dhasPrivilege('USR_ABM', array(1)) && $view->target!='view')? '' : 'disabled' ?>  >Guardar</button>
        <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













