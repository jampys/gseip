<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({
            //style: 'btn-info'
            //size: 4
            multipleSeparator: '-',
            showTick: true
        });
        


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#empleado-form').validate({
            rules: {
                empleado: {
                    require_from_group: {
                        param: [2, ".empleado-group"]
                        //depends: function(element) { return $('#empleado').val().length > 0;}
                    }
                },
                puesto: {required: true},
                fecha_desde: {required: true}
            },
            messages:{
                empleado: "Seleccione un empleado sugerido",
                puesto: "Seleccione un puesto",
                fecha_desde: "Seleccione la fecha desde"
            }
        });


        $('.input-daterange').datepicker({ //ok
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });

        $('#fecha_desde').datepicker().on('changeDate', function (selected) { //ok
            var minDate = new Date(selected.date.valueOf());
            $('#fecha_hasta').datepicker('setStartDate', minDate);
            //$('#fecha_hasta').datepicker('setStartDate', minDate).datepicker('update', minDate);
        });

        $('#fecha_hasta').datepicker().on('changeDate', function (selected) { //ok
            var maxDate = new Date(selected.date.valueOf());
            $('#fecha_desde').datepicker('setEndDate', maxDate);
        });

        $("#empleado").autocomplete({ //ok
            source: function( request, response ) {
                $.ajax({
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: { "term": request.term, "action":"empleados", "operation":"autocompletarEmpleadosByCuil"},
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.apellido+" "+item.nombre,
                                id: item.id_empleado

                            };
                        }));
                    }

                });
            },
            minLength: 2,
            select: function(event, ui) {
                $('#id_empleado').val(ui.item? ui.item.id : '');
                $('#empleado').val(ui.item.label);
            },
            search: function(event, ui) { $('#id_empleado').val(''); }
        });


    });

</script>





<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="empleado-form" id="empleado-form" method="POST" action="index.php">
                    <input type="hidden" name="id" id="id" value="<?php //print $view->client->getId() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="empleado">Empleado</label>
                        <input type="text" class="form-control empleado-group" id="empleado" name="empleado" placeholder="Empleado">
                        <input type="hidden" name="id_empleado" id="id_empleado" class="empleado-group"/>
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="puesto" >Puesto</label>
                        <select class="form-control" id="puesto" name="puesto">
                            <option value="" disabled selected>Seleccione el puesto</option>
                            <?php foreach ($view->puesto as $pu){
                                ?>
                                <option value="<?php echo $pu['id_puesto']; ?>"
                                    <?php //echo ($sup['codigo'] == $view->puesto->getCodigoSuperior())? 'selected' :'' ?>
                                    >
                                    <?php echo $pu['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group required">
                        <label class="control-label" for="id_proceso" >Proceso</label>

                        <div class="alert alert-info fade in">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <span class="glyphicon glyphicon-tags" ></span>&nbsp  Mantenga presionada la tecla <strong>Ctrl</strong> para seleccionar dos o mas procesos.
                        </div>

                        <select multiple class="form-control selectpicker" id="id_proceso" name="id_proceso" multiple data-selected-text-format="count">
                            <?php foreach ($view->procesos as $pro){
                                ?>
                                <option value="<?php echo $pro['id_proceso']; ?>">
                                    <?php echo $pro['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>



                    <div class="form-group required">
                        <label class="control-label" for="empleado">Desde / hasta</label>
                        <div class="input-group input-daterange">
                            <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php //print $view->contrato->getFechaDesde() ?>" placeholder="DD/MM/AAAA">
                            <div class="input-group-addon">a</div>
                            <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php //print $view->contrato->getFechaHasta() ?>" placeholder="DD/MM/AAAA">
                        </div>
                    </div>



                </form>

                <div id="myElem" style="display:none"></div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Aceptar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>



