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

        /*$('.input-daterange').datepicker({
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });*/


        /*$('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });*/


        //cancel de formulario de etapa
        $('#role-form #cancel').on('click', function(){
            $('#role-form').hide();
        });


        $('#role-form').validate({
            rules: {
                /*codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },*/
                id_vehiculo: {
                 required: true,
                 remote: {
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: {
                            action: "contrato-vehiculo",
                            operation: "checkVehiculo",
                            id_vehiculo: function(){ return $('#id_vehiculo').val();},
                            id_contrato: function(){ return $('#myModal #id_contrato').val();},
                            id_contrato_vehiculo: function(){ return $('#id_contrato_vehiculo').val();}
                          }
                 }
                 },
                fecha_desde: {required: true}
            },
            messages:{
                /*codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                }, */
                id_vehiculo: {
                 required: "Seleccione un vehículo",
                 remote: "El vehículo ya se encuentra en el contrato"
                 },
                //id_vehiculo: "Seleccione un vehículo",
                fecha_desde: "Seleccione una fecha desde"
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
            <select class="selectpicker form-control show-tick" id="id_role" name="id_role" data-live-search="true" data-size="5" disabled>
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
            <div class="input-group date">
                <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php print $view->role->getFechaDesde() ?>" placeholder="DD/MM/AAAA" disabled>
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label" for="fecha_hasta">Fecha hasta</label>
            <div class="input-group date">
                <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php print $view->role->getFechaHasta() ?>" placeholder="DD/MM/AAAA">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>


    <div id="myElem" class="msg" style="display:none"></div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary" id="submit" name="submit" type="submit">Guardar</button>
        <!--<button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













