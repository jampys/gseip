<style>
    #brand-image{
        height: 120%;
    }

</style>



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


        $(document).on('click', '#regresar',function(){
            //alert('regresar');
            window.location.href = "index.php?action=login";
            return false;
        });





    });

</script>



<div class="col-md-4"></div>


<div class="col-md-4">


        <nav class="navbar navbar-default col-md-12">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php" style="padding-left: 0">
                        <img id="brand-image" src="resources/img/seip140x40.png">
                    </a>
                </div>

        </nav>




<form name ="login_form" id="login_form" method="POST" action="index.php">

    <div class="form-group">
        <label class="control-label" for="email">Recuperar contraseña</label>
        <input class="form-control" type="text" name="email" id="email" placeholder="Ingrese su correo" >
    </div>


    <div class="form-group">
        <button class="btn btn-primary btn-block" id="restaurar" name="restaurar" type="submit">Restaurar</button>
    </div>

    <div class="form-group" style="text-align: center">
        <button id="regresar" name="regresar" type="button" class="btn btn-link">
            <small>Regresar al área de ingreso</small>
        </button>
    </div>
</form>

    <div id="myElem" class="msg" style="display:none">

    </div>

</div>


<div class="col-md-4"></div>

