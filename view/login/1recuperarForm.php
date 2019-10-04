<style>
    #brand-image{
        height: 120%;
    }

</style>



<script type="text/javascript">


    $(document).ready(function(){


        $(document).on('click', '#restaurar',function(){

            if ($("#recuperar_form").valid()){
                //alert('boton restaurar');
                var params={};
                params.action='login';
                params.operation='check-user-exists';
                params.usuario=$('#usuario').val();
                //params.contraseña=$('#contraseña').val();

                $.ajax({
                    url:"index.php",
                    type:"post",
                    data: params,
                    dataType:"json",//xml,html,script,json
                    success: function(data, textStatus, jqXHR) {

                        if(data['id'] >= 1){ //Envió codigo por email con exito
                            $("#myElem").html(data['msg']).removeClass('alert alert-info').addClass('alert alert-success').show();
                            setTimeout(function(){ $("#myElem").hide();
                                                   //window.location.href = "../../index.php";
                                                    window.location.href = "index.php?action=login&operation=toCodeForm";
                                                 }, 3000);
                        }
                        else {
                            $("#myElem").html(data['msg']).addClass('alert alert-danger').show();
                        }

                    },
                    error: function(data, textStatus, errorThrown) {
                     alert(data.responseText);
                        /*$("#myElem").html('Error de conexión con la base de datos').addClass('alert alert-danger').show();
                     setTimeout(function() { $("#myElem").hide();
                     }, 2000);*/
                     },
                    beforeSend: function() {
                        // setting a timeout
                        $("#myElem").html('<i class="fas fa-spinner fa-spin"></i>&nbsp;Enviando código de recuperación...').removeClass('alert alert-danger').addClass('alert alert-info').show();
                        $("form button").prop("disabled", true); //deshabilito botones
                    }

                });

            }
            return false;
        });



        $('#recuperar_form').validate({
            rules: {
                usuario: {  required: true,
                            email: true}
            },
            messages:{
                usuario: {  required : "Ingrese su correo",
                            email: "Ingrese un correo válido"}
            },
            tooltip_options: {
                usuario: {trigger:'focus'}

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
        <h4>Restablecer contraseña (Paso 1/3)</h4>
        <hr class="hr-primary"/>




<form name ="recuperar_form" id="recuperar_form" method="POST" action="index.php">

    <div class="form-group">
        <label class="control-label" for="usuario">Correo</label>
        <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Correo" >
    </div>


    <div class="form-group">
        <button class="btn btn-primary btn-block" id="restaurar" name="restaurar" type="submit">Enviar</button>
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
