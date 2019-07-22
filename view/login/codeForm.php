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
        <label class="control-label" for="usuario">Code</label>
        <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Correo" >
    </div>

    <div class="form-group">
        <button class="btn btn-primary btn-block" id="ingresar" name="ingresar" type="submit"><span class="glyphicon glyphicon-log-in"></span> Ingresar</button>
    </div>

</form>

    <div id="myElem" class="msg" style="display:none">

    </div>

</div>


<div class="col-md-4"></div>

