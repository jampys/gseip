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
        <input class="form-control" type="text" name="fecha" id="fecha" value = "<?php print $view->client->getFecha() ?>">(yyyy-mm-dd)
    </div>
    <div class="form-group">
        <label for="peso">Peso</label>
        <input class="form-control" type="text" name="peso" id="peso" value = "<?php print $view->client->getPeso() ?>">
    </div>
    <div class="form-group">
        <input class="btn btn-primary btn-sm" id="cancel" type="button" value ="Cancelar" />
        <input class="btn btn-primary btn-sm" id="submit" type="submit" name="submit" value ="Guardar" />
    </div>
</form>

<div id="myElem" style="display:none">

</div>



