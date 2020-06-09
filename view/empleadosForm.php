<script type="text/javascript">


    $(document).ready(function(){


        //Necesario para que funcione el plug-in bootstrap-select
        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });

        //$('[data-toggle="tooltip"]').tooltip();

        $('#empleado-form').validate({
            rules: {
                legajo: {
                    required: true,
                    //digits: true,
                    legajo: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "empleados",
                            operation: "checkEmpleadoLegajo",
                            legajo: function(){ return $('#legajo').val();},
                            id_empleado: function(){ return $('#id_empleado').val();}

                        }
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
                            cuil: function(){ return $('#cuil').val();},
                            id_empleado: function(){ return $('#id_empleado').val();}
                        }
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
                direccion: {required: true},
                localidad: {required: true},
                telefono: {digits: true},
                email: {email: true},
                sexo: {required: true}
            },
            messages:{
                legajo: {
                    required: "Ingrese el legajo",
                    //digits: "Ingrese solo números",
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
                direccion: "Ingrese la dirección",
                localidad: "Seleccione una localidad",
                telefono: {digits: "Ingrese solo números"},
                email: {email: "Ingrese una dirección de email válida"},
                sexo: "Seleccione el sexo"
            }
            /*,tooltip_options: {
                //nombre: {trigger:'focus'},
            }*/
        });


        /*$('.input-group.date').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });*/

        moment.locale('es');
        $('input[name="fecha_nacimiento"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            "locale": {
                "format": "DD/MM/YYYY"
            }
        });



    });

</script>


<div class="col-md-3"></div>

<div class="col-md-6">



<div class="panel panel-default ">

    <div class="panel-heading">
        <h4 class="pull-left"><span><?php echo $view->label ?></span></h4>

        <a id="back" class="pull-right" href="#"><i class="fas fa-arrow-left fa-fw"></i>&nbsp;Volver </a>
        <div class="clearfix"></div>

    </div>


    <fieldset <?php //echo ( PrivilegedUser::dhasAction('EMP_UPDATE', $view->empleado->getDomain()) )? '' : 'disabled' ?>>
    <div class="panel-body">

    <form class="form-horizontal" name ="empleado-form" id="empleado-form" method="POST" action="index.php">
    <input type="hidden" name="id_empleado" id="id_empleado" value="<?php print $view->empleado->getIdEmpleado() ?>">

    <div class="form-group required">
        <label for="legajo" class="col-md-4 control-label">Legajo</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="legajo" id="legajo" placeholder="Legajo" value = "<?php print $view->empleado->getLegajo() ?>">
        </div>
    </div>

    <div class="form-group required">
        <label for="apellido" class="col-md-4 control-label">Apellido</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="apellido" id="apellido" placeholder="Apellido" value = "<?php print $view->empleado->getApellido() ?>">
        </div>
    </div>

    <div class="form-group required">
        <label for="nombre" class="col-md-4 control-label">Nombre</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre" value = "<?php print $view->empleado->getNombre() ?>">
        </div>
    </div>

    <div class="form-group required">
        <label for="documento" class="col-md-4 control-label">Nro.documento</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="documento" id="documento" placeholder="Nro. documento" value = "<?php print $view->empleado->getDocumento() ?>">
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
            <select class="form-control selectpicker show-tick" id="empresa" name="empresa" title="Seleccione la empresa">
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
            <div class="inner-addon right-addon">
                <input class="form-control" type="text" name="fecha_nacimiento" id="fecha_nacimiento" value = "<?php print $view->empleado->getFechaNacimiento() ?>" placeholder="DD/MM/AAAA"/>
                <i class="glyphicon glyphicon-calendar"></i>
            </div>
        </div>
    </div>

    
    <div class="form-group required">
        <label class="col-md-4 control-label" for="fecha">Fecha alta</label>
        <div class="col-md-8">
            <div class="input-group date">
                <input class="form-control" type="text" name="fecha_alta" id="fecha_alta" value = "<?php print $view->empleado->getFechaAlta() ?>" placeholder="DD/MM/AAAA">
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
                <input class="form-control" type="text" name="fecha_baja" id="fecha_baja" value = "<?php print $view->empleado->getFechaBaja() ?>" placeholder="DD/MM/AAAA">
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
            <select class="form-control selectpicker show-tick" id="localidad" name="localidad" title="Seleccione la localidad" data-live-search="true" data-size="5">
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
                    <input type="checkbox" id="cambio_domicilio" name="cambio_domicilio" <?php echo (!$view->empleado->getIdEmpleado())? 'disabled' :'' ?> > <a href="#" title="Registra el cambio de domicilio y conserva el anterior como historico">Cambio de domicilio</a>
                </label>
            </div>
        </div>
    </div>


    <?php if(isset($view->domicilios) && sizeof($view->domicilios)>0 ){  ?>
    <div class="table-responsive">
        <table class="table table-condensed dpTable table-hover">
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


    <div class="form-group">
        <label for="nacionalidad" class="col-md-4 control-label">Nacionalidad</label>
        <div class="col-md-8">
            <select class="form-control selectpicker show-tick" id="nacionalidad" name="nacionalidad" title="Seleccione la nacionalidad" data-live-search="true" data-size="5">
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

    <div class="form-group">
        <label for="estado_civil" class="col-md-4 control-label">Estado civil</label>
        <div class="col-md-8">
            <select class="form-control selectpicker show-tick" id="estado_civil" name="estado_civil" title="Seleccione el estado civil" data-live-search="true" data-size="5">
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


    <div class="form-group">
        <label for="id_convenio" class="col-md-4 control-label">Convenio</label>
        <div class="col-md-8">
            <select class="form-control selectpicker show-tick" id="id_convenio" name="id_convenio" data-live-search="true" data-size="5">
                <option value="">Seleccione el convenio</option>
                <?php foreach ($view->convenios as $conv){
                    ?>
                    <option value="<?php echo $conv['id_convenio']; ?>"
                        <?php echo ($conv['id_convenio'] == $view->empleado->getIdConvenio())? 'selected' :'' ?>
                        >
                        <?php echo $conv['codigo'].' '.$conv['nombre'] ;?>
                    </option>
                <?php  } ?>
            </select>
        </div>
    </div>



    <div id="myElem" class="msg" style="display:none"></div>


    </form>


    </div>



    <div class="panel-footer clearfix">
        <div class="button-group pull-right">
            <button class="btn btn-primary" id="submit" name="submit" type="submit" <?php echo ( PrivilegedUser::dhasAction('EMP_UPDATE', $view->empleado->getDomain()) && $view->target!='view')? '' : 'disabled' ?> >Guardar</button>
            <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
        </div>
    </div>











</div>
</fieldset>





</div>

















<div class="col-md-3"></div>



