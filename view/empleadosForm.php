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


<h3><strong><?php echo $view->label ?></strong></h3>

<form name ="client" id="client" method="POST" action="index.php">
    <input type="hidden" name="id" id="id" value="<?php //print $view->client->getId() ?>">

    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
        <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre" value = "<?php //print $view->client->getNombre() ?>">
    </div>
    <br>
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
        <input class="form-control" type="text" name="apellido" id="apellido" placeholder="apellido" value = "<?php //print $view->client->getApellido() ?>">
    </div>
    <br>
    <div class="form-group">
            <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
            <button class="btn btn-primary btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>

    </div>

</form>


<div id="myElem" style="display:none">

</div>



