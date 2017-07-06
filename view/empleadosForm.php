<script type="text/javascript">


    $(document).ready(function(){


        $('#client').validate({
            rules: {
                nombre: {
                    required: true
                }
            },
            messages:{
                nombre: "Ingrese su nombre"
            }
        });


        $('#fecha').datepicker({
            inline: true
            ,dateFormat:"dd/mm/yy"
        });


    });

</script>

<!--<h2><?php echo $view->label ?></h2>-->
<form name ="client" id="client" method="POST" action="index.php">
    <input type="hidden" name="id" id="id" value="<?php print $view->client->getId() ?>">

    <div class="form-group">
        <label for="nombre">Nombre</label>
        <input class="form-control" type="text" name="nombre" id="nombre" value = "<?php print $view->client->getNombre() ?>">
    </div>
    <div class="form-group">
        <label for="apellido">Apellido</label>
        <input class="form-control" type="text" name="apellido" id="apellido"value = "<?php print $view->client->getApellido() ?>">
    </div>
    <div class="form-group">
        <label for="fecha">Fecha</label>
        <input class="form-control" type="text" name="fecha" id="fecha" value = "<?php print $view->client->getFecha() ?>">
        <p class="help-block"> dd/mm/yyyy </p>
    </div>
    <div class="form-group">
        <label for="peso">Peso</label>
        <input class="form-control" type="text" name="peso" id="peso" value = "<?php print $view->client->getPeso() ?>">
    </div>
    <div class="form-group">
        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
        <button class="btn btn-primary btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>
</form>

<div id="myElem" style="display:none">

</div>



