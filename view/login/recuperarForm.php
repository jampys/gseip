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
                params.operation='login';
                params.usuario=$('#usuario').val();
                params.contraseña=$('#contraseña').val();

                $.ajax({
                    url:"index.php",
                    type:"post",
                    data: params,
                    dataType:"json",//xml,html,script,json
                    success: function(data, textStatus, jqXHR) {

                        if(data['id'] >= 1){ //Accede al sistema
                            $("#myElem").html('<i class="fas fa-spinner fa-spin"></i>&nbsp; Accediendo al sistema...').addClass('alert alert-info').show();
                            setTimeout(function(){ $("#myElem").hide();
                                //window.location.href = "../../index.php";
                                window.location.href = "index.php";
                            }, 1500);
                        }
                        else {
                            $("#myElem").html(data['msg']).addClass('alert alert-danger').show();
                        }

                    },
                    /*error: function(data, textStatus, errorThrown) {
                     //alert(data.responseText);
                     $("#myElem").html('Error de conexión con la base de datos').addClass('alert alert-danger').show();
                     setTimeout(function() { $("#myElem").hide();
                     }, 2000);
                     },*/
                    beforeSend: function() {
                        // setting a timeout
                        $("#myElem").html('Accediendo al sistema ...').removeClass('alert alert-danger').addClass('alert alert-info').show();
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
                            email: "Ingrese un email válido"}
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




<form name ="recuperar_form" id="recuperar_form" method="POST" action="index.php">

    <div class="form-group">
        <label class="control-label" for="usuario">Recuperar contraseña</label>
        <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Ingrese su correo" >
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

