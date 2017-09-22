<script type="text/javascript">


    $(document).ready(function(){


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#objetivo').validate({
            rules: {
                nombre: {required: true},
                tipo: {required: true},
                superior: {required: true}
            },
            messages:{
                nombre: "Ingrese el nombre",
                tipo: "Seleccione el tipo",
                superior: "Seleccione el objetivo de nivel superior"
            }

        });



    });

</script>





<div class="col-md-3"></div>

<div class="col-md-6">


<div class="panel panel-default ">
<div class="panel-heading"><h4><?php echo $view->label ?></h4></div>

<div class="panel-body">




<form class="form-horizontal" name ="objetivo-form" id="objetivo-form" method="POST" action="index.php">
<input type="hidden" name="id_objetivo" id="id_objetivo" value="<?php print $view->objetivo->getIdObjetivo() ?>">

<div class="form-group required">
    <label for="nombre" class="col-md-4 control-label">Nombre</label>
    <div class="col-md-8">
        <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre" value = "<?php print $view->objetivo->getNombre() ?>">
    </div>
</div>

<div class="form-group required">
    <label for="documento" class="col-md-4 control-label">Nro.documento</label>
    <div class="col-md-8">
        <input class="form-control" type="text" name="documento" id="documento" placeholder="Nro. documento" value = "<?php //print $view->empleado->getDocumento() ?>">
    </div>
</div>

<div class="form-group required">
    <label for="cuil" class="col-md-4 control-label">CUIL</label>
    <div class="col-md-8">
        <input class="form-control" type="text" name="cuil" id="cuil" placeholder="CUIL" value = "<?php //print $view->empleado->getCuil() ?>">
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
            <input class="form-control" type="text" name="fecha_nacimiento" id="fecha_nacimiento" value = "<?php //print $view->empleado->getFechaNacimiento() ?>" placeholder="Fecha nacimiento">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
            </div>
        </div>
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



