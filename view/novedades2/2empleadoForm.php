<style>


    ul{
        list-style-type: none;
    }

</style>



<script type="text/javascript">


    $(document).ready(function(){



        $('#code_form').validate({
            rules: {
                code: {required: true}
            },
            messages:{
                code: "Ingrese el código de recuperación"
            }
            /*tooltip_options: {
                usuario: {trigger:'focus'},
                contraseña: {trigger:'focus'}
            }*/
        });


        $(document).on('click', '#enviar',function(){

            if ($("#code_form").valid()){
                //alert('boton restaurar');
                var params={};
                params.action='login';
                params.operation='check-code';
                params.code=$('#code').val();
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
                                                    window.location.href = "index.php?action=login&operation=toNewPasswordform";
                                                 }, 1500);
                        }
                        else {
                            $("form button").prop("disabled", false); //habilito botones
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
                        //$("#myElem").html('Enviando código de recuperación...').removeClass('alert alert-danger').addClass('alert alert-info').show();
                        $("form button").prop("disabled", true); //deshabilito botones
                    }

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



        <!--<div class="col-md-1"></div>-->


        <div class="col-md-12">


            <br/>
            <h4>Novedades 2 (2da parte)</h4>
            <hr class="hr-primary"/>





                <div class="row">

                    <div class="col-md-3">


                        <div class="form-group">

                            <ul>
                                <?php foreach ($view->empleados as $con){
                                    ?>
                                    <li value="<?php echo $con['id_empleado']; ?>" >
                                        <a href="#">
                                            <?php echo $con['nombre'];?>
                                        </a>

                                    </li>
                                <?php  } ?>
                            </ul>
                        </div>




                    </div>









                </div>








        </div>


        <!--<div class="col-md-1"></div>-->




    <div id="myElem" class="msg" style="display:none">

    </div>





