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
            showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            drops: 'auto',
            parentEl: '#myModal',
            minDate: '01/01/2010',
            maxDate: '31/12/2029',
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
        $('#grupo-vehiculo-form #cancel').on('click', function(){
            $('#grupo-vehiculo-form').hide();
        });


        $('#grupo-vehiculo-form').validate({ //ok
            rules: {
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
                fecha_desde: {
                    required: true,
                    validDate: true
                },
                fecha_hasta: {validDate: true}
            },
            messages:{
                id_vehiculo: {
                 required: "Seleccione un vehículo",
                 remote: "El vehículo se encuentra activo en una flota"
                 },
                fecha_desde: {
                    required: "Ingrese la fecha desde",
                    validDate: "Ingrese un formato de fecha válido DD/MM/AAAA"
                },
                fecha_hasta: {
                    validDate: "Ingrese un formato de fecha válido DD/MM/AAAA"
                }
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
    <input type="hidden" name="id_grupo" id="id_grupo" value="<?php print $view->grupo_vehiculo->getIdGrupo() ?>">


        <div class="form-group required">
            <label for="id_vehiculo" class="control-label">Vehículo</label>
            <select class="selectpicker form-control show-tick" id="id_vehiculo" name="id_vehiculo" data-live-search="true" data-size="5">
                <option value="">Seleccione un Vehículo</option>
                <?php foreach ($view->vehiculos as $ar){ ?>
                    <option value="<?php echo $ar['id_vehiculo']; ?>"
                        <?php echo ($ar['id_vehiculo'] == $view->grupo_vehiculo->getIdVehiculo())? 'selected' :'' ?>
                        >
                        <?php echo $ar['matricula'].' '.$ar['nro_movil'].' '.$ar['modelo']; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group">
            <label class="control-label" for="certificado">Certificado</label>
            <input class="form-control" type="text" name="certificado" id="certificado" value = "<?php print $view->grupo_vehiculo->getCertificado() ?>" placeholder="Nro. de certificado">
        </div>


        <div class="row">
            <div class="form-group col-md-6 required">
                <label for="fecha_desde" class="control-label">Fecha desde</label>
                <div class="inner-addon right-addon">
                    <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php print $view->grupo_vehiculo->getFechaDesde() ?>" placeholder="DD/MM/AAAA">
                    <i class="fad fa-calendar-alt"></i>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="fecha_hasta" class="control-label">Fecha hasta</label>
                <div class="inner-addon right-addon">
                    <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php print $view->grupo_vehiculo->getFechaHasta() ?>" placeholder="DD/MM/AAAA">
                    <i class="fad fa-calendar-alt"></i>
                </div>
            </div>
        </div>


    <div id="myElem" class="msg" style="display:none">
        <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
    </div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary" id="submit" name="submit" type="submit" <?php echo ( PrivilegedUser::dhasAction('GRV_UPDATE', array(1)) && $view->target!='view')? '' : 'disabled' ?>  >Guardar</button>
        <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













