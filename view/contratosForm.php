<script type="text/javascript">


    $(document).ready(function(){

        $('[data-toggle="tooltip"]').tooltip();

        $('#contrato-form').validate({ //ok
            rules: {
                nro_contrato: {
                    required: true,
                    digits: true},
                compania: {required: true},
                responsable: {
                    require_from_group: {
                        param: [2, ".responsable-group"],
                        depends: function(element) { return $('#responsable').val().length > 0;}
                    }
                },
                fecha_desde: {required: true},
                fecha_hasta: {required: true}
            },
            messages:{
                nro_contrato: {
                    required: "Ingrese nro. de contrato",
                    digits: "Ingrese solo números"
                },
                compania: "Ingrese la compañía",
                responsable: "Seleccione un empleado sugerido",
                fecha_desde: "Seleccione la fecha desde",
                fecha_hasta: "Seleccione la fecha hasta"
            }

        });



        $("#responsable").autocomplete({ //ok
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
                                id: item.cuil

                            };
                        }));
                    }

                });
            },
            minLength: 2,
            change: function(event, ui) {
                $('#id_empleado').val(ui.item? ui.item.id : '');
                $('#responsable').val(ui.item.label);
            }
        });




        $('#fecha_desde').datepicker({ //ok
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        }).on('changeDate', function (selected) {
                var minDate = new Date(selected.date.valueOf());
                $('#fecha_hasta').datepicker('setStartDate', minDate).datepicker('update', minDate);
        });

        $('#fecha_hasta').datepicker({ //ok
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        }).on('changeDate', function (selected) {
                var maxDate = new Date(selected.date.valueOf());
                $('#fecha_desde').datepicker('setEndDate', maxDate);
            });





    });

</script>


<div class="col-md-3"></div>

<div class="col-md-6">


<div class="panel panel-default ">
    <div class="panel-heading"><h4><?php echo $view->label ?></h4></div>

    <div class="panel-body">




    <form class="form-horizontal" name ="contrato-form" id="contrato-form" method="POST" action="index.php">
    <input type="hidden" name="id_contrato" id="id_contrato" value="<?php print $view->contrato->getIdContrato() ?>">

    <div class="form-group required">
        <label for="nro_contrato" class="col-md-4 control-label">Nro. Contrato</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="nro_contrato" id="nro_contrato" placeholder="Nro. Contrato" value = "<?php print $view->contrato->getNroContrato() ?>">
        </div>
    </div>

    <div class="form-group required">
        <label for="compania" class="col-md-4 control-label">Compañía</label>
        <div class="col-md-8">
            <select class="form-control" id="localidad" name="localidad">
                <option value="" disabled >Seleccione la compañía</option>
                <?php foreach ($view->companias as $cia){
                    ?>
                    <option value="<?php echo $cia['id_compania']; ?>"
                        <?php echo ($cia['id_compania'] == $view->contrato->getIdCompania())? 'selected' :'' ?>
                        >
                        <?php echo $cia['razon_social'];?>
                    </option>
                <?php  } ?>
            </select>
        </div>
    </div>


    <div class="form-group required">
        <label for="responsable" class="col-md-4 control-label">Responsable</label>
        <div class="col-md-8">
            <input type="text" class="form-control responsable-group" id="responsable" name="responsable" placeholder="Responsable" value ="<?php print $view->responsable; ?>">
            <input type="hidden" name="id_empleado" id="id_empleado" class="responsable-group" value = "<?php print $view->contrato->getResponsable() ?>" >
        </div>
    </div>


        <div class="form-group required">
            <label class="col-md-4 control-label" for="fecha">Desde / hasta</label>
            <div class="col-md-8">

                <div class="input-group input-daterange">
                    <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php print $view->contrato->getFechaDesde() ?>" placeholder="Fecha desde">
                    <div class="input-group-addon">a</div>
                    <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php print $view->contrato->getFechaHasta() ?>" placeholder="Fecha hasta">
                </div>

            </div>
        </div>



    <hr/>


        <div class="clearfix">
            <h4 class="pull-left">Empleados</h4>
            <button class="btn btn-primary btn-sm pull-right" id="new" >Agregar</button>
        </div>


    <div class="table-responsive">
        <table class="table table-condensed dataTable table-hover">
            <thead>
            <tr>
                <th>Dirección</th>
                <th>Localidad</th>
                <th>F. Desde</th>
                <th>F. Hasta</th>
            </tr>
            </thead>
            <tbody>
            <!-- se genera dinamicamente desde javascript -->
            </tbody>
        </table>
    </div>



    <hr/>





    <div id="myElem" style="display:none"></div>


    </form>


    </div>



    <div class="panel-footer clearfix">
        <div class="button-group pull-right">
            <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
            <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>
        </div>
    </div>











</div>





</div>

















<div class="col-md-3"></div>



