<script type="text/javascript">


    $(document).ready(function(){



        $('#login_form').validate({
            rules: {
                usuario: {required: true},
                contraseña: {required: true}
            },
            messages:{
                usuario: "Ingrese su usuario",
                contraseña: "Ingrese su contraseña"
            },
            tooltip_options: {
                usuario: {trigger:'focus'},
                contraseña: {trigger:'focus'}

            }
        });





    });

</script>



<div class="col-md-4"></div>


<div class="col-md-4">

<form name ="login_form" id="login_form" method="POST" action="index.php">

    <div class="form-group">
        <label class="control-label" for="usuario">Usuario</label>
        <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Usuario" >
    </div>
    <div class="form-group">
        <label class="control-label" for="contraseña">Contraseña</label>
        <input class="form-control" type="password" name="contraseña" id="contraseña" placeholder="Contraseña" >
    </div>

    <div class="form-group">
        <button class="btn btn-primary btn-sm btn-block" id="ingresar" name="ingresar" type="submit"><span class="glyphicon glyphicon-log-in"></span> Ingresar</button>
    </div>
</form>

    <div id="myElem" style="display:none">

    </div>

</div>


<div class="col-md-4"></div>

