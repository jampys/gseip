<script type="text/javascript">


    $(document).ready(function(){

        //Necesario para que funcione el plug-in bootstrap-select
        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        /*$('.input-group.date').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });*/

        $('.input-daterange').datepicker({ //ok
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        //cancel de formulario de etapa
        $('#grupo-vehiculo-form #cancel').on('click', function(){
            $('#grupo-vehiculo-form').hide();
        });


        $('#grupo-vehiculo-form').validate({ //ok
            rules: {
                /*codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },*/
                conductor: {
                 required: true,
                 remote: {
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: {
                            action: "parte-empleado",
                            operation: "checkEmpleado",
                            id_parte_empleado: function(){ return $('#id_parte_empleado').val();},
                            id_parte: function(){ return $('#id_parte').val();}
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
                conductor: {
                 required: "Seleccione un vehículo",
                 remote: "La cuadrilla tiene asignado otro conductor"
                 },
                //id_vehiculo: "Seleccione un vehículo",
                fecha_desde: "Seleccione una fecha desde"
            }

        });



    });

</script>



<form name ="grupo-vehiculo-form" id="grupo-vehiculo-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label ?></strong>
    </div>

    <input type="hidden" name="id_grupo_vehiculo" id="id_grupo_vehiculo" value="<?php print $view->grupo_vehiculo->getIdGrupoVehiculo() ?>">
    <input type="hidden" name="id_grupo" id="id_grupo" value="<?php print $view->grupo_vehiculo->getIdGrupoVehiculo() ?>">


        <div class="form-group required">
            <label for="id_vehiculo" class="control-label">Vehículo</label>
            <select class="selectpicker form-control show-tick" id="id_vehiculo" name="id_vehiculo" data-live-search="true" data-size="5">
                <option value="">Seleccione un Vehículo</option>
                <?php foreach ($view->vehiculos as $ar){ ?>
                    <option value="<?php echo $ar['id_vehiculo']; ?>"
                            data-content="<span class='label label-primary' style='font-weight: normal'><?php echo $ar['matricula']; ?></span> <span class='label label-default' style='font-weight: normal'><?php echo $ar['nro_movil']; ?></span> <?php echo $ar['modelo']; ?>"
                        <?php echo ($ar['id_vehiculo'] == $view->grupo_vehiculo->getIdVehiculo())? 'selected' :'' ?>
                        >
                        <?php //echo $ar['matricula'].' '.$ar['nro_movil'].' '.$ar['modelo']; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group required">
            <label class="control-label" for="empleado">Fecha desde / hasta</label>
            <div class="input-group input-daterange">
                <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php print $view->grupo_vehiculo->getFechaDesde() ?>" placeholder="DD/MM/AAAA">
                <div class="input-group-addon">a</div>
                <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php print $view->grupo_vehiculo->getFechaHasta() ?>" placeholder="DD/MM/AAAA">
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













