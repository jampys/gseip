<style>
    #brand-image{
        height: 120%;
    }

</style>



<script type="text/javascript">


    $(document).ready(function(){



        $('#new_password_form').validate({
            rules: {
                password: {
                    required: true,
                    maxlength: 10,
                    minlength: 4
                    },
                password_again: {
                    equalTo: "#password"
                }
            },
            messages:{
                password: {
                    required: "Ingrese una contraseña",
                    maxlength: "La contraseña no debe superar los 10 caracteres",
                    minlength: "La contraseña debe superar los 4 caracteres"
                },
                password_again: "La contraseña repetida debe coincidir"
            }
            /*tooltip_options: {
                password: {trigger:'focus'},
                password_again: {trigger:'focus'}

            }*/
        });


        $(document).on('click', '#enviar',function(){

            if ($("#new_password_form").valid()){
                //alert('boton restaurar');
                var params={};
                params.action='login';
                params.operation='saveNewPassword';
                params.password=$('#password').val();
                //params.contraseña=$('#contraseña').val();

                $.ajax({
                    url:"index.php",
                    type:"post",
                    data: params,
                    dataType:"json",//xml,html,script,json
                    success: function(data, textStatus, jqXHR) {

                        if(data['id'] >= 1){ //Envió codigo por email con exito
                            //$("#myElem").html('<i class="fas fa-spinner fa-spin"></i>&nbsp; Enviando código de recuperación...').addClass('alert alert-info').show();
                            $("#myElem").html(data['msg']).removeClass('alert alert-danger').addClass('alert alert-success').show();
                            setTimeout(function(){  $("#myElem").hide();
                                                    window.location.href = "index.php?action=login";
                                                 }, 1500);
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
                    }
                    /*beforeSend: function() {
                        // setting a timeout
                        $("#myElem").html('Enviando código de recuperación...').removeClass('alert alert-danger').addClass('alert alert-info').show();
                    }*/

                });

            }
            return false;
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
        <h4>Restablecer contraseña (Paso 3/3)</h4>
        <hr class="hr-primary"/>




<form name ="new_password_form" id="new_password_form" method="POST" action="index.php">

    <div class="alert alert-warning">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong><i class="fas fa-exclamation-triangle"></i>&nbsp;La contraseña debe contener</strong>
        <p>mínimo 4 caracteres</p>
        <p>máximo 10 caracteres</p>
        <!--<p>letras mayúsculas y minúsculas</p>
        <p>Al menos un número</p>-->
    </div>


    <div class="form-group">
        <label class="control-label" for="code">Nueva contraseña</label>
        <input class="form-control" type="password" name="password" id="password" placeholder="Nueva contraseña" >
    </div>

    <div class="form-group">
        <label class="control-label" for="code">Repita nueva contraseña</label>
        <input class="form-control" type="password" name="password_again" id="password_again" placeholder="Nueva contraseña" >
    </div>

    <div class="form-group">
        <button class="btn btn-primary btn-block" id="enviar" name="enviar" type="submit">Enviar</button>
    </div>

    <div class="form-group" style="text-align: center">
        <button id="regresar" name="regresar" type="button" class="btn btn-link">
            <small>Regresar al área de ingreso</small>
        </button>
    </div>

</form>

    <div id="myElem" class="msg" style="display:none">
        <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
    </div>

</div>


<div class="col-md-4"></div>

