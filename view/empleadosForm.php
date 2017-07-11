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


<div class="col-md-3"></div>



<div class="col-md-6">

<h3><strong><?php echo $view->label ?></strong></h3>

<form class="form-horizontal" name ="client" id="client" method="POST" action="index.php">
    <input type="hidden" name="id" id="id" value="<?php //print $view->client->getId() ?>">

    <div class="form-group required">
        <label for="nombre" class="col-md-2 control-label">Nombre</label>
        <div class="col-md-10">
            <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre" value = "<?php //print $view->client->getNombre() ?>">
        </div>
    </div>

    <div class="form-group required">
        <label for="apellido" class="col-md-2 control-label">Apellido</label>
        <div class="col-md-10">
            <input class="form-control" type="text" name="apellido" id="apellido" placeholder="Apellido" value = "<?php //print $view->client->getApellido() ?>">
        </div>
    </div>


    <div class="form-group">
            <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
            <button class="btn btn-primary btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>

    </div>

</form>


<div id="myElem" style="display:none">

</div>

</div>




<div class="col-md-3"></div>



