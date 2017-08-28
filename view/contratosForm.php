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



        $('#fecha_nacimiento').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });

        $('#fecha_alta').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });

        $('#fecha_baja').datepicker({
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
            <input type="text" class="form-control empleado-group" id="responsable" name="responsable" placeholder="Responsable">
            <input type="hidden" name="id_empleado" id="id_empleado" class="empleado-group"/>
        </div>
    </div>



    <div class="form-group required">
        <label for="cuil" class="col-md-4 control-label">CUIL</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="cuil" id="cuil" placeholder="CUIL" value = "<?php print $view->empleado->getCuil() ?>">
        </div>
    </div>


    <div class="form-group required">
        <label for="tipo" class="col-md-4 control-label">Empresa</label>
        <div class="col-md-8">
            <select class="form-control" id="empresa" name="empresa">
                <option value="" disabled selected>Seleccione la empresa</option>
                <?php foreach ($view->empresas['enum'] as $emp){
                    ?>
                    <option value="<?php echo $emp; ?>"
                        <?php echo ($emp == $view->empleado->getEmpresa() OR ($emp == $view->empresas['default'] AND !$view->empleado->getIdEmpleado()) )? 'selected' :'' ?>
                        >
                        <?php echo $emp; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>
    </div>

    <div class="form-group required">
        <label class="col-md-4 control-label" for="fecha">Fecha nacimiento</label>
        <div class="col-md-8">
            <div class="input-group date">
                <input class="form-control" type="text" name="fecha_nacimiento" id="fecha_nacimiento" value = "<?php print $view->empleado->getFechaNacimiento() ?>" placeholder="Fecha nacimiento">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
    </div>


    <div class="form-group required">
        <label class="col-md-4 control-label" for="fecha">Fecha alta</label>
        <div class="col-md-8">
            <div class="input-group date">
                <input class="form-control" type="text" name="fecha_alta" id="fecha_alta" value = "<?php print $view->empleado->getFechaAlta() ?>" placeholder="Fecha alta">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-4 control-label" for="fecha">Fecha baja</label>
        <div class="col-md-8">
            <div class="input-group date">
                <input class="form-control" type="text" name="fecha_baja" id="fecha_baja" value = "<?php print $view->empleado->getFechaBaja() ?>" placeholder="Fecha baja">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
    </div>



    <hr/>
    <div class="form-group required">
        <label for="domicilio" class="col-md-4 control-label">Dirección</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="direccion" id="direccion" placeholder="Dirección" value = "<?php print $view->empleado->getDireccion() ?>">
        </div>
    </div>

    <div class="form-group required">
        <label for="lugar_residencia" class="col-md-4 control-label">Localidad</label>
        <div class="col-md-8">
            <select class="form-control" id="localidad" name="localidad">
                <option value="" disabled selected>Seleccione la localidad</option>
                <?php foreach ($view->localidades as $loc){
                    ?>
                    <option value="<?php echo $loc['id_localidad']; ?>"
                        <?php echo ($loc['id_localidad'] == $view->empleado->getIdLocalidad())? 'selected' :'' ?>
                        >
                        <?php echo $loc['CP'].' '.$loc['ciudad'].' '.$loc['provincia'] ;?>
                    </option>
                <?php  } ?>
            </select>
        </div>
    </div>


    <div class="form-group">
        <div class="col-md-offset-4 col-md-8">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="cambio_domicilio" name="cambio_domicilio" <?php echo (!$view->empleado->getIdEmpleado())? 'disabled' :'' ?> > <a href="#" data-toggle="tooltip" title="Registra el cambio de domicilio y conserva el anterior como historico">Cambio de domicilio</a>
                </label>
            </div>
        </div>
    </div>


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

















    <div class="form-group">
        <label for="telefono" class="col-md-4 control-label">Teléfono</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="telefono" id="telefono" placeholder="Teléfono" value = "<?php print $view->empleado->getTelefono() ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="email" class="col-md-4 control-label">Email</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="email" id="email" placeholder="Email" value = "<?php print $view->empleado->getEmail() ?>">
        </div>
    </div>


    <div class="form-group required">
        <label for="sexo" class="col-md-4 control-label">Sexo</label>
        <div class="col-md-8">

            <?php foreach($view->sexos['enum'] as $val){ ?>
                <label class="radio-inline">
                    <input type="radio" name="sexo" value="<?php echo $val ?>"
                        <?php echo ($val == $view->empleado->getSexo() OR ($val == $view->sexos['default'] AND !$view->empleado->getIdEmpleado()))? 'checked' :'' ?>
                        ><?php echo $val ?>
                </label>
            <?php } ?>


        </div>
    </div>


    <div class="form-group required">
        <label for="nacionalidad" class="col-md-4 control-label">Nacionalidad</label>
        <div class="col-md-8">
            <select class="form-control" id="nacionalidad" name="nacionalidad">
                <option value="" disabled selected>Seleccione la nacionalidad</option>
                <?php foreach ($view->nacionalidades['enum'] as $nac){
                    ?>
                    <option value="<?php echo $nac; ?>"
                        <?php echo ($nac == $view->empleado->getNacionalidad() OR ($nac == $view->nacionalidades['default'] AND !$view->empleado->getIdEmpleado()) )? 'selected' :'' ?>
                        >
                        <?php echo $nac; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>
    </div>

    <div class="form-group required">
        <label for="estado_civil" class="col-md-4 control-label">Estado civil</label>
        <div class="col-md-8">
            <select class="form-control" id="estado_civil" name="estado_civil">
                <option value="" disabled selected>Seleccione el estado civil</option>
                <?php foreach ($view->estados_civiles['enum'] as $ec){
                    ?>
                    <option value="<?php echo $ec; ?>"
                        <?php echo ($ec == $view->empleado->getEstadoCivil() OR ($ec == $view->estados_civiles['default'] AND !$view->empleado->getIdEmpleado())  )? 'selected' :'' ?>
                        >
                        <?php echo $ec; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>
    </div>



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



