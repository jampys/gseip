<style>
    #brand-image{
        height: 120%;
    }

</style>



<script type="text/javascript">


    $(document).ready(function(){



        /*$('#login_form').validate({
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
        });*/


        $(document).on('click', '#enviar',function(){

            //if ($("#code_form").valid()){
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
                            setTimeout(function(){ $("#myElem").hide();

                                //window.location.href = "index.php?action=login&operation=send-code";
                                alert('todo ok, a la ventana donde ingresa nueva contraseña');
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

            //}
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




<form name ="code_form" id="login_form" method="POST" action="index.php">

    <div class="form-group">
        <label class="control-label" for="code">Nueva contraseña</label>
        <input class="form-control" type="text" name="password" id="password" placeholder="Nueva contraseña" >
    </div>

    <div class="form-group">
        <label class="control-label" for="code">Repita nueva contraseña</label>
        <input class="form-control" type="text" name="password1" id="password1" placeholder="Repita nueva contraseña" >
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

    </div>

</div>


<div class="col-md-4"></div>

