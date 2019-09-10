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
        $('#contrato-vehiculo-form #cancel').on('click', function(){
            $('#contrato-vehiculo-form').hide();
        });


        $('#contrato-vehiculo-form').validate({
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
                            action: "vto_grupo-vehiculo",
                            operation: "checkVehiculo",
                            id_vehiculo: function(){ return $('#id_vehiculo').val();},
                            id_vencimiento: function(){ return $('#myModal #id_vencimiento').val();},
                            id_grupo_vehiculo: function(){ return $('#id_grupo_vehiculo').val();}
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
                 remote: "El vehículo se encuentra activo en un grupo"
                 },
                //id_vehiculo: "Seleccione un vehículo",
                fecha_desde: "Seleccione una fecha desde"
            }

        });



    });

</script>



<form name ="contrato-vehiculo-form" id="contrato-vehiculo-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label ?></strong>
    </div>

    <input type="hidden" name="id_contrato_vehiculo" id="id_contrato_vehiculo" value="<?php print $view->contrato_vehiculo->getIdVehiculoContrato() ?>">
    <input type="hidden" name="id_contrato" id="id_contrato" value="<?php print $view->contrato_vehiculo->getIdContrato() ?>">


        <div class="form-group required">
            <label for="id_vehiculo" class="control-label">Vehículo</label>
            <select class="selectpicker form-control show-tick" id="id_vehiculo" name="id_vehiculo" data-live-search="true" data-size="5">
                <option value="">Seleccione un Vehículo</option>
                <?php foreach ($view->vehiculos as $ar){ ?>
                    <option value="<?php echo $ar['id_vehiculo']; ?>"
                            data-content="<span class='label label-primary' style='font-weight: normal'><?php echo $ar['matricula']; ?></span> <span class='label label-default' style='font-weight: normal'><?php echo $ar['nro_movil']; ?></span> <?php echo $ar['modelo']; ?>"
                        <?php echo ($ar['id_vehiculo'] == $view->contrato_vehiculo->getIdVehiculo())? 'selected' :'' ?>
                        >
                        <?php //echo $ar['matricula'].' '.$ar['nro_movil'].' '.$ar['modelo']; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group">
            <label class="control-label" for="id_localidad" >Ubicación</label>
            <select class="form-control selectpicker show-tick" id="id_localidad" name="id_localidad" title="Seleccione la ubicación" data-live-search="true" data-size="5">
                <?php foreach ($view->localidades as $loc){
                    ?>
                    <option value="<?php echo $loc['id_localidad']; ?>"
                        <?php echo ($loc['id_localidad'] == $view->contrato_vehiculo->getIdLocalidad())? 'selected' :'' ?>
                        >
                        <?php echo $loc['CP'].' '.$loc['ciudad'].' '.$loc['provincia'] ;?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group required">
            <label class="control-label" for="empleado">Fecha desde / hasta</label>
            <div class="input-group input-daterange">
                <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php print $view->contrato_vehiculo->getFechaDesde() ?>" placeholder="DD/MM/AAAA">
                <div class="input-group-addon">a</div>
                <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php print $view->contrato_vehiculo->getFechaHasta() ?>" placeholder="DD/MM/AAAA">
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













