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

                            if(data >=1){ //Accede al sistema
                                $("#myElem").html('Accediendo al sistema ...').addClass('alert alert-info').show();
                                setTimeout(function(){ $("#myElem").hide();
                                                        window.location.href = "index.php?action=index";
                                                     }, 1500);
                                //$('#content').load('index.php',{action:"index"});
                                /*params={};
                                 params.action="index";
                                 $.post('controller/indexLayout.php',params,function(data, status, xhr){
                                 alert(data);
                                 });*/

                            }else if (data == 0){ //usuario inhabilitado
                                $("#myElem").html('Usuario inhabilitado').addClass('alert alert-danger').show();
                                setTimeout(function() { $("#myElem").hide();
                                }, 2000);
                            }else if (data == -1){ //usuario o contraseña invalidos
                                $("#myElem").html('Usuario o contraseña invalidos').addClass('alert alert-danger').show();
                                setTimeout(function() { $("#myElem").hide();
                                }, 2000);
                            }

                        },
                        error: function(data, textStatus, errorThrown) {
                            //alert(data.responseText);
                            $("#myElem").html('error conexion bd').addClass('alert alert-danger').show();
                            setTimeout(function() { $("#myElem").hide();
                            }, 2000);
                        }





                    });










                }
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