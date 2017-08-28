<script type="text/javascript">


    $(document).ready(function(){

        $('[data-toggle="tooltip"]').tooltip();

        $('#empleado-form').validate({
            rules: {
                legajo: {
                    required: true,
                    digits: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "empleados",
                            operation: "checkEmpleadoLegajo",
                            legajo: function(){ return $('#legajo').val();}}
                    }
                },
                nombre: {required: true},
                apellido: {required: true},
                documento: {required: true,
                            digits: true},
                cuil: {
                    required: true,
                    digits: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "empleados",
                            operation: "checkEmpleadoCuil",
                            cuil: function(){ return $('#cuil').val();}}
                        /*success: function(data, textStatus, jqXHR) {
                            console.log(textStatus, jqXHR, data);
                        },
                        error: function(data, textStatus, errorThrown) {
                            console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                        }*/

                    }
                },
                fecha_nacimiento: {required: true},
                fecha_alta: {required: true},
                domicilio: {required: true},
                lugar_residencia: {required: true},
                telefono: {digits: true},
                email: {email: true},
                sexo: {required: true}
            },
            messages:{
                legajo: {
                    required: "Ingrese el legajo",
                    digits: "Ingrese solo números",
                    remote: "El legajo ingresado ya existe"
                },
                nombre: "Ingrese el nombre",
                apellido: "Ingrese el apellido",
                documento: {required: "Ingrese el Nro. documento",
                            digits: "Ingrese solo números"},
                cuil: {
                    required: "Ingrese el CUIL",
                    digits: "Ingrese solo números",
                    remote: "El CUIL ingresado ya existe"
                },
                fecha_nacimiento: "Ingrese la fecha de nacimiento",
                fecha_alta: "Ingrese la fecha de alta",
                domicilio: "Ingrese el domicilio",
                lugar_residencia: "Seleccione la localidad",
                telefono: {digits: "Ingrese solo números"},
                email: {email: "Ingrese una dirección de email válida"},
                sexo: "Seleccione el sexo"
            }
            /*,tooltip_options: {
                //nombre: {trigger:'focus'},
            }*/
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




        $('#fecha_desde').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });

        $('#fecha_hasta').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
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
            <input type="text" class="form-control empleado-group" id="responsable" name="responsable" placeholder="Responsable" value ="<?php print $view->responsable; ?>">
            <input type="hidden" name="id_empleado" id="id_empleado" class="empleado-group" value = "<?php print $view->contrato->getResponsable() ?>" >
        </div>
    </div>






        <div class="form-group">
            <label class="col-md-4 control-label" for="fecha">Fecha hasta</label>
            <div class="col-md-8">

                <div class="input-group input-daterange">
                    <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php print $view->contrato->getFechaDesde() ?>" placeholder="Fecha desde">
                    <div class="input-group-addon">a</div>
                    <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php print $view->contrato->getFechaHasta() ?>" placeholder="Fecha hasta">
                </div>

            </div>
        </div>






    <hr/>



    <?php if($view->domicilios){  ?>
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
            <?php foreach ($view->domicilios as $dom):  ?>
                <tr>
                    <td><?php echo $dom['direccion'];?></td>
                    <td><?php echo $dom['CP'].' '.$dom['ciudad'].' '.$dom['provincia'];?></td>
                    <td><?php echo $dom['fecha_desde'];?></td>
                    <td><?php echo $dom['fecha_hasta'];?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php } ?>


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



