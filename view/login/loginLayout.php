<!DOCTYPE html>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>


    <script type="text/javascript">

        $(document).ready(function(){


            $(document).on('click', '#ingresar',function(){
                if ($("#login_form").valid()){
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




            $(document).on('click', '#recuperar',function(){
                //alert('recuperar');
                window.location.href = "index.php?action=login&operation=recuperar-contraseña";
                return false;
            });



        });

    </script>




</head>
<body>



<div class="container">


    <div id="content" class="row">
        <?php include_once ($view->contentTemplate);  ?>
    </div>


</div>



<?php require_once('templates/footer.php'); ?>


</body>


</html>