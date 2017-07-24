<script type="text/javascript">


    $(document).ready(function(){

        $('#client').validate({
            rules: {
                nombre: {required: true},
                apellido: {required: true}
            },
            messages:{
                nombre: "Ingrese su nombre",
                apellido: "Ingrese su apellido"
            },
            tooltip_options: {
                nombre: {trigger:'focus'},
                apellido: {trigger:'focus'}

            }
        });

        $('#fecha_nacimiento').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });


    });

</script>


<div class="col-md-3"></div>



<div class="col-md-6">

<h3><strong><?php echo $view->label ?></strong></h3>

<form class="form-horizontal" name ="client" id="client" method="POST" action="index.php">
    <input type="hidden" name="id_empleado" id="id_empleado" value="<?php print $view->empleado->getIdEmpleado() ?>">

    <div class="form-group required">
        <label for="nombre" class="col-md-4 control-label">Nombre</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre" value = "<?php print $view->empleado->getNombre() ?>">
        </div>
    </div>

    <div class="form-group required">
        <label for="apellido" class="col-md-4 control-label">Apellido</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="apellido" id="apellido" placeholder="Apellido" value = "<?php print $view->empleado->getApellido() ?>">
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
            <input class="form-control" type="text" name="cuil" id="cuil" placeholder="CUIL" value = "<?php //print $view->client->getApellido() ?>">
        </div>
    </div>

    <div class="form-group required">
        <label class="col-md-4 control-label" for="fecha">Fecha nacimiento</label>
        <div class="col-md-8">
            <div class="input-group date">
                <input class="form-control" type="text" name="fecha_nacimiento" id="fecha_nacimiento" value = "<?php //print $view->client->getFecha() ?>" placeholder="Fecha nacimiento">
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
                <input class="form-control" type="text" name="fecha_alta" id="fecha_alta" value = "<?php //print $view->client->getFecha() ?>" placeholder="Fecha alta">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group required">
        <label class="col-md-4 control-label" for="fecha">Fecha baja</label>
        <div class="col-md-8">
            <div class="input-group date">
                <input class="form-control" type="text" name="fecha_baja" id="fecha_baja" value = "<?php //print $view->client->getFecha() ?>" placeholder="Fecha baja">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group required">
        <label for="domicilio" class="col-md-4 control-label">Domicilio</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="domicilio" id="domicilio" placeholder="Domicilio" value = "<?php //print $view->client->getApellido() ?>">
        </div>
    </div>

    <div class="form-group required">
        <label for="telefono" class="col-md-4 control-label">Teléfono</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="telefono" id="telefono" placeholder="Teléfono" value = "<?php //print $view->client->getApellido() ?>">
        </div>
    </div>

    <div class="form-group required">
        <label for="email" class="col-md-4 control-label">Email</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="email" id="email" placeholder="Email" value = "<?php //print $view->client->getApellido() ?>">
        </div>
    </div>


    <div class="button-group pull-right">
            <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
            <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>

    </div>

</form>


<div id="myElem" style="display:none">

</div>

</div>




<div class="col-md-3"></div>



